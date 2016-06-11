<?php

namespace driver;


class AutoLoader
{
    /**
     * 自动加载类
     * @param $className
     */
    public static function loaderClass($className){
        $className = str_replace('\\','/',$className);
        require_once BASE_DIR . '/' .$className .'.php';
    }
}