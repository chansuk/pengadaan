<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_tehnik extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_rpt_tehnik');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		
		
		$this->load->view('main/v_head');
		$this->load->view('v_rpt_tehnik',$data);
		
	}
	
	function cetak(){
		$get 		= $this->uri->uri_to_assoc();
		$dateFrom	= $get['dateFrom'];
		$dateTo		= $get['dateTo'];
		
		$getData	= $this->m_rpt_tehnik->getData($dateFrom,$dateTo);
		
		$html	= $this->head($dateFrom,$dateTo);
		$html	.= $this->content($getData);
		$html	.= $this->foot();
		
		$this->html2pdf->paper('a4', 'portrait');
		$this->html2pdf->filename("LapPermintaanTehnik.pdf");
		$this->html2pdf->folder('./assets/pdfs/');
		$this->html2pdf->html($html);
		$this->html2pdf->create('download');
	}
	
	function head($dateFrom,$dateTo){
		$logoTb	= APPPATH.'../assets/img/logotb.png';
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
			</table><hr><p class="style4" align="center">Laporan Permintaan Tehnik<br>Periode '.$dateFrom.' s/d '.$dateTo.'</p>
			';
		
	}
	
	function content($getData){
		$content = '<table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
		  <tr class="style7">
			<td>No</td>
			<td>Tanggal</td>
			<td>No MR</td>
			<td>No Bus</td>
			<td>Kode Barang</td>
			<td>Nama Barang</td>
			<td>Satuan</td>
			<td>Jumlah</td>
		  </tr>';
		$noSm	= '';$i=1;
		foreach($getData as $r){
			$tglMr	= $r['tgl_mr'];
			$noMr	= $r['no_mr'];
			$idBus	= $r['id_bus'];
			$kdBrg	= $r['kode_brg'];
			$nmBrg	= $r['nama_brg'];
			$satBrg	= $r['satuan'];
			$jmlBrg	= $r['jml_brg_mr'];
			$content .= '<tr class="style7">';
			
			if($noMr==$noSm){
				$content .='
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>';
			}else{
				$content .='
					<td>'.$i.'</td>
					<td>'.$tglMr.'</td>
					<td>'.$noMr.'</td>
					<td>'.$idBus.'</td>';
				$i++;
			}
			
			$content .='<td>'.$kdBrg.'</td>
					<td>'.$nmBrg.'</td>
					<td>'.$satBrg.'</td>
					<td>'.$jmlBrg.'</td>
				  </tr>';
			
			$noSm=$noMr;
		}
		
		$content .= '</table>';
		return $content;
	}
	
	function foot(){
		return '</body></html>';
	}
}