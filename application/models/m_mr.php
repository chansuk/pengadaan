<?php
class M_mr extends CI_Model {

    var $tblMr	= 'mr';
    var $tblBus	= 'bus';
    var $tblBrg	= 'barang';
    var $tblBrgMr	= 'brg_mr';
	

    function __construct(){
        parent::__construct();
    }
    
    function getNoMr(){
		$sql	= "select SUBSTRING_INDEX(no_mr, '/', -1) no_mr from $this->tblMr order by tgl_mr desc limit 1";
		$result	= $this->db->query($sql)->result_array();
		
		$dateMr	= date('Ym');
		if(count($result)>0){
			$noRes	= (int) $result[0]['no_mr'];
			$noLast	= $noRes+1;
			$noLast	= str_pad($noLast, 3, "0", STR_PAD_LEFT);
			$noMr	= "MR/TEK/$dateMr/$noLast";
		}else{
			$noMr	= "MR/TEK/$dateMr/001";
		}

		return $noMr;
	}
	
	function getBusData($idBus){
		$sql	= "select * from $this->tblMr order by tgl_mr desc limit 1";
		$result	= $this->db->query($sql)->result_array();
	}
	
	function getNmBrg($nmBrg){
		$sql	= "select * from $this->tblBrg where nama_brg like '$nmBrg%' limit 10";
		$result	= $this->db->query($sql)->result_array();
		return $result;
	}
	
	function insertMr($noBody,$noMr,$brgData){
		$dateNow	= date("Y-m-d H:i:s");
		$allBarang	= explode(';',$brgData);
		
		$sqlMr		= "insert into $this->tblMr(no_mr,tgl_mr,status_mr,id_bag,id_bus) values('$noMr','$dateNow','Request',2,'$noBody')";
		$qMr	= $this->db->query($sqlMr);
		
		$sqlIdMr	= "select id_mr from $this->tblMr where no_mr='$noMr'";
		$qIdMr		= $this->db->query($sqlIdMr)->row();
		
		$barang		= '';
		if(count($allBarang)>0){
			$totData = count($allBarang)-1;
		}else{
			$totData = 0;
		}
		
		for($i=0;$i<$totData;$i++){
			$dataDet	= explode('#',$allBarang[$i]);
			$idBrg		= $dataDet[0];
			$jmlBrg		= $dataDet[1];
			
			$sqlBrgMr	= "insert into brg_mr(id_mr,id_brg,status_brg_mr,jml_brg_mr) values($qIdMr->id_mr,$idBrg,0,$jmlBrg)";
			$qBrgMr		= $this->db->query($sqlBrgMr);
		}
		
		$result['status'] = true;
		$result['message'] = "PR No : $noMr berhasil dikirim!";
		return $result;
	}
	
	function getGridMrSrc($noMr,$whereDate,$start,$limit){
		$sql = "SELECT a.*,b.koridor_bus 
				FROM $this->tblMr a,$this->tblBus b
				WHERE a.id_bus=b.id_bus  and a.no_mr like '$noMr%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getGridInfoMr($noMr){
		$sql = "SELECT b.*,
					case c.status_brg_mr 
						when 0 then 'Pending'
						else 'Approve'
					end status_brg_mr_desc,c.status_brg_mr,c.jml_brg_mr,a.id_mr
				FROM $this->tblBrg b,$this->tblBrgMr c,$this->tblMr a
				WHERE a.id_mr=c.id_mr AND b.id_brg=c.id_brg and a.no_mr='$noMr'";
		
		$result['rows'] 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
	
	function getMr($idMr){
		$sql = "SELECT a.*,b.koridor_bus,b.id_bus,DATE_FORMAT(a.tgl_mr, '%d-%m-%Y') tanggal
				FROM $this->tblMr a,$this->tblBus b
				WHERE a.id_bus=b.id_bus and a.id_mr='$idMr'";
		
		$result 	= $this->db->query($sql)->row();
		
		return $result;
	}
	
	function getBrgMr($idMr){
		$sql = "SELECT c.*,b.jml_brg_mr
				FROM $this->tblMr a,$this->tblBrgMr b,$this->tblBrg c
				WHERE a.id_mr=b.id_mr AND b.id_brg=c.id_brg and a.id_mr=$idMr";
		
		$result 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
}