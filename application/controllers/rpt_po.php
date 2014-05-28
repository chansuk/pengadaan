<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_po extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_rpt_po');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		
		
		$this->load->view('main/v_head');
		$this->load->view('v_rpt_po',$data);
		
	}
	
	function cetak(){
		$get 		= $this->uri->uri_to_assoc();
		$dateFrom	= $get['dateFrom'];
		$dateTo		= $get['dateTo'];
		
		$getData	= $this->m_rpt_po->getData($dateFrom,$dateTo);
		
		$html	= $this->head($dateFrom,$dateTo);
		$html	.= $this->content($getData);
		$html	.= $this->foot();
		//exit;
		$this->html2pdf->paper('a4', 'portrait');
		$this->html2pdf->filename("LapPurchaseOrder.pdf");
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
			</table><hr><p class="style4" align="center">Laporan Purchase Order<br>Periode '.$dateFrom.' s/d '.$dateTo.'</p>
			';
		
	}
	
	function content($getData){
		$content = '<table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
		  <tr class="style7">
			<td>No</td>
			<td>Tanggal</td>
			<td>No PO</td>
			<td>Kode Barang</td>
			<td>Nama Barang</td>
			<td>Satuan</td>
			<td>Order</td>
			<td>Harga</td>
			<td>Total</td>
		  </tr>';
		$noSm	= '';$i=1;$gTotal=0;
		foreach($getData as $r){
			$tglPo	= $r['tgl_po'];
			$noPo	= $r['no_po'];
			$kdBrg	= $r['kode_brg'];
			$nmBrg	= $r['nama_brg'];
			$satBrg	= $r['satuan'];
			$total	= $r['total'];
			$harga	= $r['harga_brg_sph'];
			$jmlBrg	= $r['jml_brg_sph'];
			$content .= '<tr class="style7">';
			
			if($noPo==$noSm){
				$content .='
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>';
			}else{
				$content .='
					<td>'.$i.'</td>
					<td>'.$tglPo.'</td>
					<td>'.$noPo.'</td>';
				$i++;
			}
			
			$content .='<td>'.$kdBrg.'</td>
					<td>'.$nmBrg.'</td>
					<td>'.$satBrg.'</td>
					<td>'.$jmlBrg.'</td>
					<td align="right">'.number_format($harga).'</td>
					<td align="right">'.number_format($total).'</td>
				  </tr>';
			$gTotal = (int)$gTotal + (int)$total;
			$noSm=$noPo;
		}
		
		$content .= '<tr class="style7">
			<td colspan="8" align="right">Total</td>
			<td align="right">'.number_format($gTotal).'</td>
		  </tr>';
		$content .= '</table>';
		return $content;
	}
	
	function foot(){
		return '</body></html>';
	}
}