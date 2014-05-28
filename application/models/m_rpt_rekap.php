<?php
class M_rpt_rekap extends CI_Model {

    var $tblBrg		= 'barang';
    var $tblBrgMr	= 'brg_mr';
    var $tblMr		= 'mr';
	

    function __construct(){
        parent::__construct();
    }
    
    function getData($dateFrom,$dateTo){
		$sql	= "SELECT * FROM(SELECT a.kode_brg,a.nama_brg,a.satuan,SUM(b.jml_brg_mr) jml_brg_mr
					FROM $this->tblBrg a,$this->tblBrgMr b,$this->tblMr c
					WHERE a.id_brg=b.id_brg AND c.id_mr=b.id_mr AND DATE_FORMAT(c.tgl_mr,'%d-%m-%Y') BETWEEN '$dateFrom' AND '$dateTo'
					GROUP BY a.kode_brg) a";
		return $this->db->query($sql)->result_array();
	}
}