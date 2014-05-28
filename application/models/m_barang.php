<?php
class M_barang extends CI_Model {

    var $tblBrg	= 'barang';

    function __construct(){
        parent::__construct();
    }
    
    function getGridBarang($kdBrg,$nmBrg,$start,$limit){
		$sql = "SELECT *
				FROM $this->tblBrg
				WHERE  kode_brg like '$kdBrg%' and nama_brg like '%$nmBrg%'";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getKode($kdBrg){
		$sql	= "SELECT SUBSTRING_INDEX(kode_brg, '/', -1) kode_brg 
					FROM $this->tblBrg 
					WHERE SUBSTRING_INDEX(kode_brg, '/', 2) = '$kdBrg' 
					ORDER BY id_brg DESC LIMIT 1";
		$qSql	= $this->db->query($sql)->row();
		
		if(count($qSql)>0){
			$result = $qSql->kode_brg;
		}else{
			$result = '';
		}
		
		return	$result;
	}
	
	function getGrupKode($kdBrg){
		$sql = "SELECT DISTINCT (SUBSTRING_INDEX(kode_brg, '/', 2)) kode_brg FROM $this->tblBrg";
		$qSql	= $this->db->query($sql)->result_array();
		return $qSql;
	}
	
	function actSimpan($idBrg,$kdBrg,$nmBrg,$partNoBrg,$stokBrg,$stokMinBrg,$stokMaxBrg,$satBrg){
		if($idBrg==''){
			$kdSeqBrg	= $this->getKode($kdBrg);
			if(count($kdSeqBrg)>0){
				$kdSeqBrg	= (int) $kdSeqBrg +1;
				$kdSeqBrg	= str_pad($kdSeqBrg, 4, "0", STR_PAD_LEFT);
			}else{
				$kdSeqBrg	= '0001';
			}
			$kodeBarang	= strtoupper($kdBrg.'/'.$kdSeqBrg);
			$sqlCmd		= "insert into $this->tblBrg values('','$kodeBarang','$nmBrg','$partNoBrg','$satBrg',$stokBrg,$stokMinBrg,$stokMaxBrg)";
		}else{
			$sqlCmd		= "update $this->tblBrg set nama_brg='$nmBrg' ,part_no='$partNoBrg',satuan='$satBrg',stok_min=$stokMinBrg,stok_max=$stokMaxBrg where id_brg=$idBrg";
		}
		
		$qRes	= $this->db->query($sqlCmd);
		$result['message']	= "Barang $nmBrg berhasil disimpan !";
		
		return $result;
	}
	
	
	function actDel($idBrg){
		$delBrg		= "delete from $this->tblBrg where id_brg=$idBrg";
		$qDelBrg	= $this->db->query($delBrg);
		$result['message']	= "Barang berhasil dihapus !";
		return $result;
	}
}