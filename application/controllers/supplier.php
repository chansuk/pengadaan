<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_supplier');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		
		
		$this->load->view('main/v_head');
		$this->load->view('v_supplier',$data);
		
	}
	
	function getGridSup(){
		$page 	= trim($this->input->get_post('page', true));
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$nmSup	= $this->input->get('nmSup',true);
		
		
		$result	= $this->m_supplier->getGridSup($nmSup,$start,$limit);
		echo json_encode($result);
	}
	
	
	function actSimpan(){
		$idSup 		= trim($this->input->post('idSup', true));
		$nmSup 		= trim($this->input->post('nmSup', true));
		$almSup 	= trim($this->input->post('almSup', true));
		$tlpSup 	= trim($this->input->post('tlpSup', true));
		
		$result	= $this->m_supplier->actSimpan($idSup,$nmSup,$almSup,$tlpSup);
		echo json_encode($result);
	}
	
	
	function actDel(){
		$idSup 	= trim($this->input->post('idSup', true));
		$result	= $this->m_supplier->actDel($idSup);
		echo json_encode($result);
	}
}