<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_user {

	public function Auth_user(){
		$this->ci =& get_instance();
		$this->ci->load->library('session');
		$this->ci->load->database();
		$this->ci->load->helper(array('file','url'));
		$this->_init();
	}
	
	function _init(){
		
	}
	
	function _isLogin(){
		if(!$this->ci->session->userdata('logged_in')){
			return false;
		}else{
			return true;
		}
	}
	
	function _setUserData($userName){
		$query	= "SELECT * FROM user WHERE username='$userName'";
		$result	= $this->ci->db->query($query)->row();
		
		$userData	= array(
			'id_usr'		=> $result->id_usr,
			'username'		=> $result->username,
			'nama_user'		=> $result->nama_user,
			'jenkel_user'	=> $result->jenkel_user,
			'alamat_user'	=> $result->alamat_user,
			'telepon_user'	=> $result->telepon_user,
			'id_bag'		=> $result->id_bag,
			'status'		=> $result->status,
			'logged_in'		=> true
		);
		
		$this->ci->session->set_userdata($userData);
	}
	
	function _unsetUserDat(){
		$userData	= array(
			'id_usr'		=> '',
			'username'		=> '',
			'nama_user'		=> '',
			'jenkel_user'	=> '',
			'alamat_user'	=> '',
			'telepon_user'	=> '',
			'id_bag'		=> '',
			'status'		=> '',
			'logged_in'		=> false
		);
		
		$this->ci->session->unset_userdata($userData);
	}
}