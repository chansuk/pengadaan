<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sph extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_sph');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['getSup']	= $this->getSup();
		$this->load->view('main/v_head');
		$this->load->view('v_sph',$data);
		
	}
	
	
	function getGridPr(){
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$date1	= $this->input->get('date1',true);
		$date2	= $this->input->get('date2',true);
		$noPr	= $this->input->get('noPr',true);
		
		$whereDate = "";
		if ($date1 <>"" && $date2 <> "") {
			$whereDate = " AND tgl_pr BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";
		};		
		if ($date1 <>"" && $date2 == "") {
			$whereDate = " AND tgl_pr > '$date1 00:00:00'";
		};			
		if ($date1 =="" && $date2 <> "" ) {
			$whereDate = " AND tgl_pr < '$date2 23:59:59'";
		};
		
		$result	= $this->m_sph->getGridPr($noPr,$whereDate,$start,$limit);
		echo json_encode($result);
	}
	
	function getNoSph(){
		$noSph	= $this->m_sph->getNoSph();
		$result['no_sph'] = $noSph;
		echo json_encode($result);
	}
	
	function getGridInfoPr(){
		$noPr	= $this->input->get('noPr',true);
		
		$result	= $this->m_sph->getGridInfoPr($noPr);
		echo json_encode($result);
	}
	
	function getGridSphDet(){
		$noPr	= $this->input->get('noPr',true);
		
		$result	= $this->m_sph->getGridSphDet($noPr);
		echo json_encode($result);
	}
	
	function getSphDetBrg(){
		$idSph	= $this->input->get('idSph',true);
		
		$result	= $this->m_sph->getSphDetBrg($idSph);
		echo json_encode($result);
	}
	
	function getSup(){
		$result	= $this->m_sph->getSup();
		return $result;
	}
	
	function actSimpan(){
		$noSph		= strtoupper(trim($this->input->post('noSph')));
		$idSup		= $this->input->post('idSup');
		$idPr		= $this->input->post('idPr');
		$noSphRef	= $this->input->post('noSphRef');
		$brgData	= $this->input->post('brgData');

		$insertSph	= $this->m_sph->insertSph($noSph,$idSup,$idPr,$brgData,$noSphRef);
		echo json_encode($insertSph);
	}
	
	function appSph(){
		$idSph	= $this->input->post('idSph');
		$appSph	= $this->m_sph->appSph($idSph);
		echo json_encode($appSph);
	}
}