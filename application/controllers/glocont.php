<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Glocont extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		
	}
	
	public function getBusData(){
		$idBus 	= $this->input->post('idBus');
		$dataBus= $this->_getBus($idBus);
		echo json_encode($dataBus);
	}
	
	public function _getBus($idBus){
		$sql	= "select * from bus where id_bus='$idBus'";
		$data	= $this->db->query($sql)->result_array();
		$result['bus'] = $data;
		return $result;
	}
	
	function getBrgData(){
		$idBrg 	= $this->input->post('idBrg');
		
		$sql	= "select * from barang where id_brg=$idBrg";
		$data	= $this->db->query($sql)->result_array();
		$result['brg'] = $data;
		echo json_encode($result);
	}
	
	
}
