<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';
Ext.onReady(function(){
	var storeGridFpb = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'pr/getGridFpb',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:[
			{name:'no_fpb',type: 'string'},
			{name:'tgl_fpb',type: 'string'},
			{name:'status_fpb',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('dateFpb1').getValue();
				date2	= Ext.getCmp('dateFpb2').getValue();
				noFpb	= Ext.getCmp('noFpb').getValue();
				
				store.proxy.extraParams.date1	= date1;
				store.proxy.extraParams.date2	= date2;
				store.proxy.extraParams.noFpb	= noFpb;
			} 
		}
	});
	
	var storeGridInfoFpb = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'pr/getGridInfoFpb',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:[
			{name:'kode_brg',type: 'string'},
			{name:'nama_brg',type: 'string'},
			{name:'part_no',type: 'string'},
			{name:'satuan',type: 'string'},
			{name:'jml_brg_fpb',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				noFpb	= Ext.getCmp('noFpbDialog').getValue();
				store.proxy.extraParams.noFpb	= noFpb;
			} 
		}
	});
	
	var storeGridPr = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'pr/getGridInfoFpb',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:[
			{name:'kode_brg',type: 'string'},
			{name:'nama_brg',type: 'string'},
			{name:'part_no',type: 'string'},
			{name:'satuan',type: 'string'},
			{name:'jml_brg_fpb',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				noFpb	= Ext.getCmp('hNoFpb').getValue();
				store.proxy.extraParams.noFpb	= noFpb;
			} 
		}
	});
	
	var storeGridPrList = Ext.create('Ext.data.JsonStore', {  
		autoLoad : true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'pr/getGridPrList',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:['no_pr','pool_pr','tgl_pr','status_pr','no_fpb'],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('datePr1').getValue();
				date2	= Ext.getCmp('datePr1').getValue();
				noPr	= Ext.getCmp('noPr').getValue();
				
				store.proxy.extraParams.date1	= date1;
				store.proxy.extraParams.date2	= date2;
				store.proxy.extraParams.noPr	= noPr;
			} 
		}
	});
	
	
	var storeGridInfoPr = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'pr/getGridInfoPr',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
			}
		},
		fields 	:[
			{name:'kode_brg',type: 'string'},
			{name:'nama_brg',type: 'string'},
			{name:'part_no',type: 'string'},
			{name:'satuan',type: 'string'},
			{name:'jml_brg_fpb',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				noPr	= Ext.getCmp('noPrPop').getValue();
				store.proxy.extraParams.noPr	= noPr;
			} 
		}
	});
	
	var searchForm = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noFpb',
			id			:'noFpb',
			width		:310,
			fieldLabel	:'No FPB'
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'dateFpb1',
				id			:'dateFpb1',
				width		:200,
				fieldLabel	:'Tanggal FPB'
			},{
				xtype		:'datefield',
				name		:'dateFpb2',
				id			:'dateFpb2',
				width		:100,
				margin		:'0 0 0 10'
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridFpb.load();
			}
		}]
	});
	
	var gridFpb = Ext.create('Ext.grid.Panel', {
		store	:storeGridFpb,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'no_fpb',
			header		:'No FPB',
			width		:125
		},{ 
			dataIndex	:'tgl_fpb',
			header		:'Tanggal',
			width		:125
		},{ 
			dataIndex	:'status_fpb',
			header		:'Status FPB',
		}],
		height	:160,
		//width	:460,
		bbar:{
			xtype	: 'pagingtoolbar',
			id 		: 'toolbargrid',
			store 	: storeGridFpb,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},
	});
	
	var listFpb = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		//width	:480,
		height	:200,
		padding	:'5',
		title	:'<b>List Permintaan Barang</b>',
		items	:[gridFpb]
	}
	
	var prPanel = Ext.create('Ext.form.Panel', {
		title	:'List Permintaan Barang',
        //width	:500,
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchForm,listFpb,{
			xtype		:'button',
			width		:100,
			text		:'Info Detil FPB',
			margin		:'0 5 5 10',
			handler		:function(){
				actInfoFpb('fpb');
			}
		},{
			xtype		:'button',
			width		:100,
			text		:'Buat PR',
			margin		:'0 5 5 0',
			handler		:actBuatPr
		}],
		//renderTo: Ext.getBody()
	});
	
	var gridPrList = Ext.create('Ext.grid.Panel', {
		store	:storeGridPrList,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'no_pr',
			header		:'No PR',
			width		:125
		},{ 
			dataIndex	:'no_fpb',
			header		:'No FPB',
		},{ 
			dataIndex	:'pool_pr',
			header		:'Pool',
		},{ 
			dataIndex	:'tgl_pr',
			header		:'Tanggal',
			width		:125
		},{ 
			dataIndex	:'status_pr',
			header		:'Status PR',
		}],
		height	:160,
		//width	:480
	});
	
	var listPr = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		//width	:480,
		height	:200,
		padding	:'5',
		title	:'<b>List Purchase Request</b>',
		items	:[gridPrList]
	}
	
	var searchPr = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noPr',
			id			:'noPr',
			width		:310,
			fieldLabel	:'No PR'
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'datePr1',
				id			:'datePr1',
				width		:200,
				fieldLabel	:'Tanggal PR'
			},{
				xtype		:'datefield',
				name		:'datePr2',
				id			:'datePr2',
				width		:100,
				margin		:'0 0 0 10'
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridPrList.load();
			}
		}]
	});
	
	var prList = Ext.create('Ext.form.Panel', {
		title	:'List Purchase Request',
        //width	:500,
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchPr,listPr,{
			xtype		:'button',
			width		:100,
			text		:'Cetak PR',
			margin		:'0 5 5 10',
			handler		:actCetakShow
		},{
			xtype		:'button',
			width		:100,
			text		:'Approve FPB',
			margin		:'0 5 5 10',
			handler		:actAppFpb
		}]
	});
	
	var prTab = Ext.create('Ext.tab.Panel', {
		id			:'prTab',
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[prPanel,prList],
		renderTo	: Ext.getBody()
	});
	
	
	var gridInfoFpb = Ext.create('Ext.grid.Panel', {
		store	:storeGridInfoFpb,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'kode_brg',
			header		:'Kode Barang',
		},{ 
			dataIndex	:'nama_brg',
			header		:'Nama Barang',
			width		:125
		},{ 
			dataIndex	:'part_no',
			header		:'Part No',
		},{ 
			dataIndex	:'satuan',
			header		:'Satuan',
		},{ 
			dataIndex	:'jml_brg_fpb',
			header		:'Jumlah',
		}],
		height	:160,
		width	:480
	});
	
	var infoFpbField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'noFpbDialog',
			name 		:'noFpbDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No FPB',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'tglDialog',
			name 		:'tglDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Tanggal FPB',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Barang</b>',
			items	:[gridInfoFpb]
		},{
			xtype		:'button',
			width		:100,
			id			:'btnAppFpb',
			text		:'Approve FPB',
			margin		:'0 5 5 10',
			handler		:actApp
		}]
	});
	
	var infoFpbDialog = new Ext.Window({
        id 		:'dialogCari', 	
		title	:'Info Detil Permintaan Barang',
		width	:550,
		height	:360,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[infoFpbField],
		listeners: {
			close : {
				fn: function(){ infoFpbField.getForm().reset(); }   
			}					
		} 
	});
	
	var gridPr = Ext.create('Ext.grid.Panel', {
		store	:storeGridPr,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'kode_brg',
			header		:'Kode Barang',
		},{
			dataIndex	:'nama_brg',
			header		:'Nama Barang',
		},{ 
			dataIndex	:'part_no',
			header		:'Part No',
		},{ 
			dataIndex	:'satuan',
			header		:'Satuan',
		},{ 
			dataIndex	:'jml_brg_fpb',
			header		:'Jumlah',
		}],
		height	:160,
		width	:480
	});
	
	var prField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'hNoFpb',
			name 		:'hNoFpb',
			hidden		:true
		},{
			xtype		:'textfield',
			id   		:'noPrDialog',
			name 		:'noPrDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No PR',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'poolDialog',
			name 		:'poolDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Pool',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Permintaan Barang</b>',
			items	:[gridPr]
		},{
			xtype		:'button',
			width		:100,
			text		:'Simpan',
			margin		:'0 5 5 10',
			handler		:actSimpan
		}]
	});
	
	var prDialog = new Ext.Window({
        id 		:'prDialog', 	
		title	:'Form Purchase Request',
		width	:550,
		height	:390,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[prField],
		listeners: {
			close : {
				fn: function(){ prField.getForm().reset(); }   
			}					
		}
	});
	
	
	var gridInfoPr = Ext.create('Ext.grid.Panel', {
		store	:storeGridInfoPr,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'kode_brg',
			header		:'Kode Barang',
		},{ 
			dataIndex	:'nama_brg',
			header		:'Nama Barang',
			width		:125
		},{ 
			dataIndex	:'part_no',
			header		:'Part No',
		},{ 
			dataIndex	:'satuan',
			header		:'Satuan',
		},{ 
			dataIndex	:'jml_brg_fpb',
			header		:'Jumlah',
		}],
		height	:160,
		width	:480
	});
	
	var infoPrField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'noPrPop',
			name 		:'noPrPop',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No PR',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'tglPrPop',
			name 		:'tglPrPop',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Tanggal PR',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Barang</b>',
			items	:[gridInfoPr]
		},{
			xtype		:'button',
			width		:100,
			text		:'Cetak',
			margin		:'0 5 5 10',
			handler		:actCetak
		}]
	});
	
	var infoPrPop = new Ext.Window({
        id 		:'infoPrPop', 	
		title	:'Info Purchase Request',
		width	:550,
		height	:360,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[infoPrField],
		listeners: {
			close : {
				fn: function(){ infoPrField.getForm().reset(); }   
			}					
		} 
	});
	
	function actInfoFpb(type){
		if(type=='fpb'){
			Ext.getCmp('btnAppFpb').hide();
			var dataGrid = gridFpb.getSelectionModel().selected.items;
		}else{
			Ext.getCmp('btnAppFpb').show();
			var dataGrid = gridPrList.getSelectionModel().selected.items;
		}
		
		if(dataGrid.length>0){
			var noFpb	= dataGrid[0].raw.no_fpb;
			var noPr	= dataGrid[0].raw.no_pr;
			var tglFpb	= dataGrid[0].raw.tgl_fpb;
			var statFpb	= dataGrid[0].raw.status_fpb;
			Ext.getCmp('noFpbDialog').setValue(noFpb);
			Ext.getCmp('tglDialog').setValue(tglFpb);
			
			if(type!='fpb'){
				var statPr	= dataGrid[0].raw.status_pr;
				if(statPr=='Approve' && statFpb=='Process'){
					infoFpbDialog.show();
					storeGridInfoFpb.load();
				}else{
					Ext.Msg.alert('Information', 'No FPB : '+noFpb+' dalam status '+statFpb+'!');
				}
			}else{
				infoFpbDialog.show();
				storeGridInfoFpb.load();
			}
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih FPB!');
		}
	}
	
	function actBuatPr(){
		getNoPr();
		var dataGrid = gridFpb.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var noFpb	= dataGrid[0].raw.no_fpb;
			var statFpb	= dataGrid[0].raw.status_fpb;
			if(statFpb=='Request'){
				Ext.getCmp('hNoFpb').setValue(noFpb);
				Ext.getCmp('poolDialog').setValue('Perintis');
				prDialog.show();
				storeGridPr.load();
			}else{
				Ext.Msg.alert('Informasi', 'FPB no '+noFpb+' sudah pernah diajukan !');
			}
		}
		
	}
	
	function actSimpan(){
		var noPr		= Ext.getCmp('noPrDialog').getValue();
		var idFpb	 	= gridFpb.getSelectionModel().selected.items[0].raw.id_fpb;
		
		if(noPr=='' || idFpb==''){
			Ext.Msg.alert('Informasi', 'Periksa Kelengkapan Isian!');
		}else{
			var paramsDat	= {noPr:noPr,idFpb:idFpb}
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'pr/actSimpan',
				params	:paramsDat,
				method	:'post',
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Informasi', obj.message);
					prDialog.hide();
					storeGridFpb.load();
				}
			});
		}
	}
	
	function getNoPr(){
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'pr/getNoPr',
			method	:'post',
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.getCmp('noPrDialog').setValue(obj.no_pr);
			}
		});
	}
	
	function actCetakShow(){
		var dataGrid = gridPrList.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var noPr	= dataGrid[0].raw.no_pr;
			var tglPr	= dataGrid[0].raw.tgl_pr;
			var statPr	= dataGrid[0].raw.status_pr;
			if(statPr=='Request'||statPr=='Review'){
				Ext.getCmp('noPrPop').setValue(noPr);
				Ext.getCmp('tglPrPop').setValue(tglPr);
				infoPrPop.show();
				storeGridInfoPr.load();
			}else{
				Ext.Msg.alert('Informasi', 'No PR : '+noPr+' dalam status '+statPr+' !');
			}
		}
	}
	
	
	function actAppFpb(){
		actInfoFpb('app');
	}
	
	function actApp(){
		var noFpb = Ext.getCmp('noFpbDialog').getValue();
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'pr/actAppFpb',
			method	:'post',
			params	:{noFpb:noFpb},
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.Msg.alert('Informasi', obj.message);
				infoFpbDialog.hide();
				storeGridFpb.load();
				storeGridPrList.load();
			}
		});
	}
	
	function actCetak(){
		var dataGrid = gridPrList.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idPr		= dataGrid[0].raw.id_pr;
			var	paramExp	= 'pr/cetak/idPr/'+idPr;
			window.open(baseUrl + paramExp, "_blank");
		}
	}
});
</script>