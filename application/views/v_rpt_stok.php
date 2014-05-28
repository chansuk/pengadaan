<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';

Ext.onReady(function(){
	
	var lapSrc = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'button',
			width		:205,
			text		:'Print Laporan',
			margin		:'5 5 5 105',
			handler		:actPrint
		}]
	});
	
	var lapForm = Ext.create('Ext.form.Panel', {
		title	:'Laporan Stok Barang',
		id		:'lapForm',
		margin	:'10',
		layout	:'anchor',
		items	:[lapSrc],
		renderTo: Ext.getBody(),
	});
	
	function actPrint(){
		
		var	paramExp	= 'rpt_stok/cetak';
		window.open(baseUrl + paramExp, "_blank");
		
	}
	
});
</script>