<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ttb extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_ttb');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$this->load->view('main/v_head');
		$this->load->view('v_ttb'); 
		
	}
	
	function getGridPo(){
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$date1	= $this->input->get('date1',true);
		$date2	= $this->input->get('date2',true);
		$noPo	= $this->input->get('noPo',true);
		
		$whereDate = "";
		if ($date1 <>"" && $date2 <> "") {
			$whereDate = " AND tgl_po BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";
		};		
		if ($date1 <>"" && $date2 == "") {
			$whereDate = " AND tgl_po > '$date1 00:00:00'";
		};			
		if ($date1 =="" && $date2 <> "" ) {
			$whereDate = " AND tgl_po < '$date2 23:59:59'";
		};
		
		$result	= $this->m_ttb->getGridPo($noPo,$whereDate,$start,$limit);
		echo json_encode($result);
	}
	
	function getNoTtb(){
		$noTtb	= $this->m_ttb->getNoTtb();
		$result['no_ttb'] = $noTtb;
		echo json_encode($result);
	}
	
	function getGridTtb(){
		$noPo	= $this->input->get('noPo',true);
		$result	= $this->m_ttb->getGridTtb($noPo);
		echo json_encode($result);
	}
	
	function actSimpan(){
		$noTtb	= $this->input->post('noTtb',true);
		$sj		= $this->input->post('sj',true);
		$ketTtb	= $this->input->post('ketTtb',true);
		$idPo	= $this->input->post('idPo',true);
		
		$result	= $this->m_ttb->actSimpan($noTtb,$sj,$ketTtb,$idPo);
		echo json_encode($result);
	}
	
	function cetak(){
		$get 		= $this->uri->uri_to_assoc();
		$idTtb		= $get['idTtb'];
		
		$getTtb		= $this->m_ttb->getTtb($idTtb);
		$getBrgSph	= $this->m_ttb->getBrgSph($idTtb);
		
		$html	= $this->headHtml();
		$html	.= $this->html($getTtb,$getBrgSph);
		
		$html	.= $this->ttd($getTtb->ket_ttb);
		$html	.= $this->footHtml();
		$this->html2pdf->paper('a4', 'portrait');
		$this->html2pdf->filename("TandaTerima.pdf");
		$this->html2pdf->folder('./assets/pdfs/');
		$this->html2pdf->html($html);
		$this->html2pdf->create('download');
	}
	
	function html($getTtb,$getBrgSph){
		$content	= '<table width="100%" border="0" cellspacing="0" cellpadding="3">
		  <tr>
			<td colspan="7"><div align="center"><span class="style3">TANDA TERIMA<br>
			</span></div></td>
		  </tr>
		
		  <tr>
			<td class="style4" width="10%">Terima Dari</td>
			<td class="style4" width="1%">:</td>
			<td class="style4">'.$getTtb->nama_sup.'</td>
			<td class="style4">&nbsp;</td>
			<td class="style4" width="10%">No PO</td>
			<td class="style4" width="1%">:</td>
			<td class="style4">'.$getTtb->no_po.'</td>
		  </tr>
		  <tr>
			<td class="style4">Nomor</td>
			<td class="style4">:</td>
			<td class="style4">'.$getTtb->no_ttb.'</td>
			<td class="style4">&nbsp;</td>
			<td class="style4">Tanggal</td>
			<td class="style4">:</td>
			<td class="style4">'.$getTtb->tanggal.'</td>
		  </tr>
		  
		  <tr>
			<td colspan="7">&nbsp;</td>
		  </tr>
		</table>
			<p class="style4">&nbsp;</p>';
		$content	.= '<table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000">
					  <tr>
						<td class="style4" align="center">No.</td>
						<td class="style4" align="center">Kode Barang</td>
						<td class="style4" align="center">Nama Barang</td>
						<td class="style4" align="center">Part Number</td>
						<td class="style4" align="center">Qty</td>
						<td class="style4" align="center">Unit</td>
						<td class="style4" align="center">Price(Rp)</td>
						<td class="style4" align="center">Total Price(Rp)</td>
					  </tr>';
		$i = 1;
		$grandTot = 0;
		foreach($getBrgSph as $r){
			$nmBrg	= $r['nama_brg'];
			$kdBrg	= $r['kode_brg'];
			$partNo	= $r['part_no'];
			$jmlBrg	= $r['jml_brg_sph'];
			$satuan	= $r['satuan'];
			$harga	= $r['harga_brg_sph'];
			
			$content.= '<tr>
						<td class="style7">'.$i.'</td>
						<td class="style7">'.$kdBrg.'</td>
						<td class="style7">'.$nmBrg.'</td>
						<td class="style7">'.$partNo.'</td>
						<td class="style7">'.$jmlBrg.'</td>
						<td class="style7">'.$satuan.'</td>
						<td class="style7">'.number_format($harga).'</td>
						<td class="style7">'.number_format($harga*$jmlBrg).'</td>
					  </tr>';
			$grandTot	= $grandTot + ($harga*$jmlBrg);
			$i++;
		}
		$content.= '<tr>
						<td class="style7" colspan="7" align="right">Total</td>
						<td class="style7">'.number_format($grandTot).'</td>
					  </tr>';
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
	
	function ttd($ketTtb){
		return '<p>&nbsp;</p>
		<p class="style4">Catatan :&nbsp;'.$ketTtb.'</p>
		<hr>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		  <tr>
			<td class="style4"><div align="center">Penerima</div></td>
			<td class="style4"><div align="center"></div></td>
			<td class="style4"><div align="center">Pengirim</div></td>
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
			<td class="style4"><div align="center">Kartiwa<br>Kabag Logistik</div></td>
			<td class="style4"><div align="center">&nbsp;</div></td>
			<td class="style4"><div align="center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</div></td>
		  </tr>
		</table>';
	}
}