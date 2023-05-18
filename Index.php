<?php
namespace app\index\controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use think\Cache;
use think\Request;
use think\Controller;
use app\index\model\User;
use think\Cookie;
use app\index\model\MarkBook;
use app\Tool;
use think\Validate;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch('index');
    }

    public function sync(Request $request)
    {
        //验证
        $mail = $request->post('mail');
        $token = $request->post('token');
        $feature = $request->post('feature');
        if(!$mail || !$feature)
        {
            return json_encode(array("code"=>401,"data"=>"无参数"));
        }
        $user = User::get(['mail'=> $mail, 'pw' => $token]);
        if(!$user)
        {
            return json_encode(array("code"=>403,"data"=>"用户错误")); 
        }

        //获取数据库用户特征
        $userMarkBook = MarkBook::where('user', $mail)->select();
        $thisFeature = Tool::GetMarkBookFeature($userMarkBook);
        //比较
        if($thisFeature == $feature)
        {
            return  json_encode(array("code"=>200));
        }

        //不相同 需要同步 返回数据
        return json_encode(array("code"=>200,"data"=>$userMarkBook), true);
    }

    public function addMarkBooks(Request $request)
    {
        $validate = Validate::make([
            'token'  => 'require|max:100',
            'files' => 'require|max:4000000'
        ],
        [
            "token.require" => "未登录",
            "files.require" => "没有数据"
        ]);
        
        if (!$validate->check($request->post())) {
            return json_encode(array("code"=>401,"data"=>$validate->getError()));
        }

        $token = $request->post('token');
        $user = User::get(["pw"=>$token]);
        if(!$user)
        {
            return json_encode(array("code"=>401,"data"=>"不存在该用户"));
        }

        $files = json_decode( $request->post('files'),true);
        for($i = 0; $i < count($files); $i ++)
        {
            $ico = Tool::GetIcoUrl($files[$i]['url']);
            if(!$ico) $ico = "";
    
            $markbook = new MarkBook();
            $markbook->user = $user->mail;
            $name = $files[$i]['name'];
            $markbook->name = mb_strlen($name, 'utf8') > 100 ?mb_substr($name, 0, 50, 'utf8') : $name;
            $url = $files[$i]['url'];
            $markbook->url = mb_strlen($url, 'utf8') > 2000 ? mb_substr($url, 0, 1000, 'utf8') : $url;
            $folders = $files[$i]['folder'];
            $markbook->folder = mb_strlen($folders, 'utf8') > 100 ? mb_substr($folders, 0, 50, 'utf8'): $folders;
            $markbook->ico = mb_strlen($ico, 'utf8') > 300 ? substr($ico, 0, 150) : $ico;
            $markbook->save();
            $files[$i] = $markbook;
        }
        return json_encode(array("code"=>200, 'data'=>$files));
    }

    public function addMarkBook(Request $request)
    {
        $url = $request->post('url');
        $name = $request->post('name');
        $folder = $request->post('folder');
        $token = $request->post("token");
        if(!$url || !$name || !$folder || !$token)
        {
            return json_encode(array("code"=>401,"data"=>"无参数"));
        }

        $user = User::get(["pw"=>$token]);
        if(!$user)
        {
            return json_encode(array("code"=>401,"data"=>"不存在该用户"));
        }

        $ico = Tool::GetIcoUrl($url);
        if(!$ico) $ico = "";

        $markbook = new MarkBook();
        $markbook->user = $user->mail;
        $markbook->name = $name;
        $markbook->url = $url;
        $markbook->folder = $folder;
        $markbook->ico = $ico;
        $markbook->save();


        
        return json_encode(
            array("code"=>200,
            "data"=>$markbook
        ));
    }

    public function LoginOrRegister(Request $request)
    {
        $userEmail = $request->post('mail');
        if(!$userEmail)
        {
            return json_encode(array("code"=>401,"data"=>"无目标邮箱"));
        }

        $code = $request->post('code');
        if(!$code)
        {
            return json_encode(array("code"=>401,"data"=>"无验证码"));
        }

        if($code != Cache::get("VerifCode:".$userEmail))
        {
            Cache::pull("VerifCode:".$userEmail);
            return json_encode(array("code"=>401,"data"=>"验证码错误，请30秒后重新点击发送验证码"));
        }

        $user =  User::get(['mail' => $userEmail]);
        if(!$user)
        {
            $user = new User;
            $user->mail = $userEmail;
            $user->pw = md5($userEmail.strval(rand(10000000,99999999)));
            if ($user->save()) 
            {
                Cookie::forever("mail", $userEmail);
                Cookie::forever("token", $user->pw);
                return json_encode(array("code"=>200));
            } 
            else 
            {
                return json_encode(array("code"=>403,"data"=>"写入数据库失败"));
            }
        }
        $allMarkBook = MarkBook::where("user", $userEmail)->select();
        return json_encode(array("code"=>200,"data"=>$allMarkBook, "token"=>$user->pw));
    }

    public function delMarkBook(Request $request)
    {
        $token = $request->post("token");
        $i = $request->post('i');
        if(!$token || !$i)
        {
            return json_encode(array("code"=>401,"data"=>"参数为空"));
        }

        $user = User::get(['pw'=>$token]);
        if(!$user)
        {
            return json_encode(array("code"=>401,"data"=>"无用户"));
        }

        $markbook = MarkBook::get(['i'=>$i]);
        if(!$markbook)
        {
            return json_encode(array("code"=>401,"data"=>"无书签"));
        }

        $markbook->delete();
        return json_encode(array("code"=>200,));
    }

    public function delFolder(Request $request)
    {
        $token = $request->post("token");
        $name = $request->post('name');
        if(!$token || !$name)
        {
            return json_encode(array("code"=>401,"data"=>"参数为空"));
        }

        $user = User::get(['pw'=>$token]);
        if(!$user)
        {
            return json_encode(array("code"=>401,"data"=>"无用户"));
        }

        MarkBook::where(['user'=>$user->mail, 'folder'=>$name])->delete();
        return json_encode(array("code"=>200));
    }



    public function sendMail(Request $request)
    {
        $userEmail = $request->post('mail');
        if(!$userEmail)
        {
            return json_encode(array("code"=>401,"data"=>"无目标邮箱"));
        }

        $ip = $request->ip();
        if(!$ip)
        {
            return json_encode(array("code"=>401,"data"=>"无地址"));
        }

        if(!!Cache::get("VerifTime:".$ip))
        {
            return json_encode(array("code"=>401,"data"=>"间隔小于60秒"));
        }

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            $code = rand(1000,9999);
            Cache::set("VerifCode:".$userEmail, $code, 600);
            Cache::set("VerifTime:".$ip , time(), 30);
            //服务器配置
            $mail->CharSet ="UTF-8";                     //设定邮件编码
            $mail->SMTPDebug = 0;                        // 调试模式输出
            $mail->isSMTP();                             // 使用SMTP
            $mail->Host = 'smtpdm.aliyun.com';                // SMTP服务器
            $mail->SMTPAuth = true;                      // 允许 SMTP 认证
            $mail->Username = 'hui@xwtool.top';                // SMTP 用户名  即邮箱的用户名
            $mail->Password = '123789456goodGOODBB';             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
            $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
            $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

            $mail->setFrom('hui@xwtool.top', 'MarkBook');  //发件人
            $mail->addAddress($userEmail, '');  // 收件人
            $mail->addReplyTo('hui@xwtool.top', 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致

            $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            $mail->Subject = 'MarkBook账号邮箱验证';
            $mail->Body    = '<h1>这是您的的验证码:<a>'.$code.'</a></h1>'.date('Y-m-d H:i:s');
            $mail->AltBody =  '<h1>这是您的的验证码:'.$code;

            $mail->send();
            return json_encode(array("code"=>200,"data"=>"发送成功"));
        } catch (Exception $e) {
            return json_encode(array("code"=>401,"data"=>"发送失败:".$e));
        }
    }
}
