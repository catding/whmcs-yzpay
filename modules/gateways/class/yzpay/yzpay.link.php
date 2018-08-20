<?php
if (!defined("WHMCS")) {
	die("This file cannot be accessed directly");
}

class yzpay_link 
{
	public function get_paylink($params)
	{
		if (!function_exists("openssl_open"))
		{
			return '<span style="color:red">Fatal Error:管理员未开启openssl组件<br/>正常情况下该组件必须开启<br/>请开启openssl组件解决该问题</span>';
		}
		if (!function_exists("scandir"))
		{
			return '<span style="color:red">Fatal Error:管理员未开启scandir PHP函数<br/>支付宝Sdk 需要使用该函数<br/>请修改php.ini下的disable_function来解决该问题</span>';
		}
		if (empty($params['kdt_id']))
		{
			return "管理员未配置 店铺ID , 无法使用该支付接口";
		} 
		if (empty($params['client_id']))
		{
			return "管理员未配置 client_id  , 无法使用该支付接口";
		}	
		if (empty($params['client_secret']))
		{
			return "管理员未配置 client_secret  , 无法使用该支付接口";
		}	
		return $this->YzQrPay($params);
	}
	
	public function YzQrPay($params)
	{
		require_once __DIR__ ."/yzpay.class.php";

		$yzpay = new YzClient();
		$yzpay->setclientid($params['client_id']);
		$yzpay->setclientsecret($params['client_secret']);
		$yzpay->setkdtid($params['kdt_id']);
		$yzpay->setqrprice($params['amount']);
		$yzpay->setqrname("Billing"."-".$params['invoiceid']);	
		
		$result = $yzpay->YzQrPayServie();	
		if($result['response']['qr_code'])
		{
			return "<a href= '{$result['response']['qr_url']}' ><img src= '{$result['response']['qr_code']}' /><br>支付宝、微信扫码</a>";
		}else
		{
			return "二维码生成失败";
		}	
	}	
}
