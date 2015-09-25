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

		$list_methods=array('ym','cards','cash','mobile','wm','sb','ab','pb','ma','qw','qp','mp');
		foreach ($list_methods as $m_item){
			$this->data['method_'.$m_item] = $this->config->get('yandexmoney_method_'.$m_item);
			$this->data['method_'.$m_item.'_text'] =  $this->language->get('text_method_'.$m_item);
		}
		$this->data['mpos_page_url'] = $this->url->link('payment/yandexmoney/confirm','', 'SSL');
		$this->data['method_label'] =  $this->language->get('text_method');
		$this->data['order_text'] =  $this->language->get('text_order');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/yandexmoney.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/yandexmoney.tpl';
		} else {
			$this->template = 'default/template/payment/yandexmoney.tpl';
		}	
		
		$this->render();
	}
	public function confirm() {
		$this->load->model('checkout/order');
		$finish=$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
		$this->cart->clear();
		if (isset($this->request->post['paymentType']) && ($this->request->post['paymentType']=='MP')){
			$this->redirect($this->url->link('information/information', 'information_id='.$this->config->get('yandexmoney_page_mpos'), 'SSL'));
		}
	}
	public function callback() {
    	$ymObj = new YandexMoneyObj();
		$callbackParams = $this->request->post;
		$callbackParams = $_POST;
		$mode = $this->config->get('yandexmoney_mode');
		$ymObj->org_mode = ($mode == 2);
		$ymObj->password = $this->config->get('yandexmoney_password');
		$ymObj->shopid = $this->config->get('yandexmoney_shopid');
		if (isset($callbackParams["orderNumber"]) || isset($callbackParams["label"])){
			$order_id = ($ymObj->org_mode)?$callbackParams["orderNumber"]:$callbackParams["label"];
		}else{ $order_id =0;}
		if ($ymObj->checkSign($callbackParams)){
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			if ($order_info!=false){
				$comment=($ymObj->org_mode && $callbackParams['paymentType']=="MP" && isset($callbackParams['orderDetails']))?$callbackParams['orderDetails']:'';
				if (isset($callbackParams['action']) && $callbackParams['action'] == 'paymentAviso'){
					$res = $this->model_checkout_order->update($order_id, $this->config->get('yandexmoney_order_status_id'), "Номер транзакции: ".$callbackParams['invoiceId'].". Сумма: ".$callbackParams['orderSumAmount'].' '.$comment);
				}elseif (isset($callbackParams["label"]) && !$ymObj->org_mode){
					$sender=($callbackParams['sender']!='')?"Номер кошелька Яндекс.Денег: ".$callbackParams['sender'].".":'';
					$res = $this->model_checkout_order->update($order_id, $this->config->get('yandexmoney_order_status_id'), $sender." Сумма: ".$callbackParams['amount'].' '.$comment);
				}else{
					$res = $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'),$comment);
				}
				$ymObj->sendCode($callbackParams, "0");
			}elseif (isset($callbackParams['paymentType']) && $callbackParams['paymentType']=="MP"){
				//Заказа нет и пока будем отвечать успехом
				$ymObj->sendCode($callbackParams, "0");
			}else{
				$ymObj->sendCode($callbackParams, "100");
			}
		}else{
			$ymObj->sendCode($callbackParams, "1");
		}
	}
}

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
		if ($this->org_mode){
			$string = $callbackParams['action'].';'.$callbackParams['orderSumAmount'].';'.$callbackParams['orderSumCurrencyPaycash'].';'.$callbackParams['orderSumBankPaycash'].';'.$callbackParams['shopId'].';'.$callbackParams['invoiceId'].';'.$callbackParams['customerNumber'].';'.$this->password;
			$md5 = strtoupper(md5($string));
			return ($callbackParams['md5']==$md5);
		}else{
			$string = $callbackParams['notification_type'].'&'.$callbackParams['operation_id'].'&'.$callbackParams['amount'].'&'.$callbackParams['currency'].'&'.$callbackParams['datetime'].'&'.$callbackParams['sender'].'&'.$callbackParams['codepro'].'&'.$this->password.'&'.$callbackParams['label'];
			$check = (sha1($string) == $callbackParams['sha1_hash']);
			if (!$check){
				header('HTTP/1.0 401 Unauthorized');
				return false;
			}
			return true;
		}
	}

	public function sendCode($callbackParams, $code){
		if (!$this->org_mode) return false;
		header("Content-type: text/xml; charset=utf-8");
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<'.$callbackParams['action'].'Response performedDatetime="'.date("c").'" code="'.$code.'" invoiceId="'.$callbackParams['invoiceId'].'" shopId="'.$this->shopid.'"/>';
		echo $xml;
	}
}
?>