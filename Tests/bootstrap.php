<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

spl_autoload_register(function ($className) {
    if (strpos($className, "Hnk\\HnkFrameworkBundle") === 0) {
        $path = sprintf("%s/%s.php", dirname(__DIR__), str_replace('\\', "/", str_replace("Hnk\\HnkFrameworkBundle", "", $className)));
        if (file_exists($path)) {
            require_once $path;
        }
    }
});