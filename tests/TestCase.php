<?php
/**
 * Created by PhpStorm.
 * User: wu
 * Date: 2016/3/3
 * Time: 15:01
 */

namespace Tests;

require_once  "../vendor/autoload.php";
error_reporting(E_ALL ^ E_NOTICE);
if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('GMT');
}

abstract class TestCase
{
    abstract public function execute() ;
}