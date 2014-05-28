<?php
class M_rpt_ttb extends CI_Model {

    var $tblRpt	= 'rpt_ttb';
	

    function __construct(){
        parent::__construct();
    }
    
    function getData($dateFrom,$dateTo){
		$sql	= "SELECT * FROM $this->tblRpt WHERE tgl_ttb BETWEEN '$dateFrom' AND '$dateTo'";
		return $this->db->query($sql)->result_array();
	}
}