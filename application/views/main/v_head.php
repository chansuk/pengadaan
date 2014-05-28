<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sistem Pengadaan Barang</title>
    <!-- Ext -->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/extjs/resources/css/ext-all.css" />
    <script type="text/javascript" src="<?=base_url()?>assets/extjs/ext-all.js"></script>
	 
    <!-- example code -->

    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/layout-browser.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/extjs/ux/css/CheckHeader.css" />
	
    <script type="text/javascript"> 
		Ext.Loader.setConfig({enabled: true});

		Ext.Loader.setPath('Ext.ux', "<?=base_url()?>assets/extjs/ux");

		Ext.require([
			'Ext.tip.QuickTipManager',
			'Ext.container.Viewport',
			'Ext.layout.*',
			'Ext.form.Panel',
			'Ext.form.Label',
			'Ext.grid.*',
			'Ext.data.*',
			'Ext.tree.*',
			'Ext.selection.*',
			'Ext.tab.Panel',
			'Ext.ux.layout.Center',
			'Ext.ux.CheckColumn', 
			'Ext.util.*',
		]);
		
		
	</script>
</head>
<body>
<script type="text/javascript">
	var baseUrl		= '<?=base_url()?>index.php/';
	var loadingMask = new Ext.LoadMask(Ext.getBody(),{msg:"Loading..."});
</script>