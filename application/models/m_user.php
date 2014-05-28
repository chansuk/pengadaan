<?php
class M_user extends CI_Model {

    var $tblUsr	= 'user';
    var $tblBag	= 'bagian';
	

    function __construct(){
        parent::__construct();
    }
    
    function getGridUser($user,$start,$limit){
		$sql = "SELECT a.*,b.bagian 
				FROM $this->tblUsr a left join $this->tblBag b on a.id_bag=b.id_bag 
				WHERE  a.username like '$user%'";
		
		$sqlpage = "$sql limit $start,$limit";
		$result['rows'] 	= $this->db->query($sqlpage)->result_array();
		$result['countrow']	= $this->db->query($sql)->num_rows();
		
		return $result;
	}
	
	function getBagian(){
		$sql 	= "select * from $this->tblBag";
		$result	= $this->db->query($sql)->result_array();
		return $result;
	}
	
	function actSimpan($userName,$userPass,$userDesc,$userJenkel,$userTelp,$userAlm,$userBag,$userId,$statEnt){
		if($userId==''){
			$wUserId	= '';
			$userPass	= md5($userPass);
			$sqlCmd		= "insert into $this->tblUsr values('','$userName','$userPass','$userDesc','$userJenkel','$userAlm','$userTelp',$userBag,$statEnt)";
		}else{
			$wPass	= '';
			if($userPass!='xx99999xx'){
				$userPass	= md5($userPass);
				$wPass		= ",password='$userPass'";
			}
			
			$wUserId	= "and id_usr<>$userId";
			$sqlCmd		= "update $this->tblUsr set username='$userName' $wPass ,nama_user='$userDesc',jenkel_user='$userJenkel',alamat_user='$userAlm',telepon_user='$userTelp',id_bag=$userBag,status=$statEnt where id_usr=$userId";
		}
		
		$sCheck	= "select * from $this->tblUsr where username='$userName' $wUserId";
		$qCheck	= $this->db->query($sCheck)->num_rows();
		if($qCheck==0){
			$qRes	= $this->db->query($sqlCmd);
			$result['message']	= "User $userName berhasil disimpan !";
		}else{
			$result['message']	= "User $userName sudah terdaftar !";
		}
		return $result;
	}
	
	function actDel($idUsr){
		$delUser	= "delete from $this->tblUsr where id_usr=$idUsr";
		$qDelUser	= $this->db->query($delUser);
		$result['message']	= "User berhasil dihapus !";
		return $result;
	}
}