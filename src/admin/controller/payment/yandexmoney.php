<?php 
class ControllerPaymentYandexMoney extends Controller {
	private $error = array();
	private $ya_version= '1.7.0.1';
	private function sendStatistics(){
		$this->language->load('payment/yandexmoney');
		$this->load->model('setting/setting');
		$setting=$this->model_setting_setting->getSetting('yandexmoney');
		$array = array(
			'url' => $this->config->get('config_secure') ? HTTP_CATALOG : HTTPS_CATALOG,
			'cms' => 'opencart',
			'version' => VERSION,
			'ver_mod' => $this->ya_version,
			'yacms' => false,
			'email' => $this->config->get('config_email'),
			'shopid' => $setting['yandexmoney_shopid'],
			'settings' => array(
				'kassa' => (bool) ($setting['yandexmoney_mode']>=2)?true:false,
				'kassa_epl' => (bool) ($setting['yandexmoney_mode']==3)?true:false,
				'p2p' => (bool) ($setting['yandexmoney_mode']<2)?true:false
			)
		);

		$array_crypt = base64_encode(serialize($array));

		$url = 'https://statcms.yamoney.ru/v2/';
		$curlOpt = array(
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLINFO_HEADER_OUT => true,
			CURLOPT_POST => true,
			CURLOPT_FRESH_CONNECT => TRUE,
		);

		$curlOpt[CURLOPT_HTTPHEADER] = array('Content-Type: application/x-www-form-urlencoded');
		$curlOpt[CURLOPT_POSTFIELDS] = http_build_query(array('data' => $array_crypt, 'lbl'=>1));

		$curl = curl_init($url);
		curl_setopt_array($curl, $curlOpt);
		$rbody = curl_exec($curl);
		$errno = curl_errno($curl);
		$error = curl_error($curl);
		$rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		$json=json_decode($rbody);
		if ($rcode==200 && isset($json->new_version)){
			return sprintf($this->language->get('text_need_update'),$json->new_version);
		}else{
			return false;
		}
	}
	public function install() {
		$this->log->write("install yandexmoney");
	}
	
	public function uninstall() {
		$this->log->write("uninstall yandexmoney");
	}
	public function index() {
		$this->language->load('payment/yandexmoney');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->data['attention'] = '';	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('yandexmoney', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$updater = $this->sendStatistics();
			if ($updater) $this->data['attention'] = $updater;	else $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		
		$url = new Url(HTTP_CATALOG, $this->config->get('config_secure') ? HTTP_CATALOG : HTTPS_CATALOG);
		$this->data['callback_url'] = str_replace("http:", "https:",$url->link('payment/yandexmoney/callback', '', 'SSL'));
		$this->data['shopSuccessURL'] = $url->link('checkout/success', '', 'SSL');
		$this->data['shopFailURL'] = $url->link('checkout/failure', '', 'SSL');
		$this->data['yandexmoney_version'] = $this->ya_version;
		
		$list_language=array('yandexmoney_license','heading_title','text_payment','text_yes','text_no','text_disabled','text_enabled','text_all_zones','text_welcome1','text_welcome2','text_params','text_param_name','text_param_value','text_aviso1','text_aviso2','title_default','entry_version','entry_license','entry_testmode','entry_modes','entry_mode1','entry_mode2','entry_mode3','entry_paymode','entry_methods','entry_method_ym','entry_method_cards','entry_method_cash','entry_method_mobile','entry_method_wm','entry_method_ab','entry_method_sb','entry_method_ma','entry_method_pb','entry_method_qw','entry_method_qp','entry_method_mp','entry_default_method','entry_page_mpos','entry_page_success','entry_page_fail','entry_shopid','entry_scid','entry_title','entry_total','entry_total2','entry_password','entry_account','entry_order_status','entry_notify','entry_geo_zone','entry_status','entry_sort_order','button_save','button_cancel');
		foreach ($list_language as $item) $this->data[$item] = $this->language->get($item);

		$list_errors=array('warning','account','methods','account','password','shopid','scid','title');
		foreach ($list_errors as $e_item) $this->data['error_'.$e_item] = (isset($this->error[$e_item]))?$this->error[$e_item]:'';
				
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
		foreach (array('PC'=>'ym', 'AC'=>'cards', 'GP'=>'cash', 'MC'=>'mobile', 'WM'=>'wm', 'SB'=>'sb', 'AB'=>'ab', 'PB'=>'pb', 'MA'=>'ma', 'QW'=>'qw', 'QP'=>'qp', 'MP'=>'mp') as $name => $value){
			if ($this->config->get('yandexmoney_mode')>=2 || in_array($name, array('AC','PC'))) $this->data['default_methods'][$name] = $this->language->get('entry_method_'.$value);
		}
		$this->data['action'] = $this->url->link('payment/yandexmoney', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		$list_setting=array('testmode','account','method_ym','method_cards','method_cash','method_mobile','method_wm','method_ab','method_sb','method_ma','method_pb','method_qw','method_qp','method_mp', 'default_method', 'page_mpos','page_success','page_fail','mode','password','shopid', 'scid', 'title', 'total', 'order_status_id', 'notify', 'geo_zone_id', 'status', 'sort_order');

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$this->load->model('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$this->load->model('catalog/information');
		$this->data['pages_mpos'] = $this->model_catalog_information->getInformations();
		
		foreach ($list_setting as $s_item) $this->data['yandexmoney_'.$s_item]=(isset($this->request->post['yandexmoney_'.$s_item]))?$this->request->post['yandexmoney_'.$s_item]:$this->config->get('yandexmoney_'.$s_item);

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
		
		if (!$this->request->post['yandexmoney_shopid'] && $this->request->post['yandexmoney_mode']>=2) {
			$this->error['shopid'] = $this->language->get('error_shopid');
		}
		if (!$this->request->post['yandexmoney_scid'] && $this->request->post['yandexmoney_mode']>=2) {
			$this->error['scid'] = $this->language->get('error_scid');
		}
		if (!$this->request->post['yandexmoney_title'] && $this->request->post['yandexmoney_mode']>=2) {
			$this->error['title'] = $this->language->get('error_title');
		}
		
		$list_methods=array('ym','cards','cash','mobile','wm','sb','ab','pb','ma','qp','qw','mp');
		$bool_methods=false;
		foreach ($list_methods as $m_item) if(isset($this->request->post['yandexmoney_method_'.$m_item])) $bool_methods = true;
		if (!$bool_methods && $this->request->post['yandexmoney_mode']==2) $this->error['methods'] = $this->language->get('error_methods');
		if (!$this->error) return true; else return false;
	}
}
?>