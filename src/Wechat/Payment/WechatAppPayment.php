<?php

/**
 * Created by PhpStorm.
 * User: wu
 * Date: 2016/3/3
 * Time: 14:38
 */

namespace Wechat\Payment;

require_once __DIR__.'/lib/WxPay.Api.php';
require_once __DIR__.'/lib/WxPay.Data.php';

class WechatAppPayment extends WechatPaymentSupport
{
    /**
     * WechatAppPayment constructor.
     */
    public function __construct(array $wxPayConfig)
    {
        parent::__construct($wxPayConfig);
        parent::setTradeType('APP');
    }

    public function getAppPaymentParameters($order){
        /*
        $order =null;
        $order = $this->queryOrder($out_trade_no);
//        print json_encode($order)  .PHP_EOL;
            if ($order) {
                $input = new \WxPayReverse();
                $input->SetOut_trade_no($out_trade_no);
                $result = \WxPayApi::reverse($input);
                print('reserve:'.json_encode($result)) .PHP_EOL;
//            $input = new \WxPayCloseOrder();
//            $input->SetOut_trade_no($out_trade_no);
//            $result = \WxPayApi::closeOrder($input);
//            print('close:'.json_encode($result)) .PHP_EOL;
            }
        */

                $wxPayConfig = $this->wxPayApi->getWxPayConfig();
//                print( json_encode($order ) ) .PHP_EOL;
                $prepay_id = $order['prepay_id'];
                $response = array(
                    'appid'     => $wxPayConfig['APPID'],
                    'partnerid' => $wxPayConfig['MCHID'],
                    'prepayid'  => $prepay_id,
                    'package'   => 'Sign=WXPay',//暂时为固定值
                    'noncestr'  => \WxPayApi::getNonceStr(),
                    'timestamp' => time(),
                );
                $response['sign'] = $this->calculateSign($response, $wxPayConfig['KEY']);
                // send it to APP
            return $response;

    }


    /**
     * Get a sign string from array using app key
     *
     * @link https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=4_3
     */
    private function calculateSign($arr, $key)
    {
        //签名步骤一：按字典序排序参数
        ksort($arr);

        $buff = "";
        foreach ($arr as $k => $v) {
            if ($k != "sign" && $k != "key" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        //签名步骤二：在string后加入KEY
        //签名步骤三：MD5加密
        //签名步骤四：所有字符转为大写
        return strtoupper(md5($buff . "&key=" . $key));
    }


}