<?php
/**
 * 上传图片文件
 */
namespace driver;

class File
{
    /**
     * @param string $file 要检查的文件的路径
     * @return bool true 是图片 false 不是图片
     * 检查是不是为图片
     */
    private static function checkImage( $file ){
        if( ( $info = @getimagesize( $file ) ) === false ) {
            return false;
        }
        return true;
    }
    private function __construct(){}

    /**
     * 检查图片上传 并保存
     * @return array
     */
    private static function checkFile(){
        @$imageFile = $_FILES['image'];
        if(!isset($_FILES['image'])){
            return array(300,'没有上传文件');
        }
        if($_FILES['image']['error'] !== UPLOAD_ERR_OK ) {
            return array(302, '文件上传失败，错误码为：' . $_FILES['image']['error']);
        }
        if(!self::checkImage($imageFile['tmp_name'])){
            return array(301,'不是图片文件');
        }
        $md5 = md5_file($imageFile['tmp_name']);
        $path = BASE_DIR . '/images/' . $md5;
        if(move_uploaded_file($imageFile['tmp_name'], $path)){
            return array(200, $md5);
        } else {
            return array(400, '保存失败');
        }
    }

    /**
     * @return array 200 imageKey和图片完整路径；非200 错误信息
     */
    public static function getImageKey(){
        list($status, $msg) = self::checkFile();
        if($status !== 200){
            return array($status,$msg);
        }
        $md5 = $msg;
        return array(200,Image::getImageKey(BASE_DIR .'/images/' . $md5  ),$md5);
    }
    /**
     * 检查并保存文件
     * @return array
     */
    public static function save(){
        list($status, $msg) = self::checkFile();
        if($status !== 200){
            return array($status,$msg);
        }
        $path = BASE_DIR .'/images/' .$msg;
        $md5 = md5_file($path);
        $mysql = Mysqli::getInstance();
        $imageKey = Image::getImageKey($path);
        $mysql -> insertImage($md5, $imageKey);
        return array(200,'保存成功，指纹是' . $imageKey.'，指纹长度：'. strlen($imageKey) );
    }
}