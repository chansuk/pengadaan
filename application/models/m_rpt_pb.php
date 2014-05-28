<?php
class M_rpt_pb extends CI_Model {

    var $tblRpt	= 'rpt_pb';
	

    function __construct(){
        parent::__construct();
    }
    
    function getData($dateFrom,$dateTo){
		$sql	= "SELECT * FROM $this->tblRpt WHERE tgl_fpb BETWEEN '$dateFrom' AND '$dateTo'";
		return $this->db->query($sql)->result_array();
	}
}