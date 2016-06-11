<?php
/**
 * 得到图片的指纹
 */
namespace driver;


class Image
{
    private static $_image;
    private static $_path;
    private static $_info;
    private static $_grayArray;
    private function __construct(){}

    /**
     * @param $path string 完整图片路径
     * @return string 图片的指纹
     */
    public static function getImageKey($path){
        self::$_path = $path;
        self::createImage();
        self::thumbImage();
        self::reduceColor();
        $average = self::getAverage();
        return self::getHash($average);
    }

    /**
     * 创建图片资源
     * @return bool
     */
    private static function createImage() {
        $path = self::$_path;
        $info = getimagesize($path);
        self::$_info = $info;
        $create = 'imagecreatefrom';
        $create .= explode('/', $info['mime'])[1];
        if(($image = $create($path)) !== false){
            self::$_image = $image;
            return true;
        } else {
            return false;
        }
    }

    /**
     * 将图片压缩成8 * 8 的 图片
     * @return bool
     */
    private static function thumbImage(){
        $width = 8;
        $height = 8;
        $image_p = imagecreatetruecolor($width, $height);
        if(imagecopyresampled($image_p, self::$_image, 0, 0, 0, 0, $width, $height, self::$_info[0], self::$_info[1])){
            imagedestroy(self::$_image);
            self::$_image = $image_p;
            return true;
        } else {
            return false;
        }
    }

    /**
     * 将图片转成黑白色 并 转化为调色板
     * @return mixed
     */
    private static function reduceColor(){
        if(imageistruecolor(self::$_image)){
            imagetruecolortopalette(self::$_image, false, 64);
        }
        $width = 8;
        $height = 8;
        for($i = 0; $i < $width; $i++){
            for($j = 0; $j < $height; $j++){
                $col = imagecolorat(self::$_image, $i, $j);
                $rgb = imagecolorsforindex(self::$_image, $col);
                self::$_grayArray[] = round(0.229 * $rgb['red'] + 0.587 * $rgb['green'] + 0.114 * $rgb['blue']);
            }
        }
    }

    /**
     * 获得图片的 灰度平均值
     * @return int
     */
    private static function getAverage() {
        $sum = 0;
        foreach (self::$_grayArray as $key => $v){
            $sum += $v;
        }
        return (int)($sum / ($key+1));
    }

    /**
     * @param $average int 图片灰度平均值
     * @return string 图片指纹
     */
    private static function getHash($average){
        $hash = '';
        $width = 8;
        $height = 8;
        for($i = 0; $i < $width; $i++){
            for($j = 0; $j < $height; $j++){
                $col = imagecolorat(self::$_image, $i, $j);
                if(imagecolorsforindex(self::$_image, $col)['red'] >= $average){
                    $hash .= '1';
                } else {
                    $hash .= '0';
                }
            }
        }
        return $hash;
    }

}