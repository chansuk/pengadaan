<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_login');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			$this->load->view('main/v_head');
			$this->load->view('main/v_login');
		}else{
			redirect('','location');
		}
	}
	
	function userLogin(){
		$username 	= trim($this->input->post('username'));
		$password  	= md5(trim($this->input->post('password')));
		$validLog	= $this->m_login->loginValidate($username,$password);
		if( $username && $password && $validLog) {
			$result['status'] = true;
		}else{
			$result['status'] = false;
		}
		
		echo json_encode($result);
	}
	
	public function logout(){
		$this->auth_user->_unsetUserDat();
		$this->index();
	}
}