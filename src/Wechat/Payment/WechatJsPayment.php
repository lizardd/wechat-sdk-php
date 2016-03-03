<?php
/**
 * Created by PhpStorm.
 * User: wu
 * Date: 2016/3/3
 * Time: 14:50
 */

namespace Wechat\Payment;


class WechatJsPayment extends WechatPaymentSupport
{

    public function __construct(array $wxPayConfig)
    {
        parent::__construct($wxPayConfig);
        parent::setTradeType('JSAPI');
    }
    /**
     *
     * 获取jsapi支付的参数
     * @param array $UnifiedOrderResult 统一支付接口返回的数据
     * @throws \WxPayException
     *
     * @return array 可直接填入转换为json后作为jsapi参数
     */
    public function getJsApiParameters($UnifiedOrderResult)
    {
        if(!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new \WxPayException("参数错误");
        }
        $jsapi = new \WxPayJsApiPay();
        $jsapi->setWxPayApi($this->wxPayApi);
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(\WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        $parameters = $jsapi->GetValues();
        return $parameters;
    }

}