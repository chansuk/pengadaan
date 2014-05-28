<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mr extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_mr');
		$this->load->library('html2pdf');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		$this->load->view('main/v_head');
		$this->load->view('v_mr',$data);
		
	}
	
	function getNoMr(){
		$noMr	= $this->m_mr->getNoMr();
		$result['no_mr'] = $noMr;
		echo json_encode($result);
	}
	
	function getNmBrg(){
		$nmBrg	= $this->input->get('query');
		$getBrg	= $this->m_mr->getNmBrg($nmBrg);
		echo json_encode($getBrg);
	}
	
	function actSimpan(){
		$noBody		= strtoupper(trim($this->input->post('noBody')));
		$noMr		= trim($this->input->post('noMr'));
		$brgData	= $this->input->post('brgData');

		$insertMr	= $this->m_mr->insertMr($noBody,$noMr,$brgData);
		echo json_encode($insertMr);
	}
	
	function getGridMrSrc(){
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
		
		$result	= $this->m_mr->getGridMrSrc($noMr,$whereDate,$start,$limit);
		echo json_encode($result);
	}
	
	function getGridInfoMr(){
		$noMr	= $this->input->get('noMr',true);
		
		$result	= $this->m_mr->getGridInfoMr($noMr);
		echo json_encode($result);
	}
	
	function cetak(){
		$get 		= $this->uri->uri_to_assoc();
		$idMr		= $get['idMr'];
		
		$getMr		= $this->m_mr->getMr($idMr);
		$getBrgMr	= $this->m_mr->getBrgMr($idMr);
		
		$html	= $this->headHtml();
		$html	.= $this->html($getMr,$getBrgMr);
		
		$html	.= $this->ttd();
		$html	.= $this->footHtml();
		$this->html2pdf->paper('a4', 'portrait');
		$this->html2pdf->filename("MaterialRequistion.pdf");
		$this->html2pdf->folder('./assets/pdfs/');
		$this->html2pdf->html($html);
		$this->html2pdf->create('download');
	}
	
	function html($mrDat,$brgDat){
		$content	= '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			  <tr>
				<td colspan="7"><div align="center"><span class="style3">MATERIAL REQUISITION
				(MR)</span></div></td>
			  </tr>
			  <tr>
				<td colspan="7">&nbsp;</td>
			  </tr>
			  <tr>
				<td class="style4" width="10%">No MR</td>
				<td class="style4" width="1%">:</td>
				<td colspan="5" class="style4">'.$mrDat->no_mr.'</td>
			  </tr>
			  <tr>
				<td class="style4" width="10%">Tanggal</td>
				<td class="style4" width="1%">:</td>
				<td colspan="5" class="style4">'.$mrDat->tanggal.'</td>
			  </tr>
			  <tr>
				<td class="style4">No Body</td>
				<td class="style4">:</td>
				<td colspan="5" class="style4">'.$mrDat->id_bus.'</td>
			  </tr>
			  <tr>
				<td class="style4">Koridor</td>
				<td class="style4">:</td>
				<td colspan="5" class="style4">'.$mrDat->koridor_bus.'</td>
			  </tr>
			  <tr>
				<td colspan="7">&nbsp;</td>
			  </tr>
			</table>
			<p>&nbsp;</p>';
		$content	.= '<table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-color:#000000">
					  <tr>
						<td class="style4" align="center">No.</td>
						<td class="style4" align="center">Kode Barang</td>
						<td class="style4" align="center">Nama Barang</td>
						<td class="style4" align="center">Part Number</td>
						<td class="style4" align="center">Jumlah</td>
						<td class="style4" align="center">Satuan</td>
					  </tr>';
		$i = 1;
		foreach($brgDat as $r){
			$nmBrg	= $r['nama_brg'];
			$kdBrg	= $r['kode_brg'];
			$partNo	= $r['part_no'];
			$jmlBrg	= $r['jml_brg_mr'];
			$satuan	= $r['satuan'];
			//echo $satuan;exit;
			$content.= '<tr>
						<td class="style7">'.$i.'</td>
						<td class="style7">'.$kdBrg.'</td>
						<td class="style7">'.$nmBrg.'</td>
						<td class="style7">'.$partNo.'</td>
						<td class="style7">'.$jmlBrg.'</td>
						<td class="style7">'.$satuan.'</td>
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
			<td class="style4"><div align="center">Dibuat Oleh:<br>Staf Tehnik</div></td>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center">Diajukan Oleh:<br>Kabag Tehnik</div></td>
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
			<td class="style4"><div align="center">Asep D</div></td>
			<td class="style4"><div align="center">&nbsp;</div></td>
			<td class="style4"><div align="center">S Waluyo</div></td>
		  </tr>
		</table>';
	}
}