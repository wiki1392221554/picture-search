<?php
    define('BASE_DIR',__DIR__);
    require_once BASE_DIR .'/driver/AutoLoader.php';
    spl_autoload_register('driver\AutoLoader::loaderClass');
    @list($status ,$imageKey, $path) = driver\File::getImageKey();
    if($status !== 200) {
        echo $imageKey;
    } else {
        $md5s = \driver\Distance::getSimilarPhotos($imageKey);
        echo "原图片</br>";
        echo "<img style='width:200px;height:150px;' src=\"images/{$path}\" /></br>";
        if(is_array($md5s)){
            echo "相似图片</br>";
            foreach ($md5s as $md5) {
                echo "<img style='width:200px;height:150px;padding-right:5px;' src=\"images/{$md5}\" />";
            }
        } else {
            echo "没有相似的图片<br />";
        }
    }