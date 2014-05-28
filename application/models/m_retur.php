<?php
class M_retur extends CI_Model {
	
	var $tblPo	= 'po';
	var $tblSph	= 'sph';
	var $tblPr	= 'pr';
	var $tblSup	= 'supplier';
	var $tblTtb	= 'ttb';
	var $tblRet	= 'retur';
	var $tblBrg	= 'barang';
	var $tblBrgRet	= 'brg_ret';
	var $tblBrgSph	= 'brg_sph';
	
    function __construct(){
        parent::__construct();
    }
    
	function getGridTtb($noTtb,$whereDate,$start,$limit){
		$sql = "SELECT a.*,b.no_po,c.no_sph,d.nama_sup,b.id_sph,b.tgl_po,b.status_po
				FROM $this->tblTtb a,$this->tblPo b,$this->tblSph c,$this->tblSup d
				WHERE a.id_po=b.id_po AND b.id_sph=c.id_sph AND c.id_sup=d.id_sup and a.no_ttb like '$noTtb%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getGridRet($noTtb,$noRet,$whereDate,$start,$limit){
		$sql = "SELECT a.*,b.no_ttb,e.nama_sup
				FROM $this->tblRet a,$this->tblTtb b,$this->tblPo c,$this->tblSph d,$this->tblSup e
				WHERE a.id_ttb=b.id_ttb AND b.id_po=c.id_po AND d.id_sph=c.id_sph AND d.id_sup=e.id_sup and b.no_ttb like '$noTtb%' and a.no_ret like '$noRet%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getNoRet(){
		$sql	= "select SUBSTRING_INDEX(no_ret, '/', -1) no_ret from $this->tblRet order by tgl_ret desc limit 1";
		$result	= $this->db->query($sql)->result_array();
		
		$dateRet	= date('Ym');
		if(count($result)>0){
			$noRes	= (int) $result[0]['no_ret'];
			$noLast	= $noRes+1;
			$noLast	= str_pad($noLast, 3, "0", STR_PAD_LEFT);
			$noRet	= "RET/LOG/$dateRet/$noLast";
		}else{
			$noRet	= "RET/LOG/$dateRet/001";
		}

		return $noRet;
	}
	
	function getGridBrg($idSph){
		$sql= "SELECT b.*,a.jml_brg_sph
				FROM $this->tblBrgSph a,$this->tblBrg b
				WHERE a.id_brg=b.id_brg AND a.id_sph=$idSph";
		$result['rows'] 	= $this->db->query($sql)->result_array();
		return $result;
	} 
	
	function getGridBrgFu($noRet){
		$sql= "SELECT c.*,b.jml_brg_ret,CASE WHEN b.status_brg_ret='Retur' THEN 0 ELSE 1 END terima,a.id_ret
				FROM $this->tblRet a,$this->tblBrgRet b,$this->tblBrg c
				WHERE a.id_ret=b.id_ret AND c.id_brg=b.id_brg and a.no_ret='$noRet'";
		$result['rows'] 	= $this->db->query($sql)->result_array();
		return $result;
	}
	
	function actSimpan($idTtb,$noRet,$ketRet,$brgData){
		$dateNow	= date("Y-m-d H:i:s");
		$allBarang	= explode(';',$brgData);
		
		$sqlRet		= "insert into $this->tblRet(no_ret,tgl_ret,ket_ret,id_ttb) 
						values('$noRet','$dateNow','$ketRet',$idTtb)";
		$qRet		= $this->db->query($sqlRet);
		
		$sqlIdRet	= "select id_ret from $this->tblRet where no_ret='$noRet'";
		$qIdRet		= $this->db->query($sqlIdRet)->row();
		
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
			
			$sqlBrgRet	= "insert into $this->tblBrgRet (id_ret, id_brg, status_brg_ret, jml_brg_ret) values($qIdRet->id_ret,$idBrg,'Retur',$jmlBrg)";
			$qBrgRet	= $this->db->query($sqlBrgRet);
		}
		
		$sqlUpdTtb	= "update $this->tblTtb set status_ttb='Retur' 
						where id_ttb=$idTtb";
		$qUpdTtb	= $this->db->query($sqlUpdTtb);
		
		$sqlSel	= "SELECT d.id_pr
					FROM $this->tblTtb a,$this->tblPo b,$this->tblPr d,$this->tblSph c
					WHERE a.id_po=b.id_po AND c.id_sph=b.id_sph AND d.id_pr=c.id_pr and a.id_ttb=$idTtb";
		$qSel	= $this->db->query($sqlSel)->row();
		
		$sqlUpdTtb	= "update $this->tblPr set status_pr='Process' 
						where id_pr=$qSel->id_pr";
		$qUpdTtb	= $this->db->query($sqlUpdTtb);
		
		$result['status'] = true;
		$result['message'] = "Barang berhasil diretur dengan no $noRet";
		return $result;
	}
	
	function actSimpanFu($idRet,$noRet,$brgData){
		$dateNow	= date("Y-m-d H:i:s");
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
			
			$sqlBrgRet	= "update $this->tblBrgRet set status_brg_ret='Receive' 
							where id_ret=$idRet and id_brg=$idBrg";
			$qBrgRet	= $this->db->query($sqlBrgRet);
		}
		
		$checkBrg	= "select * from $this->tblBrgRet where id_ret=$idRet and status_brg_ret='Retur'";
		$qCheckBrg	= $this->db->query($checkBrg)->num_rows();
		
		if($qCheckBrg==0){
			$idTtb		= "select * from $this->tblRet where id_ret=$idRet";
			$qIdTtb		= $this->db->query($idTtb)->row();
			$sqlUpdTtb	= "update $this->tblTtb set status_ttb='Retur Complete' 
							where id_ttb=$qIdTtb->id_ttb";
			$qUpdTtb	= $this->db->query($sqlUpdTtb);
			
			$sqlSel	= "SELECT d.id_pr
					FROM $this->tblTtb a,$this->tblPo b,$this->tblPr d,$this->tblSph c
					WHERE a.id_po=b.id_po AND c.id_sph=b.id_sph AND d.id_pr=c.id_pr and a.id_ttb=$qIdTtb->id_ttb";
			$qSel	= $this->db->query($sqlSel)->row();
			
			$sqlUpdTtb	= "update $this->tblPr set status_pr='Approve' 
							where id_pr=$qSel->id_pr";
			$qUpdTtb	= $this->db->query($sqlUpdTtb);
			$result['status'] 	= true;
			$result['message'] 	= "Barang retur no $noRet sudah diterima semua !";
		}else{
			$result['status'] 	= true;
			$result['message'] 	= "Barang berhasil diterima !";
		}
		
		return $result;
	}
	
	function getRet($idRet){
		$sql = "SELECT c.*,e.*,a.no_po, DATE_FORMAT(e.tgl_ret, '%d-%m-%Y') tanggal
				FROM $this->tblPo a,$this->tblSph b,$this->tblSup c,$this->tblTtb d,$this->tblRet e
				WHERE a.id_sph=b.id_sph AND c.id_sup=b.id_sup and d.id_po=a.id_po and e.id_ttb=d.id_ttb and e.id_ret=$idRet";
		
		$result 	= $this->db->query($sql)->row();
		
		return $result;
	}
	
	function getBrgRet($idRet){
		$sql = "SELECT c.*,b.harga_brg_sph,f.jml_brg_ret
				FROM $this->tblPo a,$this->tblBrgSph b,$this->tblBrg c,$this->tblTtb d,$this->tblRet e,$this->tblBrgRet f
				WHERE a.id_sph=b.id_sph AND c.id_brg=b.id_brg and d.id_po=a.id_po and d.id_ttb=e.id_ttb and f.id_ret=f.id_ret and e.id_ret=$idRet";
		
		$result 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
}