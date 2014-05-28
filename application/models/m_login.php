<?php
class M_login extends CI_Model {

    var $app_user_tbl    = 'user';
	var	$detailUsr;

    function __construct(){
        parent::__construct();
    }
    
    function loginValidate($username,$password){
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$this->db->where('status',1);
		$result	= $this->db->get($this->app_user_tbl)->result();
		
		if ( !empty($result) && count($result) == 1 ) {
			$this->auth_user->_setUserData($username);
			return true;
		}

		return false;
	}

}