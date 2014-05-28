<?php
class M_bus extends CI_Model {

    var $tblBus	= 'bus';

    function __construct(){
        parent::__construct();
    }
    
    function getGridBus($idBus,$nopol,$start,$limit){
		$sql = "SELECT *
				FROM $this->tblBus
				WHERE  id_bus like '$idBus%' and nopol_bus like '$nopol%'";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getIdBus(){
		$sql	= "SELECT SUBSTRING(id_BUS, -3) id_bus FROM $this->tblBus ORDER BY id_bus DESC limit 1";
		$qSql	= $this->db->query($sql)->row();
		if(count($qSql)>0){
			$idBus	= (int)$qSql->id_bus +1;
			$idBus	= str_pad($idBus, 3, "0", STR_PAD_LEFT);
		}else{
			$idBus	= '001';
		}
		
		return 'TB'.$idBus;
	}
	
	function actSimpan($idBus,$nopol,$koridor,$kdBus){
		if($kdBus==''){
			$sqlCmd		= "insert into $this->tblBus values('$idBus','$nopol','$koridor')";
		}else{
			$sqlCmd		= "update $this->tblBus set nopol_bus='$nopol' ,koridor_bus='$koridor' where id_bus='$idBus'";
		}
		
		$qRes	= $this->db->query($sqlCmd);
		$result['message']	= "Bus $idBus berhasil disimpan !";
		
		return $result;
	}
	
	
	function actDel($idBus){
		$delBrg		= "delete from $this->tblBus where id_bus='$idBus'";
		$qDelBrg	= $this->db->query($delBrg);
		$result['message']	= "Bus berhasil dihapus !";
		return $result;
	}
}