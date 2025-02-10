<?php
namespace app\test\controller;

use app\index\model\MarkBook;
use app\Tool;

class Index
{
    public function index()
    {
        echo phpinfo();
    }

    //百度  href="//www.baidu.com/favicon.ico"
    //csdn href="https://g.csdnimg.cn/static/logo/favicon32.ico"
    //webo  无法获取  用域名加favicon https://weibo.com/favicon.ico
    //菜鸟 没有link标签  https://c.runoob.com/ 通过名字获取
    //coco文档 还有  ../../gitbook/images/favicon.ico这种路径
    public function GetIcoTest() 
    {
  
        $startTime = time();
        // echo strpos('<link rel="shortcut icon" href="image/sbsm.ico" type="image/x-icon" />', 'rel="shortcut icon"');
        // return;

        
        
        echo "<br>spring:";
        echo Tool::GetIcoUrl('https://spring.io/docs/reference');  //herf 在rel前面的  还未处理
        echo "<br>useTIme:".(time()-$startTime);
        
        echo "<br>cocos:";
        echo Tool::GetIcoUrl("https://www.cocos.com/docs/");  //ssl
        echo "<br>useTIme:".(time()-$startTime);
        
        echo "<br>单词板:";
        echo Tool::GetIcoUrl("http://47.114.55.241:81/");    //html解析
        echo "<br>useTIme:".(time()-$startTime);

        // echo "<br>tuite:";
        // echo Tool::GetIcoUrl("https://twitter.com");    //不可访问
        // echo "<br>useTIme:".(time()-$startTime);
        
        // echo "<br>ditu:";
        // echo Tool::GetIcoUrl("http://bzdt.ch.mnr.gov.cn/download.html");
        // echo "<br>useTIme:".(time()-$startTime);

        // echo "<br>bing:";
        // echo Tool::GetIcoUrl("https://cn.bing.com/");
        // echo "<br>useTIme:".(time()-$startTime);
        // echo "<br>phpL:\n";
        // $phpico = Tool::GetIcoUrl('https://www.php.net/manual/zh/function.file-get-contents.php');
        // echo $phpico;
        // echo "<br>useTIme:".(time()-$startTime);
        // echo "</br></br>csdn:\n";
        // echo Tool::GetIcoUrl('https://blog.csdn.net/qq_32067045/category_6572128.html');
        // echo "<br>useTIme:".(time()-$startTime);
        // echo "</br></br>web:\n";
        // echo Tool::GetIcoUrl('https://weibo.com/favicon.ico');
        // echo "<br>useTIme:".(time()-$startTime);
        // echo "</br></br>baidu:";
        // echo Tool::GetIcoUrl('https://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=1&tn=baidu&wd=%E6%AD%A3%E5%88%99%E6%8F%90%E5%8F%96%E5%9F%9F%E5%90%8D&oq=preg_replace&rsv_pq=f5986250000c99bf&rsv_t=d426cN1w7dGxHw6wnCzDbciPhVO1%2BiRDVbh39%2B2oQybbZMbFSOS4eCPiWTA&rqlang=cn&rsv_enter=1&rsv_dl=tb&rsv_sug3=29&rsv_sug1=34&rsv_sug7=101&rsv_sug2=0&rsv_btype=t&inputT=12456&rsv_sug4=12457');
        // echo "<br>useTIme:".(time()-$startTime);
        return ;
    }

    public function CurlTest()
    {
        echo "res";
        echo Tool::Get('https://www.cocos.com/docs/');
    }

    public function DbTest()
    {
        // $userMarkBook = MarkBook::where('user', '3266873901@qq.com')->field('folder, i, name,url')->select();
        // //return dump($userMarkBook);
        // $thisFeature = Tool::GetMarkBookFeature($userMarkBook);
        // echo $thisFeature;



        // $oldFolder = '["哈","呵799"]';
        // $newFolder = '["哈","呵"]';

        // $oldFolder = substr($oldFolder, 0, -1) ;
        // $newFolder = substr($newFolder, 0, -1) ;

        // echo $newFolder.substr('["哈","呵799","1"]',strlen($oldFolder));



        // $m = new MarkBook();
        // $m->name = "ap";
        // $m->user="";
        // $m->url="";
        // $m->folder=  addslashes('["\u54c8"]');
        // $m->save();



        // $s = substr('["哈","呵7"]',0,-1);
        // $m = MarkBook::where('folder','like',$s.'%')->select();
        // return dump($m);
    }

    public function GetSuffix()
    {
        echo Tool::GetSuffix("https://spring.io//manifest-0e3065c2bbd1ef7a320e80861393f1a6.json");
        
    }

    //切换MarkBook的folder 形式"文件夹" <==> ['文件夹']
    // public function ChangeFolder()
    // {
    //     $allMarkBook = MarkBook::all();
    //     foreach ($allMarkBook as $markBook) {
    //         if(substr($markBook->folder,0,2) == '["' && substr($markBook->folder,-2) == '"]') {
                
    //         }else{
    //             $markBook->folder = '["'.$markBook->folder.'"]';

    //         }
    //         $markBook->save();
    //     }
    // }
}
