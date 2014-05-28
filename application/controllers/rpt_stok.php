<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_stok extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_rpt_stok');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		
		
		$this->load->view('main/v_head');
		$this->load->view('v_rpt_stok',$data);
		
	}
	
	function cetak(){
		
		$getData	= $this->m_rpt_stok->getData();
		
		$html	= $this->head();
		$html	.= $this->content($getData);
		$html	.= $this->foot();
		
		$this->html2pdf->paper('a4', 'portrait');
		$this->html2pdf->filename("LapStokBarang.pdf");
		$this->html2pdf->folder('./assets/pdfs/');
		$this->html2pdf->html($html);
		$this->html2pdf->create('download');
	}
	
	function head(){
		$logoTb	= APPPATH.'../assets/img/logotb.png';
		$date	= date('m-d-Y');
		return '<html>
			<head>
			<style type="text/css">
			<!--
			.style1 {
				font-family: Arial, Helvetica, sans-serif;
				font-weight: bold;
				font-size: 24px;
			}
			.style4 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 14px;
				font-weight: bold;
			}
			.style7 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
			-->
			</style>
			</head>

			<body>
			<table width="100%" border="0" cellspacing="0" cellpadding="4">
			  <tr>
				<td width="25%" valign="middle"><span class="style1"><img src="'.$logoTb.'" width="100" height="100"></span></td>
				<td width="75%" valign="middle"><p><span class="style1">PT. TRANS BATAVIA</span></p>
				  <p><span class="style4">Konsorsium Busway Koridor 2 dan 3</span><br>
				  </p></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			</table><hr><p class="style4" align="center">Laporan Stok Barang<br>Tanggal '.$date.'</p>
			';
		
	}
	
	function content($getData){
		$content = '<table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
		  <tr class="style7">
			<td>No</td>
			<td>Kode Barang</td>
			<td>Nama Barang</td>
			<td>Part No</td>
			<td>Stok Min</td>
			<td>Stok Max</td>
			<td>Stok</td>
			<td>Satuan</td>
		  </tr>';
		$i=1;
		foreach($getData as $r){
			$kdBrg	= $r['kode_brg'];
			$nmBrg	= $r['nama_brg'];
			$partNo	= $r['part_no'];
			$stokMin= $r['stok_min'];
			$stokMax= $r['stok_max'];
			$stok	= $r['stok'];
			$satBrg	= $r['satuan'];
			$content .= '<tr class="style7">';
			$content .='
					<td>'.$i.'</td>
					<td>'.$kdBrg.'</td>
					<td>'.$nmBrg.'</td>
					<td>'.$partNo.'</td>
					<td>'.$stokMin.'</td>
					<td>'.$stokMax.'</td>
					<td>'.$stok.'</td>
					<td>'.$satBrg.'</td>
				  </tr>';
			$i++;
		}
		
		
		$content .= '</table>';
		return $content;
	}
	
	function foot(){
		return '</body></html>';
	}
}