<?php
/**
 * Created by PhpStorm.
 * User: 14013
 * Date: 2016/4/30
 * Time: 20:45
 */

namespace driver;


class Distance
{
    private static $_images; //从数据库得到的所有MD5和imagekey
    private static $_md5s; // 分析得到的比较相似的图片
    private function __construct(){}

    /**
     * 获得和imageKey 相近的key值
     * @param $imageKey string 图片指纹
     * @return array
     */
    public static function getSimilarPhotos($imageKey){
        $mysql = Mysqli::getInstance();
        self::$_images = $mysql ->getImages();
        foreach (self::$_images as $v){
            if( ! self::distanceOver( $imageKey ,$v['imagekey']) ) {
                self::$_md5s[] = $v['md5'];
            }
        }
        return self::$_md5s;
    }

    /**
     * 判断汉明距离是不是超过$distance
     * @param $key1 string
     * @param $key2 string
     * @param $distance int
     * @return bool
     */
    private static function distanceOver($key1, $key2, $distance = 5){
        $count = 0;
        for($i = 0; $i < 64; $i++){
            if($key1[$i] != $key2[$i]) {
                $count++;
                if($count >= $distance) {
                    return true;
                }
            }
        }
        if($count < $distance ) {
            return false;
        }
    }
}