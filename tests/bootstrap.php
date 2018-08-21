<?php

ini_set("max_execution_time", "0");
require_once __DIR__.'/../vendor/autoload.php';

function rmdir_r($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? rmdir_r("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}
