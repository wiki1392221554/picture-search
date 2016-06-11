<?php
    define('BASE_DIR',__DIR__);
    require_once BASE_DIR .'/driver/AutoLoader.php';
    spl_autoload_register('driver\AutoLoader::loaderClass');
    var_dump(driver\File::save());