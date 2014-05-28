<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';
Ext.onReady(function(){
	var storeGridFpbSrc = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'fpb/getGridFpb',
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
			{name:'status_fpb',type: 'string'},
			{name:'no_mr',type: 'string'},
			{name:'koridor_bus',type: 'string'},
		],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('dateFpbSrc1').getValue();
				date2	= Ext.getCmp('dateFpbSrc2').getValue();
				noFpb	= Ext.getCmp('noFpbSrc').getValue();
				
				store.proxy.extraParams.date1	= date1;
				store.proxy.extraParams.date2	= date2;
				store.proxy.extraParams.noFpb	= noFpb;
			} 
		}
	});
	
	var storeGridMr = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'fpb/getGridMr',
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
			{name:'id_mr',type: 'string'},
			{name:'no_mr',type: 'string'},
			{name:'status_mr',type: 'string'},
			{name:'id_bus',type: 'string'},
			{name:'koridor_bus',type: 'string'},
			{name:'tgl_mr',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('dateMr1').getValue();
				date2	= Ext.getCmp('dateMr2').getValue();
				noMr	= Ext.getCmp('noMr').getValue();
				
				store.proxy.extraParams.date1	= date1;
				store.proxy.extraParams.date2	= date2;
				store.proxy.extraParams.noMr	= noMr;
			} 
		}
	});
	
	var storeGridInfoMr = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'fpb/getGridInfoMr',
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
			{name:'jml_brg_mr',type: 'string'},
			{name:'satuan',type: 'string'},
			{name:'status_brg_mr',type: 'boolean'},
			{name:'status_brg_mr_desc',type: 'string'},
			{name:'stok',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				noMr	= Ext.getCmp('noMrDialog').getValue();
				store.proxy.extraParams.noMr	= noMr;
				store.proxy.extraParams.type	= 'info';
			} 
		}
	});
	
	
	var storeGridFpb = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'fpb/getGridInfoMr',
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
			{name:'jml_brg_mr',type: 'string'},
			{name:'status_brg_mr',type: 'string'},
			{name:'status_brg_mr_desc',type: 'string'},
			{name:'stok',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				noMr	= Ext.getCmp('noMrRefDialog').getValue();
				store.proxy.extraParams.noMr	= noMr;
				store.proxy.extraParams.type	= 'fpb';
			} 
		}
	});
	
	var storeGridFpbCetak = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'fpb/getGridCetak',
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
			{name:'jml_brg_fpb',type: 'string'},
			{name:'stok',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				noFpb	= Ext.getCmp('noFpbCetak').getValue();
				store.proxy.extraParams.noFpb	= noFpb;
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
			name		:'noMr',
			id			:'noMr',
			width		:310,
			fieldLabel	:'No MR',
			enableKeyEvents : true,
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'dateMr1',
				id			:'dateMr1',
				width		:200,
				fieldLabel	:'Tanggal',
				format		:'d-m-Y',
				submitFormat:'Y-m-d'
			},{
				xtype		:'datefield',
				name		:'dateMr2',
				id			:'dateMr2',
				width		:100,
				margin		:'0 0 0 10',
				format		:'d-m-Y',
				submitFormat:'Y-m-d'
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridMr.load();
			}
		}]
	});
	
	var gridMr = Ext.create('Ext.grid.Panel', {
		store	:storeGridMr,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'no_mr',
			header		:'No MR',
			width		:125
		},{ 
			dataIndex	:'tgl_mr',
			header		:'Tanggal MR',
			width		:125
		},{ 
			dataIndex	:'id_bus',
			header		:'No Body',
			width		:75
		},{ 
			dataIndex	:'koridor_bus',
			header		:'Koridor',
			width		:75
		},{ 
			dataIndex	:'status_mr',
			header		:'Status',
		}],
		height	:160,
		//width	:550,
		bbar:{
			xtype	: 'pagingtoolbar',
			id 		: 'toolbargrid',
			store 	: storeGridMr,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},
	});
	
	var listMr = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		height	:200,
		padding	:'5',
		title	:'<b>List Material Requisition</b>',
		items	:[gridMr]
	}
	
	var mrPanel = Ext.create('Ext.form.Panel', {
		title	:'List Material Requisition',
        id		:'mrPanel',
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchForm,listMr,{
			xtype		:'button',
			width		:100,
			text		:'Detil MR',
			margin		:'0 5 5 10',
			handler		:actInfoDetil
		},{
			xtype		:'button',
			width		:100,
			text		:'Buat FPB',
			margin		:'0 5 5 0',
			handler		:actBuatFpb
		}]
	});
	
	var gridInfoMr = Ext.create('Ext.grid.Panel', {
		store	:storeGridInfoMr,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			xtype		:'checkcolumn',
			dataIndex	:'status_brg_mr',
			width 		:35,
			listeners	:{
				beforecheckchange:function(me,rowIndex,checked,e){
					var statCheck = storeGridInfoMr.data.items[rowIndex].raw.status_brg_mr;
					
					var stok 	= parseInt(storeGridInfoMr.data.items[rowIndex].data.stok);
					var jmlReq	= parseInt(storeGridInfoMr.data.items[rowIndex].data.jml_brg_mr);
					
					if(statCheck=='1'){
						return false;
					}
					
					if(jmlReq>stok){
						Ext.Msg.alert('Information', 'Jumlah Permintaan tidak mencukupi !');
						return false;
						
					}
					
				}
			}
		},{ 
			dataIndex	:'kode_brg',
			header		:'Kode Barang',
			width		:125
		},{ 
			dataIndex	:'nama_brg',
			header		:'Nama Barang',
			width		:125
		},{ 
			dataIndex	:'part_no',
			header		:'Part No',
		},{ 
			dataIndex	:'jml_brg_mr',
			header		:'Jumlah',
			width		:75
		},{ 
			dataIndex	:'satuan',
			header		:'Satuan',
			width		:75
		},{ 
			dataIndex	:'stok',
			header		:'Stok',
			width		:75
		},{ 
			dataIndex	:'status_brg_mr_desc',
			header		:'Status',
			width		:75
		}],
		height	:160,
		width	:630
	});
	
	var infoMrField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'noBodyDialog',
			name 		:'noBodyDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No Body',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'noMrDialog',
			name 		:'noMrDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No MR',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'koridorDialog',
			name 		:'koridorDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Koridor',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Barang</b>',
			items	:[gridInfoMr]
		},{
			xtype		:'button',
			width		:100,
			text		:'Disetujui',
			margin		:'5 5 5 565',
			handler		:actSetuju
		}]
	});
	
	var infoMrDialog = new Ext.Window({
        id 		:'dialogCari', 	
		title	:'Detil Material Requisition',
		width	:700,
		height	:400,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[infoMrField],
		listeners: {
			close : {
				fn: function(){ infoMrField.getForm().reset(); }   
			}					
		} 
	});
	
	var gridFpb = Ext.create('Ext.grid.Panel', {
		store	:storeGridFpb,
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
			dataIndex	:'jml_brg_mr',
			header		:'Jumlah',
		},{ 
			dataIndex	:'satuan',
			header		:'Satuan',
		},{ 
			dataIndex	:'stok',
			header		:'Stok',
		},{ 
			dataIndex	:'status_brg_mr_desc',
			header		:'Status',
		}],
		height	:160,
		width	:500
	});
	
	var fpbField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'noMrRefDialog',
			name 		:'noMrRefDialog',
			labelAlign	:'right',
			labelWidth	:125,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No MR Referensi',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'noFpbDialog',
			name 		:'noFpbDialog',
			labelAlign	:'right',
			labelWidth	:125,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No FPB',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Barang</b>',
			items	:[gridFpb]
		},{
			xtype		:'button',
			width		:100,
			text		:'Edit Barang',
			margin		:'5 5 5 10',
			handler		:actEditBrg
		},{
			xtype		:'button',
			width		:100,
			text		:'Simpan',
			margin		:'5 5 5 10',
			handler		:actSmpFpb
		}]
	});
	
	var fpbDialog = new Ext.Window({
        id 		:'fpbDialog', 	
		title	:'Form Permintaan Barang',
		width	:570,
		height	:370,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[fpbField],
		listeners: {
			close : {
				fn: function(){ fpbField.getForm().reset(); }   
			}					
		} 
	});
	
	var editBrgField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'edtKdBrg',
			name 		:'edtKdBrg',
			labelAlign	:'right',
			labelWidth	:75,
			width		:300,
			margin		:'10 5 5 5',
			fieldLabel	:'Kode Barang',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'edtNmBrg',
			name 		:'edtNmBrg',
			labelAlign	:'right',
			labelWidth	:75,
			width		:300,
			margin		:'10 5 5 5',
			fieldLabel	:'Nama Barang',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'edtStok',
			name 		:'edtStok',
			labelAlign	:'right',
			labelWidth	:75,
			width		:300,
			margin		:'10 5 5 5',
			fieldLabel	:'Stok',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'label',
			id			:'lblStok',
			text		:'Min. 100 ; Max. 100',
			margin		:'0 0 0 85'
		},{
			xtype		:'numberfield',
			id   		:'edtJumlah',
			name 		:'edtJumlah',
			labelAlign	:'right',
			labelWidth	:75,
			width		:300,
			margin		:'10 5 5 5',
			fieldLabel	:'Jumlah',
			allowBlank	:false
		},{
			xtype		:'button',
			width		:100,
			text		:'Simpan',
			margin		:'5 5 5 85',
			handler		:actSmpEditBrg
		}]
	});
	
	var editBrgDialog = new Ext.Window({
        id 		:'editBrgDialog', 	
		title	:'Edit Barang',
		width	:350,
		height	:230,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[editBrgField],
		listeners: {
			close : {
				fn: function(){ editBrgField.getForm().reset(); }   
			}					
		} 
	});
	
	var searchFpb = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noFpbSrc',
			id			:'noFpbSrc',
			width		:310,
			fieldLabel	:'No FPB'
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'dateFpbSrc1',
				id			:'dateFpbSrc1',
				width		:200,
				fieldLabel	:'Tanggal FPB'
			},{
				xtype		:'datefield',
				name		:'dateFpbSrc2',
				id			:'dateFpbSrc2',
				width		:100,
				margin		:'0 0 0 10'
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridFpbSrc.load();
			}
		}]
	});
	
	var gridFpbSrc = Ext.create('Ext.grid.Panel', {
		store	:storeGridFpbSrc,
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
			dataIndex	:'no_mr',
			header		:'No MR',
			width		:125
		},{ 
			dataIndex	:'koridor_bus',
			header		:'Koridor'
		},{ 
			dataIndex	:'status_fpb',
			header		:'Status',
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridFpbSrc,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},
	});
	
	var listFpbSrc = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		height	:200,
		padding	:'5',
		title	:'<b>List Permintaan Barang</b>',
		items	:[gridFpbSrc]
	}
	
	var fpbPanel = Ext.create('Ext.form.Panel', {
		title	:'List FPB',
		id		:'fpbPanel',
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchFpb,listFpbSrc,{
			xtype		:'button',
			width		:100,
			text		:'Cetak FPB',
			margin		:'0 5 5 10',
			handler		:actCetakShow
		},{
			xtype		:'button',
			width		:100,
			text		:'Approve MR',
			margin		:'0 5 5 0',
			handler		:actAppMr
		}]
	});
	
	var fpbTab = Ext.create('Ext.tab.Panel', {
		id			:'fpbTab',
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[mrPanel,fpbPanel],
		renderTo	: Ext.getBody()
	});
	
	var gridFpbCetak = Ext.create('Ext.grid.Panel', {
		store	:storeGridFpbCetak,
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
			dataIndex	:'jml_brg_fpb',
			header		:'Jumlah',
		},{ 
			dataIndex	:'satuan',
			header		:'Satuan',
		},{ 
			dataIndex	:'stok',
			header		:'Stok',
		}],
		height	:160,
		width	:500
	});
	
	var cetakField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'noMrRefCetak',
			name 		:'noMrRefCetak',
			labelAlign	:'right',
			labelWidth	:125,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No MR Referensi',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'noFpbCetak',
			name 		:'noFpbCetak',
			labelAlign	:'right',
			labelWidth	:125,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No FPB',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Barang</b>',
			items	:[gridFpbCetak]
		},{
			xtype		:'button',
			width		:100,
			text		:'Cetak',
			margin		:'5 5 5 10',
			handler		:actCetak
		}]
	});
	
	var cetakFpb = new Ext.Window({
        id 		:'cetakFpb', 	
		title	:'Cetak FPB',
		width	:570,
		height	:370,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[cetakField],
		listeners: {
			close : {
				fn: function(){ cetakField.getForm().reset(); }   
			}					
		} 
	});
	
	function actAppMr(){
		var dataGrid = gridFpbSrc.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var noBody	= dataGrid[0].raw.id_bus;
			var koridor	= dataGrid[0].raw.koridor_bus;
			var noMr	= dataGrid[0].raw.no_mr;
			var statFpb	= dataGrid[0].raw.status_fpb;
			var noFpb	= dataGrid[0].raw.no_fpb;
			
			if(statFpb=='Approve'){
				Ext.getCmp('noBodyDialog').setValue(noBody);
				Ext.getCmp('noMrDialog').setValue(noMr);
				Ext.getCmp('koridorDialog').setValue(koridor);
				infoMrDialog.show();
				storeGridInfoMr.load();
			}else{
				Ext.Msg.alert('Information', 'FPB No : '+noFpb+' dalam status '+statFpb+' !');
			}
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih FPB!');
		}
	}
	
	function actInfoDetil(){
		var dataGrid = gridMr.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var noBody	= dataGrid[0].raw.id_bus;
			var koridor	= dataGrid[0].raw.koridor_bus;
			var noMr	= dataGrid[0].raw.no_mr;
			
			Ext.getCmp('noBodyDialog').setValue(noBody);
			Ext.getCmp('noMrDialog').setValue(noMr);
			Ext.getCmp('koridorDialog').setValue(koridor);
			infoMrDialog.show();
			storeGridInfoMr.load();
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih MR!');
		}
		
	}
	
	function actBuatFpb(){
		var dataGrid = gridMr.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var statMr	= dataGrid[0].raw.status_mr;
			var noMr	= dataGrid[0].raw.no_mr;
			if(statMr == 'Request'){
				getNoFpb();
				
				Ext.getCmp('noMrRefDialog').setValue(noMr);
				fpbDialog.show();
				storeGridFpb.load();
			}else{
				Ext.Msg.alert('Information', 'MR No : '+noMr+' dalam status '+statMr+' !');
			}
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih MR !');
		}
		
	}
	
	function actEditBrg(){
		var dataGrid = gridFpb.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var kdBrg	= dataGrid[0].raw.kode_brg;
			var nmBrg	= dataGrid[0].raw.nama_brg;
			var stok	= dataGrid[0].raw.stok;
			var stokMin	= dataGrid[0].raw.stok_min;
			var stokMax	= dataGrid[0].raw.stok_max;
			var jmlBrg	= dataGrid[0].raw.jml_brg_mr;
			var stokAll	= 'Min. '+stokMin+'; Max. '+stokMax;
			
			Ext.getCmp('edtJumlah').setMaxValue(stokMax);
			Ext.getCmp('edtKdBrg').setValue(kdBrg);
			Ext.getCmp('edtNmBrg').setValue(nmBrg);
			Ext.getCmp('edtStok').setValue(stok);
			Ext.getCmp('lblStok').setText(stokAll);
			Ext.getCmp('edtJumlah').setValue(jmlBrg);
			editBrgDialog.show();
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih Barang yang diedit!');
		}
		
	}
	
	/* function actCheckColBrg(idMr,idBrg,status){
		Ext.Ajax.request({
			url		:baseUrl + 'fpb/checkColBrg',
			params	:{idMr:idMr,idBrg:idBrg,status:status},
			method	:'post',
			success :function(result){
				obj = Ext.JSON.decode(result.responseText);
				
			}
		});
	} */
	/* 
	function actProses(){
		var dataGrid = gridMr.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idMr	= dataGrid[0].raw.id_mr;
			var noMr	= dataGrid[0].raw.no_mr;
			var statMr	= dataGrid[0].data.status_mr;
			if(statMr=='Request'){
				loadingMask.show();
				Ext.Ajax.request({
					url		:baseUrl + 'fpb/actProses',
					params	:{idMr:idMr,noMr:noMr},
					method	:'post',
					success :function(result){
						loadingMask.hide();				
						obj = Ext.JSON.decode(result.responseText);
						Ext.Msg.alert('Information', obj.message);
						infoMrDialog.hide();
						storeGridMr.load();
					}
				});
			}else{
				Ext.Msg.alert('Information', 'No MR '+noMr+' sudah pernah diproses !');
			}
		}
	} */
	
	function actSetuju(){
		actTab	= fpbTab.getActiveTab();
		
		if(actTab.id=='fpbPanel'){
			var dataGrid = gridFpbSrc.getSelectionModel().selected.items;
		}else{
			var dataGrid = gridMr.getSelectionModel().selected.items;
		}
		
		if(dataGrid.length>0){
			var gridData	= storeGridInfoMr.data.items;
			var brgData		= '';
			var checkApp	= 0;
			var i			= 0;
			var checkSet	= 0;
			Ext.Array.each(gridData, function(data, index) {
				i	= i+1;
				var idBrg   = data.raw['id_brg'],
					idMr	= data.raw['id_mr'],
					statBrg	= data.data['status_brg_mr']
					statBrgR= data.raw['status_brg_mr'];
				
				if(statBrg&&(statBrgR!='1')){
					brgData		= idBrg+'#'+idMr+';'+brgData;
					checkApp	= checkApp+1;
				}
				
				if(statBrgR=='1'){
					checkSet	= checkSet+1;
				}
			});
			
			if(checkSet==i){
				Ext.Msg.alert('Information', 'Semua Barang sudah disetujui !');
			}else{
				if(checkApp==0){
					Ext.Msg.alert('Information', 'Silahkan pilih barang yang disetujui !');
				}else{
					var idMr	= dataGrid[0].raw.id_mr;
					var noMr	= dataGrid[0].raw.no_mr;
					var statMr	= dataGrid[0].raw.status_mr;
					var statFpb	= dataGrid[0].raw.status_fpb;
					if(statMr=='Request' || (statMr=='Process' && statFpb=='Approve')){
						loadingMask.show();
						Ext.Ajax.request({
							url		:baseUrl + 'fpb/actSetuju',
							params	:{idMr:idMr,noMr:noMr,brgData:brgData},
							method	:'post',
							success :function(result){
								loadingMask.hide();				
								obj = Ext.JSON.decode(result.responseText);
								Ext.Msg.alert('Information', obj.message);
								infoMrDialog.hide();
								storeGridMr.load();
							}
						});
					}else{
						Ext.Msg.alert('Information', 'No MR : '+noMr+' dalam status '+statMr+' !');
					}
				}
			}
			
		}
	}
	
	function actSmpEditBrg(){
		var dataGrid = gridFpb.getSelectionModel().selected.items;
		var jmlBrg	 = Ext.getCmp('edtJumlah').getValue();
		if(dataGrid.length>0){
			if(editBrgField.getForm().isValid()){
				dataGrid[0].set('jml_brg_mr',jmlBrg);
				editBrgDialog.hide();
			}
		}
	}
	
	function actSmpFpb(){
		var noFpb		= Ext.getCmp('noFpbDialog').getValue();
		var noMr		= Ext.getCmp('noMrRefDialog').getValue();
		var gridData	= storeGridFpb.data.items;
		var brgData		= '';
		
		Ext.Array.each(gridData, function(data, index) {
			var idBrg   = data.raw['id_brg'],
				jmlBrg 	= data.data['jml_brg_mr'];
			
			brgData	= idBrg+'#'+jmlBrg+';'+brgData;
		});
		
		if(brgData=='' || noMr=='' || noFpb==''){
			Ext.Msg.alert('Informasi', 'Periksa Kelengkapan Isian!');
		}else{
			var paramsDat	= {noFpb:noFpb,noMr:noMr,brgData:brgData}
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'fpb/actSmpFpb',
				params	:paramsDat,
				method	:'post',
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Informasi', obj.message);
					fpbDialog.hide();
					storeGridMr.load();
				}
			});
		}
	}
	
	function getNoFpb(){
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'fpb/getNoFpb',
			method	:'post',
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.getCmp('noFpbDialog').setValue(obj.no_fpb);
			}
		});
	}
	function actCetakShow(){
		var dataGrid = gridFpbSrc.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var noMr	= dataGrid[0].raw.no_mr;
			var noFpb	= dataGrid[0].raw.no_fpb;
			var statFpb	= dataGrid[0].raw.status_fpb;
			if(statFpb=='Request'){
				Ext.getCmp('noMrRefCetak').setValue(noMr);
				Ext.getCmp('noFpbCetak').setValue(noFpb);
				storeGridFpbCetak.load();
				cetakFpb.show();
			}else{
				Ext.Msg.alert('Information', 'No FPB : '+noFpb+' dalam status '+statFpb+' !');
			}
		}else{
			Ext.Msg.alert('Informasi', 'Silahkan Pilih FPB !');
		}
		
	}
	
	function actCetak(){
		var dataGrid = gridFpbSrc.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idFpb		= dataGrid[0].raw.id_fpb;
			var	paramExp	= 'fpb/cetak/idFpb/'+idFpb;
			window.open(baseUrl + paramExp, "_blank");
		}
	}
});
</script>