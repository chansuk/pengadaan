<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_user');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		$data['getBagian']	= $this->getBagian();
		$data['getStatus']	= $this->getStatus();
		
		$this->load->view('main/v_head');
		$this->load->view('v_user',$data);
		
	}
	
	function getGridUser(){
		$page 	= trim($this->input->get_post('page', true));
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$user	= $this->input->get('user',true);
		
		
		$result	= $this->m_user->getGridUser($user,$start,$limit);
		echo json_encode($result);
	}
	
	function getBagian(){
		$result	= $this->m_user->getBagian();
		return json_encode($result);
	}
	
	function getStatus(){
		$result[0]['status']	= 'Aktif';
		$result[0]['statusid']	= 1;
		$result[1]['status']	= 'Tidak Aktif';
		$result[1]['statusid']	= 0;
		return json_encode($result);
	}
	
	function actSimpan(){
		$userName 	= trim($this->input->post('userName', true));
		$userPass 	= trim($this->input->post('userPass', true));
		$userDesc 	= trim($this->input->post('userDesc', true));
		$userJenkel	= trim($this->input->post('userJenkel', true));
		$userTelp 	= trim($this->input->post('userTelp', true));
		$userAlm 	= trim($this->input->post('userAlm', true));
		$userBag 	= trim($this->input->post('userBag', true));
		$userId 	= trim($this->input->post('userId', true));
		$statEnt 	= trim($this->input->post('statEnt', true));
		
		$result	= $this->m_user->actSimpan($userName,$userPass,$userDesc,$userJenkel,$userTelp,$userAlm,$userBag,$userId,$statEnt);
		echo json_encode($result);
	}
	
	function actDel(){
		$idUsr 	= trim($this->input->post('idUsr', true));
		$result	= $this->m_user->actDel($idUsr);
		echo json_encode($result);
	}
}