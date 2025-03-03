<?php
namespace app\index\controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Cache;
use think\Request;
use Db;
use think\Controller;
use app\index\model\User;
use Cookie;
use app\index\model\MarkBook;
use app\index\model\Visit;
use app\Tool;
use Validate;
use Log;
use app\index\model\Error;
use Config;

class Index extends Controller
{
    public function index()
    {
        $mail = Cookie::get('mail');
        if(strlen($mail) >= 300){
            $mail = "null";
        }
        if(!$mail)
            $mail = 'null';
        $v = new Visit();
        $v->mail = $mail;
        $v->utc_time = date('Y-m-d H:i:s');
        $v->save();
        //return phpinfo();
        return $this->fetch('index');
    }

    public function sync(Request $request)
    {
        $validate = Validate::make([
            'mail'  => 'require|max:100',
            'token'  => 'require|max:100',
            'feature' => 'require|max:1000'
        ],
        [
            "token.require" => "未登录",
            "feature.require" => "没有特征"
        ]);
        if (!$validate->check($request->post())) {
            return json_encode(array("code"=>401,"data"=>$validate->getError()));
        }


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
        $userMarkBook = MarkBook::where('user', $mail)->field('name, url, i, folder')->select();
        //return var_dump($userMarkBook->toArray());
        $thisFeature = Tool::GetMarkBookFeature($userMarkBook->toArray());
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

        $files =  json_decode( $request->post('files'),true);
        for($i = 0; $i < count($files); $i ++)
        {
            $ico = "";//Tool::GetIcoUrl($files[$i]['url']);
            if(!$ico) $ico = "";
    
            $markbook = new MarkBook();
            $markbook->user = $user->mail;
            $name = $files[$i]['name'];
            $markbook->name = mb_strlen($name, 'utf8') > 100 ?mb_substr($name, 0, 50, 'utf8') : $name;
            $url = $files[$i]['url'];
            $markbook->url = mb_strlen($url, 'utf8') > 2000 ? mb_substr($url, 0, 1000, 'utf8') : $url;
            $folders = json_encode($files[$i]['folder'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if(mb_strlen($folders, 'utf8') < 1000)
                $markbook->folder =    $folders;
            else
                $markbook->folder=  '["folder too long"]';
            $markbook->ico = mb_strlen($ico, 'utf8') > 300 ? substr($ico, 0, 150) : $ico;
            $markbook->save();
            $files[$i] = $markbook;
        }
        return json_encode(array("code"=>200, 'data'=>$files));
    }

    public function editFile(Request $request)
    {
        $validate = Validate::make([
            'token'  => 'require|max:100',
            'mail' =>'require|max:100',
            'id' => 'require|max:100',
            'name' => 'require|max:100',
            'url' =>'require|max:2000'
        ],
        [
            "token.require" => "未登录",
            "mail.require" => "未登录",
            "id.require" => "没有数据",
            "name.require" => "没有数据",
            "url.require" => "没有数据"
        ]);
        
        if (!$validate->check($request->post())) {
            return json_encode(array("code"=>401,"data"=>$validate->getError()));
        }

        $token = $request->post('token');
        $user = User::get(['mail'=> $request->post('mail'),"pw"=>$token]);
        if(!$user)
        {
            return json_encode(array("code"=>401,"data"=>"用户错误"));
        }

        MarkBook::where(['i'=> $request->post('id')])
        ->update(['name'=> $request->post('name'), 'url'=>$request->post('url')]);

        return json_encode(array("code"=>200));
    }

    public function editFolder(Request $request)
    {
        $validate = Validate::make([
            'token'  => 'require|max:100',
            'mail' =>'require|max:100',
            'fromPath' => 'require|max:1000',
            'toPath' => 'require|max:1000'
        ],
        [
            "token.require" => "未登录",
            "mail.require" => "未登录",
            "toPath.require" => "没有数据",
            "fromPath.require" => "没有数据"
        ]);
        
        if (!$validate->check($request->post())) {
            return json_encode(array("code"=>401,"data"=>$validate->getError()));
        }

        $token = $request->post('token');
        $user = User::get(['mail'=> $request->post('mail'),"pw"=>$token]);
        if(!$user)
        {
            return json_encode(array("code"=>401,"data"=>"用户错误"));
        }

        // 定义需要替换的前缀和新前缀
        $oldFolder =  $request->post('fromPath');
        $newFolder = $request->post('toPath');
        //去除最后一个括号
        $oldFolder = substr($oldFolder, 0, -1) ;
        $newFolder = substr($newFolder, 0, -1) ;

        $records = MarkBook::where('folder','like', Tool::escapeLikeString($oldFolder). '%')->select();

        if (count($records) == 0) {
            return json_encode(array("code"=>401, "data" => "没有文件夹"));
        }
        foreach ($records as $record) {
            // 更新每条记录
            $record->folder = $newFolder . substr($record->folder,  strlen($oldFolder));
            $record->save();
        }
        return json_encode(array("code"=>200));
    }

    public function loginOrRegister(Request $request)
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
                return json_encode(array("code"=>200, "token"=>$user->pw));
            } 
            else 
            {
                return json_encode(array("code"=>403,"data"=>"写入数据库失败"));
            }
        }
        $allMarkBook = MarkBook::where("user", $userEmail)->select();
        return json_encode(array("code"=>200,"data"=>$allMarkBook, "token"=>$user->pw));
    }

    public function delMarkBooks(Request $request)
    {

        $validate = Validate::make([
            'token'  => 'require|max:100',
            'is' =>'require|max:20000',
        ],
        [
            "token.require" => "未登录",
            "is.require" => "没有数据",
        ]);

        if (!$validate->check($request->post())) {
            return json_encode(array("code"=>401,"data"=>$validate->getError()));
        }

        $token = $request->post("token");
        $stris = $request->post('is');
        //验证账号
        $user = User::get(['pw'=>$token]);
        if(!$user)
        {
            return json_encode(array("code"=>401,"data"=>"无用户"));
        }
        //转换id
        $is = json_decode($stris, true);
        //通过id删除
        for($i = 0; $i < count($is); $i ++)
        {
            $markbook = MarkBook::get(['i' => $is[$i]]);
            if(!$markbook)
            {
                return json_encode(array("code"=>401,"data"=>"无书签"));
            }
            $markbook->delete();
        }
        return json_encode(array("code"=>200));
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

    public function jsError(Request $request)
    {
        $info = $request->post('info');
        $mail = $request->post('mail', ' ');
        if(!$info)
        {
            return json_encode(array("code"=>401,"data"=>"无参数"));
        }

        $err = new  Error;
        $err->mail = $mail;
        $err->info = $info;
        $err->utc_time = date('Y-m-d H:i:s');
        $err->save();
        return json_encode(array("code"=>200,"data"=>""));
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
            return json_encode(array("code"=>401,"data"=>"间隔小于30秒"));
        }

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            $code = rand(1000,9999);
            Cache::set("VerifTime:".$ip , time(), 4);//防止疯狂提交错误邮箱
            //服务器配置
            $mail->CharSet ="UTF-8";                     //设定邮件编码
            $mail->SMTPDebug = 0;                        // 调试模式输出
            $mail->isSMTP();                             // 使用SMTP
            $mail->Host = Config::get('smtp_host');                // SMTP服务器
            $mail->SMTPAuth = true;                      // 允许 SMTP 认证
            $mail->Username = '';                // SMTP 用户名  即邮箱的用户名
            $mail->Password = '';             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
            $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
            $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

            $mail->setFrom('', 'MarkBook');  //发件人
            $mail->addAddress($userEmail, '');  // 收件人
            $mail->addReplyTo('', 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致

            $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            $mail->Subject = 'MarkBook账号邮箱验证';
            $mail->Body    = '<h1>这是您的的验证码:<a>'.$code.'</a></h1>'.date('Y-m-d H:i:s');
            $mail->AltBody =  '<h1>这是您的的验证码:'.$code;

            $mail->send();

            Cache::set("VerifCode:".$userEmail, $code, 600);
            Cache::set("VerifTime:".$ip , time(), 30);
            return json_encode(array("code"=>200,"data"=>"发送成功"));
        } catch (Exception $e) {
            if(Config::get('app_debug'))
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";//阿里每日2000条  腾讯500 超过会出错
            else
                return json_encode(array("code"=>401,"data"=>"请检查是否输入了正确的邮箱,或者服务器问题"));
        }
    }
}
