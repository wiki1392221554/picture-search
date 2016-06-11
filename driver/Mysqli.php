<?php
namespace driver;

class Mysqli
{
    private static $_mysqli;//mysql连接
    private static $_instance; //mysqli对象  单例模式
    private function __construct(){}
    /**
     * 得到mysqli对象
     * @return mysqli
     */
    public static function getInstance(){
        if(!self::$_instance){
            self::$_instance = new self();
            self::init();
        }
        return self::$_instance;
    }

    /**
     * @param $path string 图片md5
     * @param $imageKey string 图片指纹
     * @return mixed
     */
    public function insertImage($md5, $imageKey){
        $sql = "insert into images (imagekey, md5) VALUE ('{$imageKey}', '{$md5}')";
        return self::$_mysqli -> query( $sql );
    }

    /**
     * 获得数据库中所有的imageKeys 和 md5
     * @return mixed
     */
    public function getImages(){
        $res = self::$_mysqli -> query('select `md5`,`imagekey` from images');
        return $res -> fetch_all(MYSQLI_ASSOC);
    }
    /**
     * 初始化mysql连接
     */
    private static function init(){
        self::$_mysqli = mysqli_connect('127.0.0.1','root','root','image');
        self::$_mysqli->query('set names utf8');
    }
}