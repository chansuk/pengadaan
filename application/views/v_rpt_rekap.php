<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';

Ext.onReady(function(){
	
	var lapSrc = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'dateFrom',
				id			:'dateFrom',
				format		:'d-m-Y',
				value		:new Date(),
				width		:200,
				fieldLabel	:'Tanggal'
			},{
				xtype		:'datefield',
				name		:'dateTo',
				id			:'dateTo',
				format		:'d-m-Y',
				margin		:'0 0 0 10',
				value		:new Date(),
				width		:100,
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Print Laporan',
			margin		:'5 5 5 105',
			handler		:actPrint
		}]
	});
	
	var lapForm = Ext.create('Ext.form.Panel', {
		title	:'Laporan Rekap Permintaan Barang',
		id		:'lapForm',
		margin	:'10',
		layout	:'anchor',
		items	:[lapSrc],
		renderTo: Ext.getBody(),
	});
	
	function actPrint(){
		var dateFrom 	= Ext.getCmp('dateFrom').getSubmitValue();
		var dateTo 		= Ext.getCmp('dateTo').getSubmitValue();
		if(dateFrom==''||dateTo==''){
			Ext.Msg.alert('Informasi', 'Silahkan isikan tanggal !');
		}else{
			var	paramExp	= 'rpt_rekap/cetak/dateFrom/'+dateFrom+'/dateTo/'+dateTo;
			window.open(baseUrl + paramExp, "_blank");
		}
	}
	
});
</script>