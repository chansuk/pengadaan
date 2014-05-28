<?php
class M_rpt_pr extends CI_Model {

    var $tblRpt	= 'rpt_pr';
	

    function __construct(){
        parent::__construct();
    }
    
    function getData($dateFrom,$dateTo){
		$sql	= "SELECT * FROM $this->tblRpt WHERE tgl_pr BETWEEN '$dateFrom' AND '$dateTo'";
		return $this->db->query($sql)->result_array();
	}
}