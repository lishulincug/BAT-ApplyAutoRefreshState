<?php 
class API{
	
	private $Company = null;
	private $currentState = '';
	private $interval = 0;
	
	public function __construct(){
		$company = C('company');
		if(!$company || !class_exists($companyClass = $company.'Company')){
			echo 'Company initialize failed!';
		}
		$this->Company = new $companyClass();
		$this->currentState = C('currentState');
		$this->interval = C('interval');
	}
	
	/**
	 * 应聘状态JSON API
	 */
	public function refreshState(){
		$url = $this->Company->getPageURL();
		$cookie = $this->Company->getLoginCookie();
		$content = curl_request(compact('url', 'cookie'));
		$state = $content ? $this->Company->getApplyState($content) : false;
// 		$state = '测试更新';
		show_json($state);
	}
	
	/**
	 * 应聘状态JSON API
	 */
	public function initialState(){
		show_json(array('company'=>$this->Company->getName(), 'now'=>$this->currentState, 'interval'=>$this->interval));
	}
	
	/**
	 * 通知接口
	 */
	public function notify(){
		$smsConfig = C('SMS');
		$message = clean_xss($_GET['message']);
		return send_sms($smsConfig['account'], $smsConfig['password'], $smsConfig['mobile'], $message);
	}
}
