<?php
class M_pr extends CI_Model {

    var $tblMr	= 'mr';
    var $tblPr	= 'pr';
    var $tblFpb	= 'fpb';
    var $tblBrg	= 'barang';
    var $tblBrgFpb	= 'brg_fpb';
	

    function __construct(){
        parent::__construct();
    }
    
    function getNoPr(){
		$sql	= "select SUBSTRING_INDEX(no_pr, '/', -1) no_pr from $this->tblPr order by tgl_pr desc limit 1";
		$result	= $this->db->query($sql)->result_array();
		
		$datePr	= date('Ym');
		if(count($result)>0){
			$noRes	= (int) $result[0]['no_pr'];
			$noLast	= $noRes+1;
			$noLast	= str_pad($noLast, 3, "0", STR_PAD_LEFT);
			$noPr	= "PR/LOG/$datePr/$noLast";
		}else{
			$noPr	= "PR/LOG/$datePr/001";
		}

		return $noPr;
	}
	
	function getGridFpb($date1,$date2,$noFpb,$whereDate,$page,$start,$limit){
		$sql = "SELECT * 
				FROM $this->tblFpb
				WHERE no_fpb like '$noFpb%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getGridInfoFpb($noFpb){
		
		$sql = "SELECT b.*,c.jml_brg_fpb,a.id_fpb
				FROM $this->tblFpb a,$this->tblBrg b,$this->tblBrgFpb c
				WHERE a.id_fpb=c.id_fpb AND b.id_brg=c.id_brg AND a.no_fpb='$noFpb'";
		
		$result['rows'] 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
	
	function insertPr($noPr,$idFpb){
		$dateNow	= date("Y-m-d H:i:s");
		
		$sqlPr		= "insert into $this->tblPr(no_pr,pool_pr,tgl_pr,status_pr,id_fpb) values('$noPr','Perintis','$dateNow','Request',$idFpb)";
		$qPr	= $this->db->query($sqlPr);
		
		$sqlUpdFpb	= "update $this->tblFpb set status_fpb='Process' where id_fpb='$idFpb'";
		$qUpdFpb	= $this->db->query($sqlUpdFpb);
		
		$result['status'] = true;
		$result['message'] = "Purchase Request (PR) No $noPr berhasil dikirim!";
		return $result;
	}
	
	function getGridPrList($noPr,$whereDate,$page,$start,$limit){
		$sql = "SELECT a.*, b.tgl_fpb,b.no_fpb,b.status_fpb
				FROM $this->tblPr a,$this->tblFpb b
				WHERE a.id_fpb=b.id_fpb and a.no_pr like '$noPr%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getGridInfoPr($noPr){
		
		$sql = "SELECT d.*,c.jml_brg_fpb,b.tgl_fpb,b.no_fpb
				FROM $this->tblPr a,$this->tblFpb b,$this->tblBrgFpb c,$this->tblBrg d
				WHERE a.id_fpb=b.id_fpb AND c.id_fpb=b.id_fpb AND d.id_brg=c.id_brg and a.no_pr='$noPr'";
		
		$result['rows'] 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
	
	function actAppFpb($noFpb){
		
		$sql	= "update $this->tblFpb set status_fpb='Approve' where no_fpb='$noFpb'";
		$qUpd	= $this->db->query($sql);
		
		$sql	= "SELECT b.* FROM  $this->tblFpb a, $this->tblBrgFpb b
					WHERE a.id_fpb=b.id_fpb AND a.no_fpb='$noFpb'";
		$qSql	= $this->db->query($sql)->result_array();
		
		foreach($qSql as $r){
			$jmlBrg	= $r['jml_brg_fpb'];
			$idBrg	= $r['id_brg'];
			$sUpd	= "update barang set stok=(stok+$jmlBrg) where id_brg=$idBrg";
			$qUpd	= $this->db->query($sUpd);
		}
		
		$result['status'] = true;
		$result['message'] = "FPB No : $noFpb disetujui!";
		return $result;
	}
	
	function getPr($idPr){
		$sql = "SELECT a.*,b.id_bus,c.no_fpb,DATE_FORMAT(a.tgl_pr, '%d-%m-%Y') tanggal
				FROM $this->tblPr a,$this->tblMr b,$this->tblFpb c
				WHERE c.id_mr=b.id_mr and c.id_fpb=a.id_fpb and a.id_pr=$idPr";
		
		$result 	= $this->db->query($sql)->row();
		
		return $result;
	}
	
	function getBrgFpb($idPr){
		$sql = "SELECT c.*,b.jml_brg_fpb
				FROM $this->tblFpb a,$this->tblBrgFpb b,$this->tblBrg c,$this->tblPr d
				WHERE a.id_fpb=b.id_fpb AND b.id_brg=c.id_brg and d.id_fpb=b.id_fpb and d.id_pr=$idPr";
		
		$result 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
}