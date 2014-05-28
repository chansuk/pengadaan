<?php
class M_po extends CI_Model {

    var $tblSph	= 'sph';
	var $tblBrgSph	= 'brg_sph';
	var $tblBrg	= 'barang';
	var $tblPr	= 'pr';
    var $tblSup	= 'supplier';
    var $tblPo	= 'po';
	
    function __construct(){
        parent::__construct();
    }
    
	function getGridPr($noPr,$whereDate,$start,$limit){
		$sql = "SELECT a.*,b.no_pr,b.pool_pr,b.tgl_pr,c.nama_sup,b.status_pr 
				FROM $this->tblSph a,$this->tblPr b,$this->tblSup c
				WHERE a.id_pr=b.id_pr AND c.id_sup=a.id_sup and a.status_sph='Approve'  and b.no_pr like '$noPr%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getGridInfoPr($noPr){
		$sql = "SELECT c.*,b.harga_brg_sph,b.jml_brg_sph,(b.harga_brg_sph*b.jml_brg_sph) total
				FROM $this->tblSph a,$this->tblBrgSph b,$this->tblBrg c,$this->tblPr d,$this->tblSup e
				WHERE d.no_pr='$noPr' AND a.id_sph=b.id_sph and a.status_sph='Approve' AND b.id_brg=c.id_brg AND a.id_sup=e.id_sup AND d.id_pr=a.id_pr";
		
		$result['rows'] 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
	
	function getNoPo(){
		$sql	= "select SUBSTRING_INDEX(no_po, '/', -1) no_po from $this->tblPo order by tgl_po desc limit 1";
		$result	= $this->db->query($sql)->result_array();
		
		$datePo	= date('Ym');
		if(count($result)>0){
			$noRes	= (int) $result[0]['no_po'];
			$noLast	= $noRes+1;
			$noLast	= str_pad($noLast, 3, "0", STR_PAD_LEFT);
			$noPo	= "PO/PRC/$datePo/$noLast";
		}else{
			$noPo	= "PO/PRC/$datePo/001";
		}

		return $noPo;
	}
	
	function actSimpan($idPr,$idSph,$noPo){
		$dateNow	= date("Y-m-d H:i:s");
		
		$sqlPo		= "insert into $this->tblPo(no_po,tgl_po,status_po,id_sph) values('$noPo','$dateNow','Sent',$idSph)";
		$qPo		= $this->db->query($sqlPo);
		
		$sqlUpdSph	= "update $this->tblPr set status_pr='Process' where id_pr=$idPr";
		$qUpdSph	= $this->db->query($sqlUpdSph);
		
		$result['status'] = true;
		$result['message'] = "PO no $noPo berhasil dikirim!";
		return $result;
	}
	
	function actApprove($noPr){
		$sql	= "update $this->tblPr set status_pr='Approve' where no_pr='$noPr'";
		$qUpd	= $this->db->query($sql);
		
		$result['status'] = true;
		$result['message'] = "PR no $noPr disetujui!";
		return $result;
	}
	
	function getPo($idPo){
		$sql = "SELECT a.tgl_po,a.no_po,c.nama_sup,c.alamat_sup,c.telpon_sup, DATE_FORMAT(a.tgl_po, '%d-%m-%Y') tanggal
				FROM $this->tblPo a,$this->tblSph b,$this->tblSup c
				WHERE a.id_sph=b.id_sph AND c.id_sup=b.id_sup and a.id_po=$idPo";
		
		$result 	= $this->db->query($sql)->row();
		
		return $result;
	}
	
	function getBrgSph($idPo){
		$sql = "SELECT c.*,b.harga_brg_sph,b.jml_brg_sph
				FROM $this->tblPo a,$this->tblBrgSph b,$this->tblBrg c
				WHERE a.id_sph=b.id_sph AND c.id_brg=b.id_brg and a.id_po=$idPo";
		
		$result 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
}