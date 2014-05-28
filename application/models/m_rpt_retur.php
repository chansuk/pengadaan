<?php
class M_rpt_retur extends CI_Model {

    var $tblRpt	= 'rpt_retur';
	

    function __construct(){
        parent::__construct();
    }
    
    function getData($dateFrom,$dateTo){
		$sql	= "SELECT * FROM $this->tblRpt WHERE tgl_ret BETWEEN '$dateFrom' AND '$dateTo'";
		return $this->db->query($sql)->result_array();
	}
}