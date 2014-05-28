<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Po extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_po');
	}
	
	public function index(){
		if(!$this->auth_user->_isLogin()){
			redirect('login','location');
		}
		
		$data['user_desc']	= $this->session->userdata('user_desc');
		$this->load->view('main/v_head');
		$this->load->view('v_po',$data);
		
	}
	
	function getGridPr(){
		$start 	= trim($this->input->get_post('start', true));
		$limit 	= trim($this->input->get_post('limit', true));
		$date1	= $this->input->get('date1',true);
		$date2	= $this->input->get('date2',true);
		$noPr	= $this->input->get('noPr',true);
		
		$whereDate = "";
		if ($date1 <>"" && $date2 <> "") {
			$whereDate = " AND b.tgl_pr BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";
		};		
		if ($date1 <>"" && $date2 == "") {
			$whereDate = " AND b.tgl_pr > '$date1 00:00:00'";
		};			
		if ($date1 =="" && $date2 <> "" ) {
			$whereDate = " AND b.tgl_pr < '$date2 23:59:59'";
		};
		
		$result	= $this->m_po->getGridPr($noPr,$whereDate,$start,$limit);
		echo json_encode($result);
	}
	
	function getGridInfoPr(){
		$noPr	= $this->input->get('noPr',true);
		
		$result	= $this->m_po->getGridInfoPr($noPr);
		echo json_encode($result);
	}
	
	function getNoPo(){
		$noPo	= $this->m_po->getNoPo();
		$result['no_po'] = $noPo;
		echo json_encode($result);
	}
	
	function actSimpan(){
		$idPr	= $this->input->post('idPr',true);
		$idSph	= $this->input->post('idSph',true);
		$noPo	= $this->input->post('noPo',true);
		$result	= $this->m_po->actSimpan($idPr,$idSph,$noPo);
		echo json_encode($result);
	}
	
	function actApprove(){
		$noPr	= $this->input->post('noPr',true);
		$result	= $this->m_po->actApprove($noPr);
		echo json_encode($result);
	}
	
	function cetak(){
		$get 		= $this->uri->uri_to_assoc();
		$idPo		= $get['idPo'];
		
		$getPo		= $this->m_po->getPo($idPo);
		$getBrgSph	= $this->m_po->getBrgSph($idPo);
		//print_r($getBrgSph);exit;
		$html	= $this->headHtml();
		$html	.= $this->html($getPo,$getBrgSph);
		
		$html	.= $this->ttd();
		$html	.= $this->footHtml();
		$this->html2pdf->paper('a4', 'portrait');
		$this->html2pdf->filename("PurchaseOrder.pdf");
		$this->html2pdf->folder('./assets/pdfs/');
		$this->html2pdf->html($html);
		$this->html2pdf->create('download');
	}
	
	function html($getPo,$getBrgSph){
		$content	= '<table width="100%" border="0" cellspacing="0" cellpadding="3">
		  <tr>
			<td colspan="7"><div align="center"><span class="style3">PURCHASE ORDER (PO)<br>
			</span></div></td>
		  </tr>
		  <tr>
			<td colspan="5" class="style4"><div align="right">Date</div></td>
			<td class="style4">:</td>
			<td class="style4">'.$getPo->tanggal.'</td>
		  </tr>
		  <tr>
			<td colspan="5" class="style4"><div align="right">PO No</div></td>
			<td class="style4">:</td>
			<td class="style4">'.$getPo->no_po.'</td>
		  </tr>
		  <tr>
			<td colspan="5" class="style4"><div align="right">Validity</div></td>
			<td class="style4">:</td>
			<td class="style4">Dua Minggu</td>
		  </tr>
		  
		  <tr>
			<td colspan="7">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan="3" class="style4">SUPPLIER</td>
			<td class="style4">&nbsp;</td>
			<td colspan="3" class="style4">CUSTOMER</td>
		  </tr>
		  <tr>
			<td class="style4" width="10%">Company</td>
			<td class="style4" width="1%">:</td>
			<td class="style4">'.$getPo->nama_sup.'</td>
			<td class="style4">&nbsp;</td>
			<td class="style4" width="10%">Company</td>
			<td class="style4" width="1%">:</td>
			<td class="style4">PT. Trans Batavia</td>
		  </tr>
		  <tr>
			<td class="style4">Address</td>
			<td class="style4">:</td>
			<td class="style4">'.$getPo->alamat_sup.'</td>
			<td class="style4">&nbsp;</td>
			<td class="style4">Address</td>
			<td class="style4">:</td>
			<td class="style4">Jl. Perintis Kemerdekaan No. 1<br>Jakarta Timur</td>
		  </tr>
		  <tr>
			<td class="style4">Telepon</td>
			<td class="style4">:</td>
			<td class="style4">'.$getPo->telpon_sup.'</td>
			<td class="style4">&nbsp;</td>
			<td class="style4">Telepon</td>
			<td class="style4">:</td>
			<td class="style4">021-4703350</td>
		  </tr>
		  <tr>
			<td class="style4">&nbsp;</td>
			<td class="style4">&nbsp;</td>
			<td class="style4">&nbsp;</td>
			<td class="style4">&nbsp;</td>
			<td class="style4">Fax</td>
			<td class="style4">:</td>
			<td class="style4">021-47861782</td>
		  </tr>
		  <tr>
			<td class="style4">&nbsp;</td>
			<td class="style4">&nbsp;</td>
			<td class="style4">&nbsp;</td>
			<td class="style4">&nbsp;</td>
			<td class="style4">NPWP</td>
			<td class="style4">:</td>
			<td class="style4">02.463.991.6.005.000</td>
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
						<td class="style4" align="center">Total(Rp)</td>
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
						<td class="style7" colspan="7" align="right">Grand Total</td>
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
	
	function ttd(){
		return '<p>&nbsp;</p>
		<table width="80%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000">
  <tr>
    <td bordercolor="#FFFFFF" class="style4">Dibuat Oleh :</td>
    <td colspan="2" bordercolor="#FFFFFF" class="style4">Mengetahui :</td>
  </tr>
  <tr>
    <td bordercolor="#000000" class="style4">
    <p>&nbsp;</p>      
    <p align="left" ><span  style="text-decoration:underline">Tumiar S</span><br>
    Staff Purchasing</p></td>
    <td bordercolor="#000000" class="style4"><p>&nbsp;</p>      
      <p><span  style="text-decoration:underline">Sendi I ST</span><br>
    Manager Purchasing</p></td>
    <td bordercolor="#000000" class="style4"><p>&nbsp;</p>
    <p ><span  style="text-decoration:underline">Bambang Sudibyo</span><br>
    Manager Keuangan</p></td>
  </tr>
</table>
<br><br>
<table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000">
  <tr>
    <td colspan="4" bordercolor="#FFFFFF" class="style4"><div align="center">Disetujui :</div></td>
  </tr>
  <tr>
    <td bordercolor="#000000" class="style4"><p align="left">&nbsp;</p><br>
      <span style="text-decoration:underline">Fauji Tanudjaja</span><br>
    Direktur PUM</td>
    <td bordercolor="#000000" class="style4"><p>&nbsp;</p><br>    
      <span style="text-decoration:underline">Jabes Sihombing</span><br>
    Direktur Operasi</td>
    <td bordercolor="#000000" class="style4"><p>&nbsp;</p><br>     
      <span style="text-decoration:underline">H. Mamat Surachmat</span><br>
    Direktur Keuangan</td>
    <td bordercolor="#000000" class="style4"><p>&nbsp;</p><br>
    <span style="text-decoration:underline">H Azis Reismaya M</span><br>
    Direktur Utama</td>
  </tr>
</table>';
	}
}