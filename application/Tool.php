<?php
namespace app;

use app\index\model\MarkBook;
use Exception;
use think\Config;

class Tool{

    public static function GetIcoUrl($url)
    {
        $host = Tool::ParseUrlHost($url);
        $icoUrl = Tool::NameGetIcoUrl($host);
        if($icoUrl)
        {
            return $icoUrl;
        }

        $icoUrl =  Tool::ParseGetIcoUrl($host);
        if(Config::get("get_url_debug")) 
        {
            echo "<br>icoUrlRes:";
            dump($icoUrl);
        }
        if(!$icoUrl)
        {
            return false;
        }
        $spos = strpos($icoUrl, "http");//很多都不是完整url
        if($spos !== 0)
        {   
            $icoUrl =  $host.$icoUrl;
        }
        if(Config::get("get_url_debug")) 
        {
            echo "<br>res:".$icoUrl;
        }
        return $icoUrl;
    }

    public static function NameGetIcoUrl($url)
    {
        $icoUrl =$url."favicon.ico";
        try
        {
            if(file_get_contents($icoUrl,false,Config::get('getContext')))
            {
                return $icoUrl;
            }
            else   
                return false;
        }
        catch(Exception $e)
        {
            return false;
        }

    }

    public static function GetMarkBookFeature($markBooks)
    {
        //根据i排序
        usort($markBooks,function($a,$b){
            return $a['i'] - $b['i'];
        });
        $thisFeature = ""; 


        // 提取多个列
        $markBooks = array_map(function($item) {
        return [
            'name' => $item['name'],
            'url' => $item['url'],
            'i' => $item['i'],

            'folder' => $item['folder']
        ];
        }, $markBooks);
        $thisFeature = json_encode($markBooks, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
        return md5($thisFeature);
    }

    

    public static function ParseGetIcoUrl($url)
    {
        $iconUrl = false;
        try
        {
            $html = Tool::Get($url);
            $iconUrl = Tool::ParseIcon($html);
            $suffix = Tool::GetSuffix($iconUrl);
            if(!in_array($suffix, Config::get("ico_suffix_names")))
            {
                if(Config::get('get_url_debug') == true) echo "<br>获取到非图片";
                return false;
            }
        }
        catch(Exception $e)
        {
            if(Config::get('get_url_debug') == true) echo "访问网址异常".$e;
            return false;
        }
        return $iconUrl;
    }

    public static function ParseUrlHost($url)
    {
        $url = preg_replace('/(?<!\/)\/([^\/].+)/',"",$url);
        if($url[strlen($url) - 1] !== '/')
        {
            return $url.'/';
        }
        else
        {
            return $url;
        }
    }

    public static function Get($url)
    {
        $curlObj = curl_init();
        $ssl = stripos($url,'https://') === 0 ? true : false;
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_AUTOREFERER => 1,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)',
            CURLOPT_TIMEOUT => 2,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
            CURLOPT_HTTPHEADER => ['Expect:'],
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_REFERER => ""
        ];
        if (!empty($header)) {
            $options[CURLOPT_HTTPHEADER] = $header;
        }
        if ($ssl) {
            //support https
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }
        curl_setopt_array($curlObj, $options);
        $html = curl_exec($curlObj);
        curl_close($curlObj);
        return $html;
    }

    public static function GetSuffix($name)
    {
        $name = str_replace('//', '', $name);
        return str_replace(preg_replace('/.[^.]+$/', '', $name), "", $name);
    }

    public static function ParseIcon($html)
    {
        if(Config::get('get_url_debug') == true) echo "html:".dump($html);
        $html = preg_replace(array('/\s/'), array(''), $html);
        $html = preg_replace("/'/", '"', $html);

        $siconPos = strpos($html, 'rel="shortcuticon"');//源数据确实没有空格
        if($siconPos) $siconPos += 18;
        $iconPos = strpos($html, 'rel="icon"');
        if($iconPos) $iconPos += 10;

        if(!$siconPos && !$iconPos)
        {
            if(Config::get('get_url_debug') == true) echo "<br>没获取到图标link开头";
            return false;
        } 

        $linkStart = 0;
        if($siconPos && $iconPos)//如果两个都找到了就选第一个
        {
            $linkStart = min($siconPos, $iconPos);
        }
        else//如果只找到一个就选有的哪个
        {
            $linkStart = max($siconPos, $iconPos);
        }
        $endPos = strpos($html, ">", $linkStart);
        if(!$endPos) 
        {
            if(Config::get('get_url_debug') == true) echo "<br>没获取到图标link结尾";
            return false; 
        }

        $start = strpos($html, 'href="', $linkStart);
        if(!$start)
        {
            if(Config::get('get_url_debug') == true) echo "<br>没获取href";
            return false;
        }

        $start += 6;
        $end = strpos($html, '"',  $start);
        if(!$end)
        {
            if(Config::get('get_url_debug') == true) echo "<br>没获取结尾";
            return false;
        }

        return substr($html, $start, $end - $start);
    }


}