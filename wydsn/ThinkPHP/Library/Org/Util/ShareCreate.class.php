<?php
/**
 * 分享图片管理类
 */

class ShareCreate
{
    /**
     * 分享图片生成
     * @param $gData  商品数据，array
     * @param $codeName 二维码图片
     * @param $fileName string 保存文件名,默认空则直接输入图片
     */
    public function createSharePng($gData, $codeName, $fileName = '')
    {
        // 当前目录绝对路径
        $root_path          = $_SERVER['DOCUMENT_ROOT'];

        // 拼接成绝对路径
        $codeName           = $codeName ? ($this->isHttp($codeName) ? $codeName : $root_path . $codeName) : '';
        $fileName           = $fileName ? ($this->isHttp($fileName) ? $fileName : $root_path . $fileName) : '';
        if (isset($gData['img'])) {
            $gData['img']   = $this->isHttp($gData['img']) ? $gData['img'] : $root_path . $gData['img'];
        }

        if (isset($gData['avatar'])) {
            $gData['avatar']= $this->isHttp($gData['avatar']) ? $gData['avatar'] : $root_path . $gData['avatar'];
        }


        //创建画布
        $im                 = imagecreatetruecolor(335, 478);
    
        //填充画布背景色
        $color              = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $color);
    
        //字体文件
        $goods_name         = $root_path . "/Public/Upload/Share/ttf/goods_name.ttf";
        $nickname           = $root_path . "/Public/Upload/Share/ttf/nickname.ttf";
    
        //设定字体的颜色
        $font_color_1       = ImageColorAllocate($im, 153, 153, 153);
        $goods_name_color   = ImageColorAllocate($im, 51, 51, 51);
        $nickname_color     = ImageColorAllocate($im, 51, 51, 51);

    
        //商品图片
        list($g_w, $g_h)    = getimagesize($gData['img']);
        $goodImg            = $this->createImageFromFile($gData['img']);
        imagecopyresized($im, $goodImg, 0, 0, 0, 0, 335, 335, $g_w, $g_h);
    
        // 用户头像
        list($avatar_w, $avatar_h)  = getimagesize($gData['avatar']);
        $avatarImg                  = $this->createImageFromFile($gData['avatar']);
        imagecopyresized($im, $avatarImg, 20, 345, 0, 0, 48, 48, $avatar_w, $avatar_h);

        // 用户昵称
        $nickTitle                  = $this->cnRowSubstr($gData['nickname'], 1, 7);
        imagettftext($im, 14, 0, 84, 375, $nickname_color ,$nickname, $nickTitle[1]);
    
        //名称
        $theTitle               = $this->cnRowSubstr($gData['name'], 2, 9);
        imagettftext($im, 14, 0, 20, 428, $goods_name_color , $goods_name, $theTitle[1]);
        imagettftext($im, 14, 0, 20, 453, $goods_name_color , $goods_name, $theTitle[2]);

        //二维码
        list($code_w, $code_h)  = getimagesize($codeName);
        $codeImg                = $this->createImageFromFile($codeName);
        imagecopyresized($im, $codeImg, 215, 344, 0, 0, 100, 100, $code_w, $code_h);

        //长按识别小程序码
        imagettftext($im, 10, 0, 213, 462, $font_color_1 , $goods_name, '长按识别小程序码');
    
        //输出图片
        if ($fileName) {
            imagepng ($im,$fileName);
        } else {
            Header("Content-Type: image/png");
            imagepng ($im);
        }
    
        //释放空间
        imagedestroy($im);
        imagedestroy($goodImg);
        imagedestroy($avatarImg);
        imagedestroy($codeImg);
    }
    
    /**
     * 从图片文件创建Image资源
     * @param $file 图片文件，支持url
     * @return bool|resource    成功返回图片image资源，失败返回false
     */
    public function createImageFromFile($file)
    {
        if ($this->isHttp($file)) {
            $fileSuffix = $this->getNetworkImgType($file);
        } else {
            $fileSuffix = pathinfo($file, PATHINFO_EXTENSION);
        }
    
        if (!$fileSuffix) return false;
    
        switch ($fileSuffix) {
            case 'jpeg':
                $theImage = @imagecreatefromjpeg($file);
                break;
            case 'jpg':
                $theImage = @imagecreatefromjpeg($file);
                break;
            case 'png':
                $theImage = @imagecreatefrompng($file);
                break;
            case 'gif':
                $theImage = @imagecreatefromgif($file);
                break;
            default:
                $theImage = @imagecreatefromstring(file_get_contents($file));
                break;
        }
    
        return $theImage;
    }

    /**
     * 获取网络图片类型
     * @param $url  网络图片url,支持不带后缀名url
     * @return bool
     */
    public function getNetworkImgType($url)
    {
        $ch         = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url); //设置需要获取的URL
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);//设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //支持https
        curl_exec($ch);//执行curl会话
        $http_code  = curl_getinfo($ch);//获取curl连接资源句柄信息
        curl_close($ch);//关闭资源连接
    
        if ($http_code['http_code'] == 200) {
            $theImgType = explode('/',$http_code['content_type']);
    
            if ($theImgType[0] == 'image') {
                return $theImgType[1];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * 分行连续截取字符串
     * @param $str  需要截取的字符串,UTF-8
     * @param int $row  截取的行数
     * @param int $number   每行截取的字数，中文长度
     * @param bool $suffix  最后行是否添加‘...’后缀
     * @return array    返回数组共$row个元素，下标1到$row
     */
    public function cnRowSubstr($str, $row = 1, $number = 10, $suffix = true)
    {
        $result     = [];
        for ($r = 1; $r <= $row; $r++) {
            $result[$r] = '';
        }
    
        $str        = trim($str);
        if (!$str) return $result;
    
        $theStrlen  = strlen($str);
    
        //每行实际字节长度
        $oneRowNum  = $number * 3;
        for ($r = 1; $r <= $row; $r++) {
            if ($r == $row and $theStrlen > $r * $oneRowNum and $suffix) {
                $result[$r] = $this->mgCnSubstr($str,$oneRowNum-6,($r-1)* $oneRowNum) .'...';
            } else {
                $result[$r] = $this->mgCnSubstr($str,$oneRowNum,($r-1)* $oneRowNum);
            }
            if ($theStrlen < $r * $oneRowNum) break;
        }
    
        return $result;
    }
    
    /**
     * 按字节截取utf-8字符串
     * 识别汉字全角符号，全角中文3个字节，半角英文1个字节
     * @param $str  需要切取的字符串
     * @param $len  截取长度[字节]
     * @param int $start    截取开始位置，默认0
     * @return string
     */
    public function mgCnSubstr($str, $len, $start = 0)
    {
        $q_str      = '';
        $q_strlen   = ($start + $len)>strlen($str) ? strlen($str) : ($start + $len);
    
        //如果start不为起始位置，若起始位置为乱码就按照UTF-8编码获取新start
        if ($start and json_encode(substr($str,$start,1)) === false) {
            for($a = 0; $a < 3; $a++) {
                $new_start  = $start + $a;
                $m_str      = substr($str,$new_start,3);

                if (json_encode($m_str) !== false) {
                    $start = $new_start;
                    break;
                }
            }
        }
    
        //切取内容
        for ($i = $start; $i < $q_strlen; $i++) {
            //ord()函数取得substr()的第一个字符的ASCII码，如果大于0xa0的话则是中文字符
            if (ord(substr($str,$i,1)) > 0xa0) {
                $q_str .= substr($str,$i,3);
                $i+=2;
            } else {
                $q_str .= substr($str,$i,1);
            }
        }

        return $q_str;
    }

    /**
     * 判断路径是否是网路路径
     */
    public function isHttp($str)
    {
        $res = preg_match('/http(s)?:\/\//', $str);

        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}
?>