<?php
class ControllerPaymentYandexMoney extends Controller {
	protected function index() {
		
		$this->language->load('payment/yandexmoney');

		$yandexMoney = new YandexMoneyObj();
		$yandexMoney->test_mode = $this->config->get('yandexmoney_testmode');
		$yandexMoney->org_mode = ((int)$this->config->get('yandexmoney_mode') == 2);
		
		$this->load->model('checkout/order');		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['action'] = $yandexMoney->getFormUrl();
		$this->data['order_id'] = $this->session->data['order_id'];
		$this->data['org_mode'] = $yandexMoney->org_mode;
		$this->data['account'] = $this->config->get('yandexmoney_account');
		$this->data['shop_id'] = $this->config->get('yandexmoney_shopid');
		$this->data['scid'] = $this->config->get('yandexmoney_scid');
		$this->data['customerNumber'] =$this->data['order_id'].' ' . $order_info['email'];
		$this->data['shopSuccessURL'] = $this->url->link('checkout/success', '', 'SSL');
		$this->data['shopFailURL'] = $this->url->link('checkout/failure', '', 'SSL');
		$this->data['formcomment'] = $this->config->get('config_name');
		$this->data['short_dest'] = $this->config->get('config_name');
		$this->data['comment'] = $order_info['comment'];
		$this->data['sum'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		$this->data['method_ym'] = $this->config->get('yandexmoney_method_ym');
		$this->data['method_cards'] = $this->config->get('yandexmoney_method_cards');
		$this->data['method_cash'] = $this->config->get('yandexmoney_method_cash');
		$this->data['method_mobile'] = $this->config->get('yandexmoney_method_mobile');
		$this->data['method_wm'] = $this->config->get('yandexmoney_method_wm');
		$this->data['method_ab'] = $this->config->get('yandexmoney_method_ab');
		$this->data['method_sb'] = $this->config->get('yandexmoney_method_sb');

		$this->data['method_label'] =  $this->language->get('text_method');
		$this->data['method_ym_text'] =  $this->language->get('text_method_ym');
		$this->data['method_cards_text'] =  $this->language->get('text_method_cards');
		$this->data['method_cash_text'] =  $this->language->get('text_method_cash');
		$this->data['method_mobile_text'] =  $this->language->get('text_method_mobile');
		$this->data['method_wm_text'] =  $this->language->get('text_method_wm');
		$this->data['method_sb_text'] =  $this->language->get('text_method_sb');
		$this->data['method_ab_text'] =  $this->language->get('text_method_ab');
		$this->data['order_text'] =  $this->language->get('text_order');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/yandexmoney.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/yandexmoney.tpl';
		} else {
			$this->template = 'default/template/payment/yandexmoney.tpl';
		}	
		
		$this->render();
	}

	public function callback() {
    	$ymObj = new YandexMoneyObj();
		$callbackParams = $this->request->post;
		$callbackParams = $_POST;
		$mode = $this->config->get('yandexmoney_mode');
		$ymObj->org_mode = ($mode == 2);
		$ymObj->password = $this->config->get('yandexmoney_password');
		$ymObj->shopid = $this->config->get('yandexmoney_shopid');
		$order_id =0;
		if ($ymObj->org_mode){
			if ($callbackParams['action'] == 'checkOrder'){
				$code = $ymObj->checkOrder($callbackParams);
				$ymObj->sendCode($callbackParams, $code);
			}
			if ($callbackParams['action'] == 'paymentAviso'){
				$order_id = (int)$callbackParams["orderNumber"];
				$ymObj->checkOrder($callbackParams, TRUE, TRUE);
			}
		}else{
			$check = $ymObj->individualCheck($callbackParams);
			if (!$check){
				exit;
			}else{
				$order_id = (int)$callbackParams["label"];
			}
		}

		if ($order_id){
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			$res = $this->model_checkout_order->confirm($order_id, $this->config->get('yandexmoney_order_status_id'));	
			$order_info = $this->model_checkout_order->getOrder($order_id);
		}		
	}
}

define("YM_PC", 'PC');
define("YM_AC", 'AC');
define("YM_GP", 'GP');
define("YM_MC", 'MC');
define("YM_WM", 'WM');
define("YM_AB", 'AB');
define("YM_SB", 'SB');

Class YandexMoneyObj {
	public $test_mode;
	public $org_mode;

	public $order_id;

	public $reciver;
	public $formcomment;
	public $short_dest;
	public $writable_targets = 'false';
	public $comment_needed = 'true';
	public $label;
	public $quickpay_form = 'shop';
	public $payment_type = '';
	public $targets;
	public $sum;
	public $comment;
	public $need_fio = 'true';
	public $need_email = 'true';
	public $need_phone = 'true';
	public $need_address = 'true';

	public $shopid;
	public $password;
	


	/*constructor*/
	public function __construct(){
		
	}

	public function getFormUrl(){
		if (!$this->org_mode){
			return $this->individualGetFormUrl();
		}else{
			return $this->orgGetFormUrl();
		}
	}

	public function individualGetFormUrl(){
		if ($this->test_mode){
			return 'https://demomoney.yandex.ru/quickpay/confirm.xml';
		}else{
			return 'https://money.yandex.ru/quickpay/confirm.xml';
		}
	}

	public function orgGetFormUrl(){
		if ($this->test_mode){
            return 'https://demomoney.yandex.ru/eshop.xml';
        } else {
            return 'https://money.yandex.ru/eshop.xml';
        }
	}

	public function checkSign($callbackParams){
		$string = $callbackParams['action'].';'.$callbackParams['orderSumAmount'].';'.$callbackParams['orderSumCurrencyPaycash'].';'.$callbackParams['orderSumBankPaycash'].';'.$callbackParams['shopId'].';'.$callbackParams['invoiceId'].';'.$callbackParams['customerNumber'].';'.$this->password;
		$md5 = strtoupper(md5($string));
		return ($callbackParams['md5']==$md5);
	}

	public function checkOrder($callbackParams, $sendCode=FALSE, $aviso=FALSE){ 
		
		if ($this->checkSign($callbackParams)){
			$code = 0;
		}else{
			$code = 1;
		}
		if ($sendCode){
			if ($aviso){
				$this->sendAviso($callbackParams, $code);	
			}else{
				$this->sendCode($callbackParams, $code);	
			}
			exit;
		}else{
			return $code;
		}
	}

	public function sendCode($callbackParams, $code){
		header("Content-type: text/xml; charset=utf-8");
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<checkOrderResponse performedDatetime="'.date("c").'" code="'.$code.'" invoiceId="'.$callbackParams['invoiceId'].'" shopId="'.$this->shopid.'"/>';
		echo $xml;
	}

	public function sendAviso($callbackParams, $code){
		header("Content-type: text/xml; charset=utf-8");
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<paymentAvisoResponse performedDatetime="'.date("c").'" code="'.$code.'" invoiceId="'.$callbackParams['invoiceId'].'" shopId="'.$this->shopid.'"/>';
		echo $xml;
	}

	public function individualCheck($callbackParams){
		$string = $callbackParams['notification_type'].'&'.$callbackParams['operation_id'].'&'.$callbackParams['amount'].'&'.$callbackParams['currency'].'&'.$callbackParams['datetime'].'&'.$callbackParams['sender'].'&'.$callbackParams['codepro'].'&'.$this->password.'&'.$callbackParams['label'];
		$check = (sha1($string) == $callbackParams['sha1_hash']);
		if (!$check){
			header('HTTP/1.0 401 Unauthorized');
			return false;
		}
		return true;
	
	}
}


?>
