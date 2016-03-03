<?php
/**
 * Created by PhpStorm.
 * User: wu
 * Date: 2015/11/27
 * Time: 15:08
 */

namespace Wechat\Payment;

require_once __DIR__.'/lib/WxPay.Api.php';
require_once __DIR__.'/lib/WxPay.Data.php';
require_once __DIR__.'/lib/WxPay.Notify.php';

class WechatPaymentNotifyCallback extends \WxPayNotify
{
    const log_tag="WechatPayment.Notify";

    private $on_trade_success;
    private $logger;

    /**
     * WechatPaymentNotifyCallback constructor.
     * @param $on_trade_success
     */
    public function __construct($logger = null)
    {
        $this ->logger = $logger;
    }


    /**
     * @param callback $on_trade_success
     */
    public function setOnTradeSuccess(array $on_trade_success)
    {
        $this->on_trade_success = $on_trade_success;
    }
//    public function __construct($on_trade_success){
//        $this->on_trade_success =$on_trade_success;
//    }

    protected function log($logContent){
    }

    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->setWxPayApi($this->wxPayApi);
        $input->SetTransaction_id($transaction_id);
        $result = $this->wxPayApi->orderQuery($input);
//        if ($this->logger) {
//            $logContent = static::log_tag . " query:" . json_encode($result);
//            $this->logger->log($logContent);
//        }
        if(    array_key_exists("return_code",$result)
            && array_key_exists("result_code",$result)
            && array_key_exists('trade_state',$result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS"
            && $result['trade_state'] == "SUCCESS"
        ) {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        $this->log(static::log_tag. " call back:" . json_encode($data));
//        $notfiyOutput = array();
        if(!array_key_exists("transaction_id", $data) && !array_key_exists("out_trade_no", $data)){
            $msg = "输入参数不正确";
            return false;
        }

        $transaction_id=$data["transaction_id"];
        $out_trade_no = $data["out_trade_no"];

        //查询订单，判断订单真实性
        if(!$this->Queryorder($transaction_id)){
            $msg = "订单查询失败";
            return false;
        }

        try{
//            $this->trade_success($out_trade_no,$transaction_id);
//            $this->on_trade_success($out_trade_no,$transaction_id);
            call_user_func($this->on_trade_success, $out_trade_no,$transaction_id);
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

//    abstract protected function trade_success($out_trade_no,$transaction_id);
}
