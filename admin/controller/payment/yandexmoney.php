<?php 
class ControllerPaymentYandexMoney extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/yandexmoney');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('yandexmoney', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		
		$url = new Url(HTTP_CATALOG, $this->config->get('config_secure') ? HTTP_CATALOG : HTTPS_CATALOG);
		$this->data['check_url'] = $url->link('payment/yandexmoney/callback', 'check=1', 'SSL');
		$this->data['callback_url'] = $url->link('payment/yandexmoney/callback', '', 'SSL');
		$this->data['shopSuccessURL'] = $url->link('checkout/success', '', 'SSL');
		$this->data['shopFailURL'] = $url->link('checkout/failure', '', 'SSL');
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_payment'] = $this->language->get('text_pay');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');

		$this->data['text_welcome1'] = $this->language->get('text_welcome1');
		$this->data['text_welcome2'] = $this->language->get('text_welcome2');
		$this->data['text_params'] = $this->language->get('text_params');
		$this->data['text_param_name'] = $this->language->get('text_param_name');
		$this->data['text_param_value'] = $this->language->get('text_param_value');
		$this->data['text_aviso1'] = $this->language->get('text_aviso1');
		$this->data['text_aviso2'] = $this->language->get('text_aviso2');
		
		$this->data['entry_testmode'] = $this->language->get('entry_testmode');
		$this->data['entry_modes'] = $this->language->get('entry_modes');
		$this->data['entry_mode1'] = $this->language->get('entry_mode1');
		$this->data['entry_mode2'] = $this->language->get('entry_mode2');
		$this->data['entry_methods'] = $this->language->get('entry_methods');
		$this->data['entry_method_ym'] = $this->language->get('entry_method_ym');
		$this->data['entry_method_cards'] = $this->language->get('entry_method_cards');
		$this->data['entry_method_cash'] = $this->language->get('entry_method_cash');
		$this->data['entry_method_mobile'] = $this->language->get('entry_method_mobile');
		$this->data['entry_method_wm'] = $this->language->get('entry_method_wm');
		$this->data['entry_method_ab'] = $this->language->get('entry_method_ab');
		$this->data['entry_method_sb'] = $this->language->get('entry_method_sb');
		$this->data['entry_method_ma'] = $this->language->get('entry_method_ma');
		$this->data['entry_method_pb'] = $this->language->get('entry_method_pb');

		$this->data['entry_shopid'] = $this->language->get('entry_shopid');
		$this->data['entry_scid'] = $this->language->get('entry_scid');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_total2'] = $this->language->get('entry_total2');

		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_account'] = $this->language->get('entry_account');

		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['account'])) { 
			$this->data['error_account'] = $this->error['account'];
		} else {
			$this->data['error_account'] = '';
		}
		if (isset($this->error['methods'])) { 
			$this->data['error_methods'] = $this->error['methods'];
		} else {
			$this->data['error_methods'] = '';
		}
		if (isset($this->error['account'])) { 
			$this->data['error_account'] = $this->error['account'];
		} else {
			$this->data['error_account'] = '';
		}
		if (isset($this->error['password'])) { 
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		if (isset($this->error['shopid'])) { 
			$this->data['error_shopid'] = $this->error['shopid'];
		} else {
			$this->data['error_shopid'] = '';
		}
		if (isset($this->error['scid'])) { 
			$this->data['error_scid'] = $this->error['scid'];
		} else {
			$this->data['error_scid'] = '';
		}
		
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_pay'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/yandexmoney', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/yandexmoney', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['yandexmoney_testmode'])) {
			$this->data['yandexmoney_testmode'] = $this->request->post['yandexmoney_testmode'];
		} else {
			$this->data['yandexmoney_testmode'] = $this->config->get('yandexmoney_testmode');
		}
	
		if (isset($this->request->post['yandexmoney_account'])) {
			$this->data['yandexmoney_account'] = $this->request->post['yandexmoney_account'];
		} else {
			$this->data['yandexmoney_account'] = $this->config->get('yandexmoney_account');
		}

		if (isset($this->request->post['yandexmoney_method_ym'])) {
			$this->data['yandexmoney_method_ym'] = $this->request->post['yandexmoney_method_ym'];
		} else {
			$this->data['yandexmoney_method_ym'] = $this->config->get('yandexmoney_method_ym');
		}

		if (isset($this->request->post['yandexmoney_method_cards'])) {
			$this->data['yandexmoney_method_cards'] = $this->request->post['yandexmoney_method_cards'];
		} else {
			$this->data['yandexmoney_method_cards'] = $this->config->get('yandexmoney_method_cards');
		}
		
		if (isset($this->request->post['yandexmoney_method_cash'])) {
			$this->data['yandexmoney_method_cash'] = $this->request->post['yandexmoney_method_cash'];
		} else {
			$this->data['yandexmoney_method_cash'] = $this->config->get('yandexmoney_method_cash');
		}

		if (isset($this->request->post['yandexmoney_method_mobile'])) {
			$this->data['yandexmoney_method_mobile'] = $this->request->post['yandexmoney_method_mobile'];
		} else {
			$this->data['yandexmoney_method_mobile'] = $this->config->get('yandexmoney_method_mobile');
		}

		if (isset($this->request->post['yandexmoney_method_wm'])) {
			$this->data['yandexmoney_method_wm'] = $this->request->post['yandexmoney_method_wm'];
		} else {
			$this->data['yandexmoney_method_wm'] = $this->config->get('yandexmoney_method_wm');
		}

		if (isset($this->request->post['yandexmoney_method_ab'])) {
			$this->data['yandexmoney_method_ab'] = $this->request->post['yandexmoney_method_ab'];
		} else {
			$this->data['yandexmoney_method_ab'] = $this->config->get('yandexmoney_method_ab');
		}

		if (isset($this->request->post['yandexmoney_method_sb'])) {
			$this->data['yandexmoney_method_sb'] = $this->request->post['yandexmoney_method_sb'];
		} else {
			$this->data['yandexmoney_method_sb'] = $this->config->get('yandexmoney_method_sb');
		}
		
		if (isset($this->request->post['yandexmoney_method_ma'])) {
			$this->data['yandexmoney_method_ma'] = $this->request->post['yandexmoney_method_sb'];
		} else {
			$this->data['yandexmoney_method_ma'] = $this->config->get('yandexmoney_method_sb');
		}
		
		if (isset($this->request->post['yandexmoney_method_sb'])) {
			$this->data['yandexmoney_method_pb'] = $this->request->post['yandexmoney_method_sb'];
		} else {
			$this->data['yandexmoney_method_pb'] = $this->config->get('yandexmoney_method_sb');
		}

		if (isset($this->request->post['yandexmoney_mode'])) {
			$this->data['yandexmoney_mode'] = $this->request->post['yandexmoney_mode'];
		} else {
			$this->data['yandexmoney_mode'] = $this->config->get('yandexmoney_mode');
		}

		if (isset($this->request->post['yandexmoney_password'])) {
			$this->data['yandexmoney_password'] = $this->request->post['yandexmoney_password'];
		} else {
			$this->data['yandexmoney_password'] = $this->config->get('yandexmoney_password');
		}

		if (isset($this->request->post['yandexmoney_shopid'])) {
			$this->data['yandexmoney_shopid'] = $this->request->post['yandexmoney_shopid'];
		} else {
			$this->data['yandexmoney_shopid'] = $this->config->get('yandexmoney_shopid');
		}
		
		
		if (isset($this->request->post['yandexmoney_scid'])) {
			$this->data['yandexmoney_scid'] = $this->request->post['yandexmoney_scid'];
		} else {
			$this->data['yandexmoney_scid'] = $this->config->get('yandexmoney_scid');
		}
		
		if (isset($this->request->post['yandexmoney_total'])) {
			$this->data['yandexmoney_total'] = $this->request->post['yandexmoney_total'];
		} else {
			$this->data['yandexmoney_total'] = $this->config->get('yandexmoney_total'); 
		} 
				
		if (isset($this->request->post['yandexmoney_order_status_id'])) {
			$this->data['yandexmoney_order_status_id'] = $this->request->post['yandexmoney_order_status_id'];
		} else {
			$this->data['yandexmoney_order_status_id'] = $this->config->get('yandexmoney_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['yandexmoney_geo_zone_id'])) {
			$this->data['yandexmoney_geo_zone_id'] = $this->request->post['yandexmoney_geo_zone_id'];
		} else {
			$this->data['yandexmoney_geo_zone_id'] = $this->config->get('yandexmoney_geo_zone_id'); 
		} 		
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['yandexmoney_status'])) {
			$this->data['yandexmoney_status'] = $this->request->post['yandexmoney_status'];
		} else {
			$this->data['yandexmoney_status'] = $this->config->get('yandexmoney_status');
		}
		
		if (isset($this->request->post['yandexmoney_sort_order'])) {
			$this->data['yandexmoney_sort_order'] = $this->request->post['yandexmoney_sort_order'];
		} else {
			$this->data['yandexmoney_sort_order'] = $this->config->get('yandexmoney_sort_order');
		}

		$this->template = 'payment/yandexmoney.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/yandexmoney')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['yandexmoney_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}
		if (!$this->request->post['yandexmoney_account'] && $this->request->post['yandexmoney_mode']==1) {
			$this->error['account'] = $this->language->get('error_account');
		}
		
		if (!$this->request->post['yandexmoney_shopid'] && $this->request->post['yandexmoney_mode']==2) {
			$this->error['shopid'] = $this->language->get('error_shopid');
		}
		if (!$this->request->post['yandexmoney_scid'] && $this->request->post['yandexmoney_mode']==2) {
			$this->error['scid'] = $this->language->get('error_scid');
		}
		

		if (!isset($this->request->post['yandexmoney_method_ym']) && !isset($this->request->post['yandexmoney_method_cards']) && !isset($this->request->post['yandexmoney_method_cash']) and !isset($this->request->post['yandexmoney_method_mobile']) and !isset($this->request->post['yandexmoney_method_wm'])  ) {
			$this->error['methods'] = $this->language->get('error_methods');
		}

		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>