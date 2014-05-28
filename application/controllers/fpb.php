<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fpb extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_fpb');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		$this->load->view('main/v_head');
		$this->load->view('v_fpb',$data);
		
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
		
		$result	= $this->m_fpb->getGridFpb($noFpb,$whereDate,$start,$limit);
		echo json_encode($result);
	}
	
	function getGridMr(){
		$page 	= trim($this->input->get_post('page', true));
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$date1	= $this->input->get('date1',true);
		$date2	= $this->input->get('date2',true);
		$noMr	= $this->input->get('noMr',true);
		
		$whereDate = "";
		if ($date1 <>"" && $date2 <> "") {
			$whereDate = " AND tgl_mr BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";
		};		
		if ($date1 <>"" && $date2 == "") {
			$whereDate = " AND tgl_mr > '$date1 00:00:00'";
		};			
		if ($date1 =="" && $date2 <> "" ) {
			$whereDate = " AND tgl_mr < '$date2 23:59:59'";
		};
		
		$result	= $this->m_fpb->getGridMr($date1,$date2,$noMr,$whereDate,$page,$start,$limit);
		echo json_encode($result);
	}
	
	function getGridInfoMr(){
		$noMr	= $this->input->get('noMr',true);
		$type	= $this->input->get('type',true);
		
		$result	= $this->m_fpb->getGridInfoMr($noMr,$type);
		echo json_encode($result);
	}
	
	function getGridCetak(){
		$noFpb	= $this->input->get('noFpb',true);
		$result	= $this->m_fpb->getGridCetak($noFpb);
		echo json_encode($result);
	}
	
	function getNoFpb(){
		$noFpb	= $this->m_fpb->getNoFpb();
		$result['no_fpb'] = $noFpb;
		echo json_encode($result);
	}
	
	function checkColBrg(){
		$idMr	= $this->input->post('idMr',true);
		$idBrg	= $this->input->post('idBrg',true);
		$status	= $this->input->post('status',true);
		
		$actCheck	= $this->m_fpb->checkColBrg($idMr,$idBrg,$status);
		echo json_encode($actCheck);
	}
	
	function actProses(){
		$idMr		= $this->input->post('idMr',true);
		$noMr		= $this->input->post('noMr',true);
		$actProses	= $this->m_fpb->actProses($idMr,$noMr);
		echo json_encode($actProses);
	}
	
	function actSetuju(){
		$idMr		= $this->input->post('idMr',true);
		$noMr		= $this->input->post('noMr',true);
		$brgData	= $this->input->post('brgData',true);
		$actSetuju	= $this->m_fpb->actSetuju($idMr,$noMr,$brgData);
		echo json_encode($actSetuju);
	}
	
	function actSmpFpb(){
		$noFpb		= trim($this->input->post('noFpb'));
		$noMr		= trim($this->input->post('noMr'));
		$brgData	= $this->input->post('brgData');
		
		$insertFpb	= $this->m_fpb->insertFpb($noFpb,$noMr,$brgData);
		echo json_encode($insertFpb);
	}
	
	function cetak(){
		$get 		= $this->uri->uri_to_assoc();
		$idFpb		= $get['idFpb'];
		//$idFpb		= 6;
		
		$getFpb		= $this->m_fpb->getFpb($idFpb);
		$getBrgFpb	= $this->m_fpb->getBrgFpb($idFpb);
		
		$html	= $this->headHtml();
		$html	.= $this->html($getFpb,$getBrgFpb);
		
		$html	.= $this->ttd();
		$html	.= $this->footHtml();
		$this->html2pdf->paper('a4', 'portrait');
		$this->html2pdf->filename("PermintaanBarang.pdf");
		$this->html2pdf->folder('./assets/pdfs/');
		$this->html2pdf->html($html);
		$this->html2pdf->create('download');
	}
	
	function html($getFpb,$getBrgFpb){
		$content	= '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			  <tr>
				<td colspan="7"><div align="center"><span class="style3">PERMINTAAN BARANG TEHNIK</span></div></td>
			  </tr>
			  <tr>
				<td colspan="7">&nbsp;</td>
			  </tr>
			  <tr>
				<td class="style4" width="100">Nomor</td>
				<td class="style4">:</td>
				<td colspan="5" class="style4">'.$getFpb->no_fpb.'</td>
			  </tr>
			  <tr>
				<td class="style4" width="10%">Tanggal</td>
				<td class="style4" width="1%">:</td>
				<td colspan="5" class="style4">'.$getFpb->tanggal.'</td>
			  </tr>
			  <tr>
				<td class="style4">No Referensi MR</td>
				<td class="style4">:</td>
				<td colspan="5" class="style4">'.$getFpb->no_mr.'</td>
			  </tr>
			  <tr>
				<td colspan="7">&nbsp;</td>
			  </tr>
			</table>
			<p>&nbsp;</p>Bersama ini, kami mengajukan permintaan barang - barang teknik untuk penambahan stok gudang:<p>&nbsp;</p>';
		$content	.= '<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-color:#000000">
					  <tr>
						<td class="style4" align="center">No.</td>
						<td class="style4" align="center">Kode Barang</td>
						<td class="style4" align="center">Part Number</td>
						<td class="style4" align="center">Nama Barang</td>
						<td class="style4" align="center">Satuan</td>
						<td class="style4" align="center">Stok Min</td>
						<td class="style4" align="center">Stok Max</td>
						<td class="style4" align="center">Stok</td>
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
				font-size: 18px;
				font-weight: bold;
			}
			.style4 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 14px;
				font-weight: bold;
			}
			.style7 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 10;
			}
			.style12 {font-size: 12}
			-->
			</style>
			<body>
			<table width="100%" border="0" cellspacing="0" cellpadding="4">
			  <tr>
				<td width="25%" valign="middle"><span class="style1"><img src="'.$logoTb.'" width="155" height="144"></span></td>
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
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		  <tr>
			<td class="style4"><div align="center">Dibuat Oleh:<br>Staf Gudang</div></td>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center">Diajukan Oleh:<br>Kabag Gudang</div></td>
		  </tr>
		  <tr>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
		  </tr>
		  <tr>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
		  </tr>
		  <tr>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
		  </tr>
		  <tr>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
		  </tr>
		  <tr>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
		  </tr>
		  <tr>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center"></div></td>
		  </tr>
		  <tr>
			<td class="style4"><div align="center">Deni FJD</div></td>
			<td class="style4"><div align="center">&nbsp;</div></td>
			<td class="style4"><div align="center">Idi Supriadi</div></td>
		  </tr>
		</table>';
	}
}