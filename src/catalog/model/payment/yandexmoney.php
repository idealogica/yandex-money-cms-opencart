<?php
class ControllerPaymentYandexMoney extends Controller {
	private function payment($order_info, $child = false) {
		$this->language->load('payment/yandexmoney');

		$yandexMoney = new YandexMoneyObj();
		$yandexMoney->test_mode = $this->config->get('yandexmoney_testmode');
		$yandexMoney->org_mode = ((int)$this->config->get('yandexmoney_mode') >= 2);
		$yandexMoney->epl =((int)$this->config->get('yandexmoney_mode') == 3);

		if (isset($order_info['email'])) $this->data['email'] = $order_info['email'];
		if (isset($order_info['telephone'])) $this->data['phone'] = $order_info['telephone'];
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['action'] = $yandexMoney->getFormUrl();
		$this->data['epl'] = $yandexMoney->epl;
		$this->data['org_mode'] = $yandexMoney->org_mode;
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['account'] = $this->config->get('yandexmoney_account');
		$this->data['shop_id'] = $this->config->get('yandexmoney_shopid');
		$this->data['scid'] = $this->config->get('yandexmoney_scid');
		$this->data['customerNumber'] =$order_info['order_id'].' ' . $order_info['email'];

		$this->data['shopSuccessURL'] = (!$this->config->get('yandexmoney_page_success'))? $this->url->link('checkout/success', '', 'SSL'):$this->url->link('information/information', 'information_id='.$this->config->get('yandexmoney_page_success'));
		$this->data['shopFailURL'] =(!$this->config->get('yandexmoney_page_fail'))? $this->url->link('checkout/failure', '', 'SSL'):$this->url->link('information/information', 'information_id='.$this->config->get('yandexmoney_page_fail'));

		$this->data['formcomment'] = $this->config->get('config_name');
		$this->data['short_dest'] = $this->config->get('config_name');
		$this->data['comment'] = $order_info['comment'];
		$this->data['sum'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$this->data['allow_methods']=array();
		$this->data['default_method'] = $this->config->get('yandexmoney_default_method');
		foreach (array('AC'=>'cards', 'PC'=>'ym', 'GP'=>'cash', 'MC'=>'mobile', 'WM'=>'wm', 'SB'=>'sb', 'AB'=>'ab', 'PB'=>'pb', 'MA'=>'ma', 'QW'=>'qw', 'QP'=>'qp', 'MP'=>'mp') as $name => $value){
			if ($this->config->get('yandexmoney_method_'.$value)) $this->data['allow_methods'][$name] = $this->language->get('text_method_'.$value);
		}
		$this->data['mpos_page_url'] = $this->url->link('payment/yandexmoney/confirm','', 'SSL');
		$this->data['method_label'] =  $this->language->get('text_method');
		$this->data['order_text'] =  $this->language->get('text_order');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/yandexmoney.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/yandexmoney.tpl';
		} else {
			$this->template = 'default/template/payment/yandexmoney.tpl';
		}
		if ($child) {
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/footer',
				'common/header'
			);
		}

		$this->response->setOutput($this->render());

	}

	protected function index() {
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->payment($order_info);
	}
	public function repay() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('payment/yandexmoney/repay', 'order_id='.$this->request->get['order_id'], 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
		$this->load->model('account/order');
		$order_info = $this->model_account_order->getOrder((int) $this->request->get['order_id']);
		if ($order_info){
			$this->payment($order_info, true);
		}else {
			$this->redirect($this->url->link('account/order/info', 'order_id='.$this->request->get['order_id'], 'SSL'));
		}
	}

	public function confirm() {
		$this->language->load('payment/yandexmoney');
		$pay_url = $this->url->link('payment/yandexmoney/repay','order_id='.$this->session->data['order_id'], 'SSL');
		$this->load->model('checkout/order');
		$finish=$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'), '<a href="'.$pay_url.'" class="button">'.$this->language->get('text_repay').'</a>', true);
		$this->cart->clear();
		if (isset($this->request->post['paymentType']) && ($this->request->post['paymentType']=='MP')){
			$this->redirect($this->url->link('information/information', 'information_id='.$this->config->get('yandexmoney_page_mpos'), 'SSL'));
		}
	}
	public function callback() {
    	$ymObj = new YandexMoneyObj();
		$callbackParams = $_POST;

		$ymObj->org_mode = ($this->config->get('yandexmoney_mode') >= 2);
		$ymObj->password = $this->config->get('yandexmoney_password');
		$ymObj->shopid = $this->config->get('yandexmoney_shopid');
		$notify = (bool) $this->config->get('yandexmoney_notify');
		if (isset($callbackParams["orderNumber"]) || isset($callbackParams["label"])){
			$order_id = ($ymObj->org_mode)?$callbackParams["orderNumber"]:$callbackParams["label"];
		}else{ $order_id =0;}

      //$this->log->write(print_r($callbackParams, true));
      //$this->log->write(print_r($ymObj, true));
      //$this->log->write(print_r($order_id, true));

		if ($ymObj->checkSign($callbackParams)){
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);

         //$this->log->write(print_r($order_info, true));
         //$this->log->write(number_format($callbackParams[($ymObj->org_mode)?'orderSumAmount':'withdraw_amount'], 2, '.', ''));
         //$this->log->write(number_format($order_info['total'], 2, '.', ''));

			if ($order_info!=false){
				$comment=($ymObj->org_mode && $callbackParams['paymentType']=="MP" && isset($callbackParams['orderDetails']))?$callbackParams['orderDetails']:'';
				$amount = number_format($callbackParams[($ymObj->org_mode)?'orderSumAmount':'withdraw_amount'], 2, '.', '');

            // confirm call is required before any update call
            $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'),$comment);

            if ((isset($callbackParams['paymentType']) && $callbackParams['paymentType']=="MP") || $amount == number_format($order_info['total'], 2, '.', '')){
					if (isset($callbackParams['action']) && $callbackParams['action'] == 'paymentAviso'){
						$this->model_checkout_order->update($order_id, $this->config->get('yandexmoney_order_status_id'), "Номер транзакции: ".$callbackParams['invoiceId'].". Сумма: ".$callbackParams['orderSumAmount'].' '.$comment, $notify);
					}elseif (isset($callbackParams["label"]) && !$ymObj->org_mode){

						$sender=($callbackParams['sender']!='')?"Номер кошелька Яндекс.Денег: ".$callbackParams['sender'].".":'';

                  //$this->log->write(print_r($order_id, true));
                  //$this->log->write(print_r($this->config->get('yandexmoney_order_status_id'), true));
                  //$this->log->write(print_r($sender." Сумма: ".$callbackParams['amount'].' '.$comment, true));
                  //$this->log->write(print_r($notify, true));

						$this->model_checkout_order->update($order_id, $this->config->get('yandexmoney_order_status_id'), $sender." Сумма: ".$callbackParams['amount'].' '.$comment, $notify);

                  //$this->log->write(print_r($res, true));
					}

					$ymObj->sendCode($callbackParams, "0");
				}else{
					$ymObj->sendCode($callbackParams, "100");
				}
			}elseif (isset($callbackParams['paymentType']) && $callbackParams['paymentType']=="MP"){
				//Заказа нет и пока будем отвечать успехом
				$ymObj->sendCode($callbackParams, "0");
			}else{
				$ymObj->sendCode($callbackParams, "200");
			}
		}else{
			$ymObj->sendCode($callbackParams, "1");
		}
	}
}

Class YandexMoneyObj {
	public $test_mode;//
	public $org_mode; //
	public $epl;		//

	public $shopid;	//
	public $password;	//

	public function getFormUrl(){ //
		$demo = ($this->test_mode)?'https://demomoney.yandex.ru/':'https://money.yandex.ru/';
		return ($this->org_mode)?$demo.'eshop.xml':$demo.'quickpay/confirm.xml';
	}

	public function checkSign($callbackParams){ //
		if ($this->org_mode){
			$string = $callbackParams['action'].';'.$callbackParams['orderSumAmount'].';'.$callbackParams['orderSumCurrencyPaycash'].';'.$callbackParams['orderSumBankPaycash'].';'.$callbackParams['shopId'].';'.$callbackParams['invoiceId'].';'.$callbackParams['customerNumber'].';'.$this->password;
			$md5 = strtoupper(md5($string));
			return (strtoupper($callbackParams['md5'])==$md5);
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

	public function sendCode($callbackParams, $code){ //
		if (!$this->org_mode) return false;
		header("Content-type: text/xml; charset=utf-8");
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<'.$callbackParams['action'].'Response performedDatetime="'.date("c").'" code="'.$code.'" invoiceId="'.$callbackParams['invoiceId'].'" shopId="'.$this->shopid.'"/>';
		echo $xml;
	}
}
?>