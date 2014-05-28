<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barang extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_barang');
		//$this->load->model('m_user');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		
		
		$this->load->view('main/v_head');
		$this->load->view('v_barang',$data);
		
	}
	
	function getGridBarang(){
		$page 	= trim($this->input->get_post('page', true));
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$kdBrg	= $this->input->get('kdBrg',true);
		$nmBrg	= $this->input->get('nmBrg',true);
		
		
		$result	= $this->m_barang->getGridBarang($kdBrg,$nmBrg,$start,$limit);
		echo json_encode($result);
	}
	
	function actSimpan(){
		$idBrg 		= trim($this->input->post('idBrg', true));
		$kdBrg 		= trim($this->input->post('kdBrg', true));
		$nmBrg 		= trim($this->input->post('nmBrg', true));
		$partNoBrg	= trim($this->input->post('partNoBrg', true));
		$satBrg		= trim($this->input->post('satBrg', true));
		$stokBrg 	= trim($this->input->post('stokBrg', true));
		$stokMinBrg = trim($this->input->post('stokMinBrg', true));
		$stokMaxBrg = trim($this->input->post('stokMaxBrg', true));
		
		$result	= $this->m_barang->actSimpan($idBrg,$kdBrg,$nmBrg,$partNoBrg,$stokBrg,$stokMinBrg,$stokMaxBrg,$satBrg);
		echo json_encode($result);
	}
	
	function getGrupKode(){
		$kdBrg	= trim($this->input->get('query'));
		
		$getGrupKode	= $this->m_barang->getGrupKode($kdBrg);
		$result	= $getGrupKode;
		echo json_encode($result);
	}
	
	function actDel(){
		$idBrg 	= trim($this->input->post('idBrg', true));
		$result	= $this->m_barang->actDel($idBrg);
		echo json_encode($result);
	}
}