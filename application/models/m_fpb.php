<?php
class M_fpb extends CI_Model {

    var $tblMr		= 'mr';
    var $tblBus		= 'bus';
    var $tblBrgMr	= 'brg_mr';
    var $tblBrgFpb	= 'brg_fpb';
    var $tblBrg		= 'barang';
    var $tblFpb		= 'fpb';
	
    function __construct(){
        parent::__construct();
    }
    
	function getGridFpb($noFpb,$whereDate,$start,$limit){
		$sql = "SELECT a.*,b.no_mr,c.koridor_bus,c.id_bus,b.status_mr
				FROM $this->tblFpb a,$this->tblMr b,$this->tblBus c
				WHERE a.id_mr=b.id_mr and c.id_bus=b.id_bus and no_fpb like '$noFpb%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
    function getGridMr($date1,$date2,$noMr,$whereDate,$page,$start,$limit){
		$sql = "SELECT a.*,b.koridor_bus 
				FROM $this->tblMr a,$this->tblBus b
				WHERE a.id_bus=b.id_bus  and a.no_mr like '$noMr%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getGridInfoMr($noMr,$type){
		$status = '';
		if($type == 'fpb'){
			$status	= 'and c.status_brg_mr=0';
		}
		
		$sql = "SELECT b.*,
					case c.status_brg_mr 
						when 0 then 'Pending'
						else 'Approve'
					end status_brg_mr_desc,c.status_brg_mr,c.jml_brg_mr,a.id_mr,d.status_fpb
				FROM $this->tblBrg b,$this->tblBrgMr c,$this->tblMr a
				left join $this->tblFpb d on a.id_mr=d.id_mr AND d.status_fpb='Approve'
				WHERE a.id_mr=c.id_mr AND b.id_brg=c.id_brg AND a.no_mr='$noMr' $status";
		
		$result['rows'] 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
	
	function getGridCetak($noFpb){
		$sql = "SELECT b.*,c.jml_brg_fpb,a.id_fpb,a.status_fpb
				FROM $this->tblBrg b,$this->tblBrgFpb c,$this->tblFpb a
				WHERE a.id_fpb=c.id_fpb AND b.id_brg=c.id_brg AND a.no_fpb='$noFpb'";
		
		$result['rows'] 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
	
	function getNoFpb(){
		$sql	= "select SUBSTRING_INDEX(no_fpb, '/', -1) no_fpb from $this->tblFpb order by tgl_fpb desc limit 1";
		$result	= $this->db->query($sql)->result_array();
		
		$dateFpb	= date('Ym');
		if(count($result)>0){
			$noRes	= (int) $result[0]['no_fpb'];
			$noLast	= $noRes+1;
			$noLast	= str_pad($noLast, 3, "0", STR_PAD_LEFT);
			$noFpb	= "FPB/GDG/$dateFpb/$noLast";
		}else{
			$noFpb	= "FPB/GDG/$dateFpb/001";
		}

		return $noFpb;
	}
	
	
	
	function actSetuju($idMr,$noMr,$brgData){
		$allBarang	= explode(';',$brgData);
		
		$barang		= '';
		if(count($allBarang)>0){
			$totData = count($allBarang)-1;
		}else{
			$totData = 0;
		}
		
		for($i=0;$i<$totData;$i++){
			$dataDet	= explode('#',$allBarang[$i]);
			$idBrg		= $dataDet[0];
			$idMr		= $dataDet[1];
			
			$sqlBrgMr	= "update $this->tblBrgMr set status_brg_mr=1 where id_mr=$idMr and id_brg=$idBrg";
			$qBrgMr		= $this->db->query($sqlBrgMr);
			
			$sSel		= "select jml_brg_mr jml from $this->tblBrgMr where id_brg=$idBrg and id_mr=$idMr";
			$qSel		= $this->db->query($sSel)->row();
			
			$sqlBrgMr	= "update $this->tblBrg set stok=(stok-$qSel->jml) where id_brg=$idBrg";
			$qBrgMr		= $this->db->query($sqlBrgMr);
		}
		
		$sqlCheck	= "select * from $this->tblBrgMr where id_mr=$idMr and status_brg_mr=0";
		$check		= $this->db->query($sqlCheck)->num_rows();
		if($check==0){
			$sqlUpdate	= "update $this->tblMr set status_mr='Approve' where id_mr='$idMr'";
			$update		= $this->db->query($sqlUpdate);
			
			$result['status']	= true;
			$result['message']	= "Permintaan material no $noMr disetujui !";
		}else{
			$result['status']	= true;
			$result['message']	= "Barang berhasil disetujui dan Masih terdapat barang yang belum disetujui !";
		}
		return $result;
	}
	
	function insertFpb($noFpb,$noMr,$brgData){
		$dateNow	= date("Y-m-d H:i:s");
		$allBarang	= explode(';',$brgData);
		
		$sqlIdMr	= "select id_mr from $this->tblMr where no_mr='$noMr'";
		$qIdMr		= $this->db->query($sqlIdMr)->row();
		
		$sqlMr		= "insert into $this->tblFpb(no_fpb,tgl_fpb,status_fpb,id_mr) values('$noFpb','$dateNow','Request',$qIdMr->id_mr)";
		$qMr	= $this->db->query($sqlMr);
		
		$sqlIdFpb	= "select id_fpb from $this->tblFpb where no_fpb='$noFpb'";
		$qIdFpb		= $this->db->query($sqlIdFpb)->row();
		
		$sqlUpdMr	= "update $this->tblMr set status_mr='Process' where no_mr='$noMr'";
		$qUpdMr		= $this->db->query($sqlUpdMr);
		
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
			
			$sqlBrgFpb	= "insert into $this->tblBrgFpb(id_fpb,id_brg,jml_brg_fpb) values($qIdFpb->id_fpb,$idBrg,$jmlBrg)";
			$qBrgFpb	= $this->db->query($sqlBrgFpb);
		}
		
		$result['status'] = true;
		$result['message'] = "Permintaan Barang No $noFpb berhasil dikirim!";
		return $result;
	}
	
	function getFpb($idFpb){
		$sql = "SELECT a.*,b.id_bus,b.no_mr,DATE_FORMAT(a.tgl_fpb, '%d-%m-%Y') tanggal
				FROM $this->tblFpb a,$this->tblMr b
				WHERE a.id_mr=b.id_mr and a.id_fpb=$idFpb";
		
		$result 	= $this->db->query($sql)->row();
		
		return $result;
	}
	
	function getBrgFpb($idFpb){
		$sql = "SELECT c.*,b.jml_brg_fpb
				FROM $this->tblFpb a,$this->tblBrgFpb b,$this->tblBrg c
				WHERE a.id_fpb=b.id_fpb AND b.id_brg=c.id_brg and a.id_fpb=$idFpb";
		
		$result 	= $this->db->query($sql)->result_array();
		
		return $result;
	}

}