<?php
class M_ttb extends CI_Model {
	
	var $tblPo	= 'po';
	var $tblSph	= 'sph';
	var $tblSup	= 'supplier';
	var $tblTtb	= 'ttb';
	var $tblPr	= 'pr';
	var $tblBrg	= 'barang';
	var $tblBrgSph	= 'brg_sph';
	
    function __construct(){
        parent::__construct();
    }
    
	function getGridPo($noPo,$whereDate,$start,$limit){
		$sql = "SELECT a.*,b.no_sph,b.id_sph,c.nama_sup,d.no_pr,d.tgl_pr,d.pool_pr,d.status_pr
				FROM $this->tblPo a,$this->tblSph b,$this->tblSup c,$this->tblPr d
				WHERE a.id_sph=b.id_sph AND c.id_sup=b.id_sup and d.id_pr=b.id_pr and a.no_po like '$noPo%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getNoTtb(){
		$sql	= "select SUBSTRING_INDEX(no_ttb, '/', -1) no_ttb from $this->tblTtb order by tgl_ttb desc limit 1";
		$result	= $this->db->query($sql)->result_array();
		
		$dateTtb	= date('Ym');
		if(count($result)>0){
			$noRes	= (int) $result[0]['no_ttb'];
			$noLast	= $noRes+1;
			$noLast	= str_pad($noLast, 3, "0", STR_PAD_LEFT);
			$noTtb	= "TTB/LOG/$dateTtb/$noLast";
		}else{
			$noTtb	= "TTB/LOG/$dateTtb/001";
		}

		return $noTtb;
	}
	
	function getGridTtb($noPo){
		$sql= "SELECT d.*,c.harga_brg_sph,c.jml_brg_sph,(c.harga_brg_sph*c.jml_brg_sph) total
				FROM po a,sph b,brg_sph c,barang d
				WHERE a.id_sph=b.id_sph AND b.id_sph=c.id_sph AND d.id_brg=c.id_brg AND a.no_po='$noPo'";
		$result['rows'] 	= $this->db->query($sql)->result_array();
		return $result;
	}
	
	function actSimpan($noTtb,$sj,$ketTtb,$idPo){
		$dateNow	= date("Y-m-d H:i:s");
		$sqlIns		= "insert into $this->tblTtb(no_ttb, no_ref_sj, tgl_ttb, status_ttb,ket_ttb,id_po) values('$noTtb','$sj','$dateNow','Receive','$ketTtb',$idPo)";
		$qIns	= $this->db->query($sqlIns);
		
		$sqlUpd	= "update $this->tblPo set status_po='Receive' where id_po=$idPo";
		$qUpd	= $this->db->query($sqlUpd);
		
		$sqlSel	= "SELECT a.id_pr 
					FROM $this->tblPr a,$this->tblPo b,$this->tblSph c
					WHERE c.id_sph=b.id_sph AND a.id_pr=c.id_pr AND b.id_po=$idPo";
		$qSel	= $this->db->query($sqlSel)->row();
		
		$sqlUpd	= "update $this->tblPr set status_pr='Approve' where id_pr=$qSel->id_pr";
		$qUpd	= $this->db->query($sqlUpd);
		
		$result['status'] 	= true;
		$result['message'] 	= "No TTB : $noTtb barang telah diterima !";
		
		return $result;
	}
	
	function getTtb($idTtb){
		$sql = "SELECT c.nama_sup,d.*,a.no_po, DATE_FORMAT(d.tgl_ttb, '%d-%m-%Y') tanggal
				FROM $this->tblPo a,$this->tblSph b,$this->tblSup c,$this->tblTtb d
				WHERE a.id_sph=b.id_sph AND c.id_sup=b.id_sup and d.id_po=a.id_po and d.id_ttb=$idTtb";
		
		$result 	= $this->db->query($sql)->row();
		
		return $result;
	}
	
	function getBrgSph($idTtb){
		$sql = "SELECT c.*,b.harga_brg_sph,b.jml_brg_sph
				FROM $this->tblPo a,$this->tblBrgSph b,$this->tblBrg c,$this->tblTtb d
				WHERE a.id_sph=b.id_sph AND c.id_brg=b.id_brg and d.id_po=a.id_po and d.id_ttb=$idTtb";
		
		$result 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
}