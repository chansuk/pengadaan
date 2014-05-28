<?php
class M_supplier extends CI_Model {

    var $tblSup	= 'supplier';

    function __construct(){
        parent::__construct();
    }
    
    function getGridSup($nmSup,$start,$limit){
		$sql = "SELECT *
				FROM $this->tblSup
				WHERE  nama_sup like '%$nmSup%' ";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function actSimpan($idSup,$nmSup,$almSup,$tlpSup){
		if($idSup==''){
			$sqlCmd		= "insert into $this->tblSup values('','$nmSup','$almSup','$tlpSup')";
		}else{
			$sqlCmd		= "update $this->tblSup set nama_sup='$nmSup' ,alamat_sup='$almSup' ,telpon_sup='$tlpSup' where id_sup='$idSup'";
		}
		
		$qRes	= $this->db->query($sqlCmd);
		$result['message']	= "Supplier $nmSup berhasil disimpan !";
		
		return $result;
	}
	
	
	function actDel($idSup){
		$delBrg		= "delete from $this->tblSup where id_sup='$idSup'";
		$qDelBrg	= $this->db->query($delBrg);
		$result['message']	= "Supplier berhasil dihapus !";
		return $result;
	}
}