<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->idBag	= $this->session->userdata('id_bag');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('nama_user');
		$this->load->view('main/v_head');
		$this->load->view('main/v_panel',$data);
		
	}
	
	function menuTree(){
		$bag	 = $this->idBag;
		if($bag==4){ //logsitik
			$menu	= "{ 
				text: '.',
				children: [{
					text:'Transaksi',
					expanded: true,
					children:[{
						text:'Entry Purchase Request',
						id:'pr',
						leaf:true
					},{
						text:'Entry Tanda Terima Barang',
						id:'ttb',
						leaf:true
					},{
						text:'Entry Retur Barang',
						id:'retur',
						leaf:true 
					}]
				},{
					text:'Laporan',
					expanded: true,
					children:[{
						text:'Laporan Purchase Request',
						id:'rpt_pr',
						leaf:true
					},{
						text:'Laporan Tanda Terima',
						id:'rpt_ttb',
						leaf:true
					},{
						text:'Laporan Retur Barang',
						id:'rpt_retur',
						leaf:true
					}]
				}]
			}";
		}elseif($bag==5){ //purchasing
			$menu = "{ 
				text: '.',
				children: [{
					text:'Master',
					expanded: true,
					children:[{
						text:'Entry Supplier',
						id:'supplier',
						leaf:true
					}]
				},{
					text:'Transaksi',
					expanded: true,
					children:[{
						text:'Entry Penawaran Harga Supplier',
						id:'sph',
						leaf:true
					},{
						text:'Entry Purchase Order',
						id:'po',
						leaf:true
					}]
				},{
					text:'Laporan',
					expanded: true,
					children:[{
						text:'Laporan Purchase Order',
						id:'rpt_po',
						leaf:true
					}]
				}]
			}";
		}elseif($bag==3){//gudang
			$menu	= "{ 
				text: '.',
				children: [{
					text:'Master',
					expanded: true,
					children:[{
						text:'Entry Barang',
						id:'barang',
						leaf:true
					},{
						text:'Entry Bus',
						id:'bus',
						leaf:true
					}]
				},{
					text:'Transaksi',
					expanded: true,
					children:[{
						text:'Entry Permintaan Barang',
						id:'fpb',
						leaf:true
					}]
				},{
					text:'Laporan',
					expanded: true,
					children:[{
						text:'Laporan Permintaan Barang',
						id:'rpt_pb',
						leaf:true
					},{
						text:'Laporan Stok Barang',
						id:'rpt_stok',
						leaf:true
					},{
						text:'Laporan Rekap Permintaan Barang',
						id:'rpt_rekap',
						leaf:true
					}]
				}]
			}";
		}elseif($bag==2){//tehnik
			$menu = "{ 
				text: '.',
				children: [{
					text:'Transaksi',
					expanded: true,
					children:[{
						text:'Entry Material Requisition',
						id:'mr',
						leaf:true
					}]
				},{
					text:'Laporan',
					expanded: true,
					children:[{
						text:'Laporan Permintaan Tehnik',
						id:'rpt_tehnik',
						leaf:true
					}]
				}]
			}";
		}else{ //admin
			$menu = "{ 
				text: '.',
				children: [{
					text:'Master',
					expanded: true,
					children:[{
						text:'Entry User',
						id:'user',
						leaf:true
					},{
						text:'Entry Barang',
						id:'barang',
						leaf:true
					},{
						text:'Entry Bus',
						id:'bus',
						leaf:true
					},{
						text:'Entry Supplier',
						id:'supplier',
						leaf:true
					}]
				},{
					text:'Transaksi',
					expanded: true,
					children:[{
						text:'Entry Material Requisition',
						id:'mr',
						leaf:true
					},{
						text:'Entry Permintaan Barang',
						id:'fpb',
						leaf:true
					},{
						text:'Entry Purchase Request',
						id:'pr',
						leaf:true
					},{
						text:'Entry Penawaran Harga Supplier',
						id:'sph',
						leaf:true
					},{
						text:'Entry Purchase Order',
						id:'po',
						leaf:true
					},{
						text:'Entry Tanda Terima Barang',
						id:'ttb',
						leaf:true
					},{
						text:'Entry Retur Barang',
						id:'retur',
						leaf:true 
					}]
				},{
					text:'Laporan',
					expanded: true,
					children:[{
						text:'Laporan Permintaan Tehnik',
						id:'rpt_tehnik',
						leaf:true
					},{
						text:'Laporan Permintaan Barang',
						id:'rpt_pb',
						leaf:true
					},{
						text:'Laporan Purchase Request',
						id:'rpt_pr',
						leaf:true
					},{
						text:'Laporan Purchase Order',
						id:'rpt_po',
						leaf:true
					},{
						text:'Laporan Tanda Terima',
						id:'rpt_ttb',
						leaf:true
					},{
						text:'Laporan Retur Barang',
						id:'rpt_retur',
						leaf:true
					},{
						text:'Laporan Stok Barang',
						id:'rpt_stok',
						leaf:true
					},{
						text:'Laporan Rekap Permintaan Barang',
						id:'rpt_rekap',
						leaf:true
					}]
				}]
			}";
		}
		echo $menu;
	}
}