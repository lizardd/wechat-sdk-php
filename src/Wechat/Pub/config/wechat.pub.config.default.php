<?php

/**
 * User: wu
 * Date: 2015/12/7
 * Time: 15:54
 * Update: 2016/3/3 15:22
 */

//微信公众平台默认配置
return [
    'APPID' => 'wx8....',
    'KEY' => 'BB15a.....',
    'APPSECRET' => 'aa15af3.....',
    //=======【curl代理设置】===================================
    /**
     * 这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @var unknown_type
     */
    'CURL_PROXY_HOST' => "0.0.0.0",
    'CURL_PROXY_PORT' => 0,//8080;
];