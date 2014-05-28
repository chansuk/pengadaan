<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bus extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_bus');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		
		
		$this->load->view('main/v_head');
		$this->load->view('v_bus',$data);
		
	}
	
	function getGridBus(){
		$page 	= trim($this->input->get_post('page', true));
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$idBus	= $this->input->get('idBus',true);
		$nopol	= $this->input->get('nopol',true);
		
		
		$result	= $this->m_bus->getGridBus($idBus,$nopol,$start,$limit);
		echo json_encode($result);
	}
	
	function getIdBus(){
		$result	= $this->m_bus->getIdBus();
		echo json_encode($result);
	}
	
	function actSimpan(){
		$idBus 		= trim($this->input->post('idBus', true));
		$kdBus 		= trim($this->input->post('kdBus', true));
		$nopol 		= trim($this->input->post('nopol', true));
		$koridor 	= trim($this->input->post('koridor', true));
		
		$result	= $this->m_bus->actSimpan($idBus,$nopol,$koridor,$kdBus);
		echo json_encode($result);
	}
	
	
	function actDel(){
		$idBus 	= trim($this->input->post('idBus', true));
		$result	= $this->m_bus->actDel($idBus);
		echo json_encode($result);
	}
}