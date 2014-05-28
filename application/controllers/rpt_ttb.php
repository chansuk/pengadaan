<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_ttb extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_rpt_ttb');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		
		
		$this->load->view('main/v_head');
		$this->load->view('v_rpt_ttb',$data);
		
	}
	
	function cetak(){
		$get 		= $this->uri->uri_to_assoc();
		$dateFrom	= $get['dateFrom'];
		$dateTo		= $get['dateTo'];
		
		$getData	= $this->m_rpt_ttb->getData($dateFrom,$dateTo);
		
		$html	= $this->head($dateFrom,$dateTo);
		$html	.= $this->content($getData);
		$html	.= $this->foot();
		//exit;
		$this->html2pdf->paper('a4', 'portrait');
		$this->html2pdf->filename("LapTandaTerima.pdf");
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
			</table><hr><p class="style4" align="center">Laporan Tanda Terima<br>Periode '.$dateFrom.' s/d '.$dateTo.'</p>
			';
		
	}
	
	function content($getData){
		$content = '<table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
		  <tr class="style7">
			<td>No</td>
			<td>Tanggal</td>
			<td>No TTB</td>
			<td>No PO</td>
			<td>Supplier</td>
			<td>Alamat</td>
			<td>Nama Barang</td>
			<td>Satuan</td>
			<td>Jumlah</td>
		  </tr>';
		$noSm	= '';$i=1;
		foreach($getData as $r){
			$tglTtb	= $r['tgl_ttb'];
			$noTtb	= $r['no_ttb'];
			$noPo	= $r['no_po'];
			$nmSup	= $r['nama_sup'];
			$nmBrg	= $r['nama_brg'];
			$satBrg	= $r['satuan'];
			$jmlBrg	= $r['jml_brg_sph'];
			$almSup	= $r['alamat_sup'];
			$content .= '<tr class="style7">';
			
			if($noTtb==$noSm){
				$content .='
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>';
			}else{
				$content .='
					<td>'.$i.'</td>
					<td>'.$tglTtb.'</td>
					<td>'.$noTtb.'</td>
					<td>'.$noPo.'</td>
					<td>'.$nmSup.'</td>
					<td>'.$almSup.'</td>';
				$i++;
			}
			
			$content .='<td>'.$nmBrg.'</td>
					<td>'.$satBrg.'</td>
					<td>'.$jmlBrg.'</td>
				  </tr>';
			$noSm=$noTtb;
		}
		
		
		$content .= '</table>';
		return $content;
	}
	
	function foot(){
		return '</body></html>';
	}
}