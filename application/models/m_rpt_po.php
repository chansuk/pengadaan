<?php
class M_rpt_po extends CI_Model {

    var $tblRpt	= 'rpt_po';
	

    function __construct(){
        parent::__construct();
    }
    
    function getData($dateFrom,$dateTo){
		$sql	= "SELECT * FROM $this->tblRpt WHERE tgl_po BETWEEN '$dateFrom' AND '$dateTo'";
		return $this->db->query($sql)->result_array();
	}
}