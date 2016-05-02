<?php
/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/4/30
 * Time: 23:15
 */

require 'WebAutoLoader.php';

$webApp = Http::getInstance();

try {
    $webApp->run();
} catch (Exception $e) {
    // handle runtime exception
    throw $e;
}