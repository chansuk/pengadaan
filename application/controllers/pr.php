<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pr extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_pr');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		$this->load->view('main/v_head');
		$this->load->view('v_pr',$data);
		
	}
	
	function getGridFpb(){
		$page 	= trim($this->input->get_post('page', true));
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$date1	= $this->input->get('date1',true);
		$date2	= $this->input->get('date2',true);
		$noFpb	= $this->input->get('noFpb',true);
		
		$whereDate = "";
		if ($date1 <>"" && $date2 <> "") {
			$whereDate = " AND tgl_fpb BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";
		};		
		if ($date1 <>"" && $date2 == "") {
			$whereDate = " AND tgl_fpb > '$date1 00:00:00'";
		};			
		if ($date1 =="" && $date2 <> "" ) {
			$whereDate = " AND tgl_fpb < '$date2 23:59:59'";
		};
		
		$result	= $this->m_pr->getGridFpb($date1,$date2,$noFpb,$whereDate,$page,$start,$limit);
		echo json_encode($result);
	}
	
	function getGridInfoFpb(){
		$noFpb	= $this->input->get('noFpb',true);
		
		$result	= $this->m_pr->getGridInfoFpb($noFpb);
		echo json_encode($result);
	}
	
	function getNoPr(){
		$noPr	= $this->m_pr->getNoPr();
		$result['no_pr'] = $noPr;
		echo json_encode($result);
	}
	
	function actSimpan(){
		$noPr		= $this->input->post('noPr',true);
		$idFpb		= $this->input->post('idFpb',true);
		
		$insertPr	= $this->m_pr->insertPr($noPr,$idFpb);
		echo json_encode($insertPr);
	}
	
	function getGridPrList(){
		$page 	= trim($this->input->get_post('page', true));
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$date1	= $this->input->get('date1',true);
		$date2	= $this->input->get('date2',true);
		$noPr	= $this->input->get('noPr',true);
		
		$whereDate = "";
		if ($date1 <>"" && $date2 <> "") {
			$whereDate = " AND tgl_pr BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";
		};		
		if ($date1 <>"" && $date2 == "") {
			$whereDate = " AND tgl_pr > '$date1 00:00:00'";
		};			
		if ($date1 =="" && $date2 <> "" ) {
			$whereDate = " AND tgl_pr < '$date2 23:59:59'";
		};
		
		$result	= $this->m_pr->getGridPrList($noPr,$whereDate,$page,$start,$limit);
		echo json_encode($result);
	}
	
	function getGridInfoPr(){
		$noPr	= $this->input->get('noPr',true);
		
		$result	= $this->m_pr->getGridInfoPr($noPr);
		echo json_encode($result);
	}
	
	function actAppFpb(){
		$noFpb	= $this->input->post('noFpb',true);
		$result	= $this->m_pr->actAppFpb($noFpb);
		echo json_encode($result);
	}
	
	function cetak(){
		$get 		= $this->uri->uri_to_assoc();
		$idPr		= $get['idPr'];
		
		$getPr		= $this->m_pr->getPr($idPr);
		$getBrgFpb	= $this->m_pr->getBrgFpb($idPr);
		
		$html	= $this->headHtml();
		$html	.= $this->html($getPr,$getBrgFpb);
		
		$html	.= $this->ttd();
		$html	.= $this->footHtml();
		$this->html2pdf->paper('a4', 'portrait');
		$this->html2pdf->filename("PurchaseRequest.pdf");
		$this->html2pdf->folder('./assets/pdfs/');
		$this->html2pdf->html($html);
		$this->html2pdf->create('download');
	}
	
	function html($getPr,$getBrgFpb){
		$content	= '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			  <tr>
				<td colspan="7"><div align="center"><span class="style3">PURCHASE REQUEST (PR)</span></div></td>
			  </tr>
			  <tr>
				<td colspan="7">&nbsp;</td>
			  </tr>
			  <tr>
				<td class="style4">Nomor</td>
				<td class="style4">:</td>
				<td colspan="5" class="style4">'.$getPr->no_pr.'</td>
			  </tr>
			  <tr>
				<td class="style4" width="10%">Tanggal</td>
				<td class="style4" width="1%">:</td>
				<td colspan="5" class="style4">'.$getPr->tanggal.'</td>
			  </tr>
			  <tr>
				<td class="style4" width="10%">No FPB</td>
				<td class="style4" width="1%">:</td>
				<td colspan="5" class="style4">'.$getPr->no_fpb.'</td>
			  </tr>
			  <tr>
				<td class="style4">Pool</td>
				<td class="style4">:</td>
				<td colspan="5" class="style4">'.$getPr->pool_pr.'</td>
			  </tr>
			  <tr>
				<td colspan="7">&nbsp;</td>
			  </tr>
			</table>
			<p class="style4">Bersama ini, kami mengajukan permintaan barang - barang tehnik sebagai berikut:</p>';
		$content	.= '<table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000">
					  <tr>
						<td class="style4" align="center">No.</td>
						<td class="style4" align="center">Kode Barang</td>
						<td class="style4" align="center">Part Number</td>
						<td class="style4" align="center">Nama Barang</td>
						<td class="style4" align="center">Satuan</td>
						<td class="style4" align="center">Stok</td>
						<td class="style4" align="center">Stok Min</td>
						<td class="style4" align="center">Stok Max</td>
						<td class="style4" align="center">Order</td>
					  </tr>';
		$i = 1;
		foreach($getBrgFpb as $r){
			$nmBrg	= $r['nama_brg'];
			$kdBrg	= $r['kode_brg'];
			$partNo	= $r['part_no'];
			$jmlBrg	= $r['jml_brg_fpb'];
			$satuan	= $r['satuan'];
			$stok	= $r['stok'];
			$stokMin= $r['stok_min'];
			$stokMax= $r['stok_max'];
			
			$content.= '<tr>
						<td class="style7">'.$i.'</td>
						<td class="style7">'.$kdBrg.'</td>
						<td class="style7">'.$partNo.'</td>
						<td class="style7">'.$nmBrg.'</td>
						<td class="style7">'.$satuan.'</td>
						<td class="style7">'.$stok.'</td>
						<td class="style7">'.$stokMin.'</td>
						<td class="style7">'.$stokMax.'</td>
						<td class="style7">'.$jmlBrg.'</td>
					  </tr>';
			$i++;
		}
		$content.= '</table>';
		return $content;
	}
	
	function headHtml(){
		$logoTb	= APPPATH.'../assets/img/logotb.png';
		return '<html>
			<style type="text/css">
			<!--
			.style1 {
				font-family: Arial, Helvetica, sans-serif;
				font-weight: bold;
				font-size: 24px;
			}
			.style3 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 14px;
				font-weight: bold;
			}
			.style4 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 10px;
				font-weight: bold;
			}
			.style7 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 8;
			}
			.style12 {font-size: 12}
			-->
			</style>
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
			</table><hr>';
	}
	
	function footHtml(){
		return '<p>&nbsp;</p> </body> </html>';
	}
	
	function ttd(){
		return '<p>&nbsp;</p>
		<table width="50%" cellpadding="5" cellspacing="0" bordercolor="#000000">
		  <tr>
			<td bordercolor="#FFFFFF" class="style4">Dibuat Oleh :</td>
			<td bordercolor="#FFFFFF" class="style4">Diajukan Oleh :</td>
		  </tr>
		  <tr>
			<td bordercolor="#000000" class="style4"><div align="left">Staff Logistik</div></td>
			<td bordercolor="#000000" class="style4">Kabag Logistik</td>
		  </tr>
		  <tr>
			<td bordercolor="#000000" class="style4">
			  <p>&nbsp;</p>
			  <p align="left">Yani</p>      </td>
			<td bordercolor="#000000" class="style4"><p>&nbsp;</p>
			<p>Kartiwa</p></td>
		  </tr>
		</table>
		<br><br>
		<table width="80%" cellpadding="5" cellspacing="0" bordercolor="#000000">
		  <tr>
			<td colspan="3" bordercolor="#FFFFFF" class="style4">Menyetujui :</td>
		  </tr>
		  <tr>
			<td bordercolor="#000000" class="style4"><div align="left">Kabag Teknik</div></td>
			<td bordercolor="#000000" class="style4">Kabag Gudang</td>
			<td bordercolor="#000000" class="style4">Manager Teknik</td>
		  </tr>
		  <tr>
			<td bordercolor="#000000" class="style4"><p align="left">&nbsp;</p>
			<p align="left">S. Waluyo</p></td>
			<td bordercolor="#000000" class="style4"><p>&nbsp;</p>
			<p>Idi Supriadi</p></td>
			<td bordercolor="#000000" class="style4"><p>&nbsp;</p>
			<p>H. Widya F Rahmat</p></td>
		  </tr>
		</table>';
	}
}