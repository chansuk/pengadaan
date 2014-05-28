<?php
class M_sph extends CI_Model {

    var $tblSph	= 'sph';
    var $tblPr	= 'pr';
    var $tblFpb	= 'fpb';
    var $tblBrg	= 'barang';
    var $tblBrgFpb	= 'brg_fpb';
    var $tblBrgSph	= 'brg_sph';
    var $tblSup	= 'supplier';
	

    function __construct(){
        parent::__construct();
    }
    
	function getGridPr($noPr,$whereDate,$start,$limit){
		$sql = "SELECT * 
				FROM $this->tblPr
				WHERE no_pr like '$noPr%' $whereDate";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getGridInfoPr($noPr){
		$sql = "SELECT b.*,c.jml_brg_fpb,d.id_pr,d.no_pr
				FROM $this->tblFpb a,$this->tblBrg b,$this->tblBrgFpb c,$this->tblPr d
				WHERE a.id_fpb=c.id_fpb AND b.id_brg=c.id_brg and a.id_fpb=d.id_fpb and d.no_pr='$noPr'";
		
		$result['rows'] 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
	
	function getGridSphDet($noPr){
		$sql = "SELECT a.*,b.nama_sup,SUM(d.harga_brg_sph*d.jml_brg_sph) total,
					case when a.status_sph='Approve' then 1 else 0 end status
				FROM $this->tblSph a,$this->tblSup b,$this->tblPr c,$this->tblBrgSph d
				WHERE a.id_pr=c.id_pr AND b.id_sup=a.id_sup AND d.id_sph=a.id_sph and c.no_pr='$noPr'
				GROUP BY a.id_sph ";
		
		$result['rows'] 	= $this->db->query($sql)->result_array();
		
		return $result;
	}
	
	function getSphDetBrg($idSph){
		$sql = "SELECT a.*,b.harga_brg_sph,b.jml_brg_sph,(b.harga_brg_sph*b.jml_brg_sph) total
				FROM $this->tblBrg a,$this->tblBrgSph b,$this->tblSph c
				WHERE a.id_brg=b.id_brg AND c.id_sph=b.id_sph and c.id_sph=$idSph";
		$result['rows'] 	= $this->db->query($sql)->result_array();
		
		return $result;		
	}
	
	function getNoSph(){
		$sql	= "select SUBSTRING_INDEX(no_sph, '/', -1) no_sph from $this->tblSph order by tgl_sph desc limit 1";
		$result	= $this->db->query($sql)->result_array();
		
		$dateSph= date('Ym');
		if(count($result)>0){
			$noRes	= (int) $result[0]['no_sph'];
			$noLast	= $noRes+1;
			$noLast	= str_pad($noLast, 4, "0", STR_PAD_LEFT);
			$noSph	= "SPH/PRC/$dateSph/$noLast";
		}else{
			$noSph	= "SPH/PRC/$dateSph/0001";
		}

		return $noSph;
	}
	
	function getSup(){
		$sql 	= "select * from $this->tblSup";
		$result	= $this->db->query($sql)->result_array();
		
		return json_encode($result);
	}
	
	function insertSph($noSph,$idSup,$idPr,$brgData,$noSphRef){
		$dateNow	= date("Y-m-d H:i:s");
		$allBarang	= explode(';',$brgData);
		
		$sqlCSup	= "select * from $this->tblSph where id_sup='$idSup' AND id_pr=$idPr";
		$qCSup		= $this->db->query($sqlCSup)->num_rows();
		
		$sqlNmSup	= "select * from $this->tblSup where id_sup='$idSup'";
		$qNmSup		= $this->db->query($sqlNmSup)->row();
		
		if($qCSup==0){
			$sqlSph		= "insert into $this->tblSph (no_sph,tgl_sph,status_sph,id_pr,id_sup,no_sph_ref) values ('$noSph','$dateNow','Review',$idPr,$idSup,'$noSphRef')";
			$qSph		= $this->db->query($sqlSph);
			
			
			$sqlIdSph	= "select id_sph from $this->tblSph where no_sph='$noSph'";
			$qIdSph		= $this->db->query($sqlIdSph)->row();
			
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
				$hrgBrg		= $dataDet[2];
				
				$sqlBrgSph	= "insert into $this->tblBrgSph(id_sph, id_brg, harga_brg_sph, jml_brg_sph) values($qIdSph->id_sph,$idBrg,$hrgBrg,$jmlBrg)";
				$qBrgSph		= $this->db->query($sqlBrgSph);
			}
			
			$sqlUpdPr	= "update $this->tblPr set status_pr='Review' where id_pr=$idPr";
			$qUpdPr		= $this->db->query($sqlUpdPr);
			
			$result['status'] = true;
			$result['message'] = "SPH no $noSph berhasil disimpan!";
		}else{
			$result['status'] = true;
			$result['message'] = "Supplier $qNmSup->nama_sup sudah mengajukan penawaran!";
		}
		return $result;
	}
	
	function appSph($idSph){
		
		$sqlUpdSph	= "update $this->tblSph set status_sph='Approve' where id_sph=$idSph";
		$qUpdSph	= $this->db->query($sqlUpdSph);
		
		$sqlIdPr	= "select id_pr from $this->tblSph where id_sph=$idSph";
		$qIdPr		= $this->db->query($sqlIdPr)->row();
		
		$sqlUpdPr	= "update $this->tblPr set status_pr='Approve SPH' where id_pr=$qIdPr->id_pr";
		$qUpdPr		= $this->db->query($sqlUpdPr);
		
		$result['status'] 	= true;
		$result['message'] 	= "SPH telah disetujui!";
		
		return $result;
	}
}