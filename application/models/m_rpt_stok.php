<?php
class M_rpt_stok extends CI_Model {

    var $tblRpt	= 'barang';
	

    function __construct(){
        parent::__construct();
    }
    
    function getData(){
		$sql	= "SELECT * FROM $this->tblRpt order by stok asc";
		return $this->db->query($sql)->result_array();
	}
}