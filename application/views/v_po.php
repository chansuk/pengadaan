<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';
Ext.onReady(function(){
	
	var storeGridPr = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'po/getGridPr',
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
			{name:'no_pr',type: 'string'},
			{name:'no_sph',type: 'string'},
			{name:'nama_sup',type: 'string'},
			{name:'pool_pr',type: 'string'},
			{name:'tgl_pr',type: 'string'},
			{name:'status_sph',type: 'string'},
			{name:'status_pr',type: 'string'},
		],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('datePr1').getValue();
				date2	= Ext.getCmp('datePr2').getValue();
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
			url		:baseUrl + 'po/getGridInfoPr',
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
			{name:'jml_brg_sph',type: 'int'},
			{name:'harga_brg_sph',type: 'int'},
			{name:'total',type: 'int'},
		],
		listeners: {
			beforeload: function(store, operation, options){
				noPr	= Ext.getCmp('noPrSphDialog').getValue();
				store.proxy.extraParams.noPr	= noPr;
			} 
		}
	});
	
	var storePo = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'po/getGridInfoPr',
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
			{name:'jml_brg_sph',type: 'int'},
			{name:'harga_brg_sph',type: 'int'},
			{name:'total',type: 'int'},
		],
		listeners: {
			beforeload: function(store, operation, options){
				noPr	= Ext.getCmp('noPrDialog').getValue();
				store.proxy.extraParams.noPr	= noPr;
			} 
		}
	});
	
	
	var storeGridPoList = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'ttb/getGridPo',
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
			{name:'no_po',type: 'string'},
			{name:'no_sph',type: 'string'},
			{name:'nama_sup',type: 'string'},
			{name:'tgl_po',type: 'string'},
			{name:'no_pr',type: 'string'},
			{name:'status_po',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('datePoList1').getValue();
				date2	= Ext.getCmp('datePoList2').getValue();
				noPo	= Ext.getCmp('noPoList').getValue();
				
				store.proxy.extraParams.date1	= date1;
				store.proxy.extraParams.date2	= date2;
				store.proxy.extraParams.noPo	= noPo;
			} 
		}
	});
	
	
	var storeGridInfoPo = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'ttb/getGridTtb',
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
			{name:'jml_brg_sph',type: 'int'},
			{name:'harga_brg_sph',type: 'int'},
			{name:'total',type: 'int'},
		]
	});
	
	var searchForm = Ext.create('Ext.form.Panel', {
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
				storeGridPr.load();
			}
		}]
	});
	
	var gridListPr = Ext.create('Ext.grid.Panel', {
		store	:storeGridPr,
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
			dataIndex	:'no_sph',
			header		:'No SPH',
			width		:125
		},{ 
			dataIndex	:'nama_sup',
			header		:'Supplier',
			width		:150
		},{ 
			dataIndex	:'tgl_pr',
			header		:'Tanggal',
			width		:125
		},{ 
			dataIndex	:'pool_pr',
			header		:'Pool',
		},{ 
			dataIndex	:'status_pr',
			header		:'Status PR',
		}],
		height	:160,
		//width	:460,
		bbar:{
			xtype	: 'pagingtoolbar',
			id 		: 'toolbargrid',
			store 	: storeGridPr,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},
	});
	
	var listPr = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		//width	:480,
		height	:200,
		padding	:'5',
		title	:'<b>List Purchase Request</b>',
		items	:[gridListPr]
	}
	
	var poPanel = Ext.create('Ext.form.Panel', {
		title	:'List Purchase Request',
        //width	:500,
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchForm,listPr,{
			xtype		:'button',
			width		:100,
			text		:'Detil PR',
			margin		:'0 5 5 10',
			handler		:function(){
				actInfoPr('info');
			}
		},{
			xtype		:'button',
			width		:100,
			text		:'Buat PO',
			margin		:'0 5 5 0',
			handler		:actPo
		}],
	});
	
	var gridPo = Ext.create('Ext.grid.Panel', {
		store	:storePo,
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
			dataIndex	:'harga_brg_sph',
			header		:'Harga',
			renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');;
            }
		},{ 
			dataIndex	:'jml_brg_sph',
			header		:'Jumlah',
		},{ 
			dataIndex	:'total',
			header		:'Total Harga',
			renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');;
            }
		}],
		height	:160,
		//width	:640
	});
	
	var poField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'noPoDialog',
			name 		:'noPoDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No PO',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'noSphDialog',
			name 		:'noSphDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No SPH',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'supplierDialog',
			name 		:'supplierDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Supplier',
			allowBlank	:false,
			readOnly	:true
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
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Barang</b>',
			items	:[gridPo]
		},{
			xtype		:'button',
			width		:100,
			text		:'Simpan',
			margin		:'0 5 5 585',
			handler		:actSimpan
			
		}]
	});
	
	var poDialog = new Ext.Window({
        id 		:'poDialog', 	
		title	:'Purchase Order',
		width	:710,
		height	:460,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[poField],
		listeners: {
			close : {
				fn: function(){ poField.getForm().reset(); }   
			}					
		} 
	});
	
	var gridInfoSph = Ext.create('Ext.grid.Panel', {
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
		},{ 
			dataIndex	:'part_no',
			header		:'Part No',
		},{ 
			dataIndex	:'satuan',
			header		:'Satuan',
		},{ 
			dataIndex	:'harga_brg_sph',
			header		:'Harga',
			renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');;
            }
		},{ 
			dataIndex	:'jml_brg_sph',
			header		:'Jumlah',
		},{ 
			dataIndex	:'total',
			header		:'Total Harga',
			renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');;
            }
		}],
		height	:160,
		width	:640
	});
	
	var infoSphField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'noSphInfoDialog',
			name 		:'noSphInfoDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No SPH',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'combobox',
			id   		:'supplierInfoDialog',
			name 		:'supplierInfoDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Supplier',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'noPrSphDialog',
			name 		:'noPrSphDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No PR',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'tglPrSphDialog',
			name 		:'tglPrSphDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Tanggal PR',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'poolSphDialog',
			name 		:'poolSphDialog',
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
			title	:'<b>List Barang</b>',
			items	:[gridInfoSph]
		},{
			xtype		:'button',
			width		:100,
			text		:'Approve',
			margin		:'5 5 5 10',
			id			:'btnApprove',
			handler		:actApprove
		}]
	});
	
	var infoSphDialog = new Ext.Window({
        id 		:'infoSphDialog', 	
		title	:'Info Detil Purchase Request',
		width	:710,
		height	:460,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[infoSphField],
		listeners: {
			close : {
				fn: function(){ infoSphField.getForm().reset(); }   
			}					
		} 
	});
	
	
	var gridPoList = Ext.create('Ext.grid.Panel', {
		store	:storeGridPoList,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'no_po',
			header		:'No PO',
			width		:125
		},{ 
			dataIndex	:'no_sph',
			header		:'No SPH',
			width		:125
		},{ 
			dataIndex	:'no_pr',
			header		:'No PR',
			width		:125
		},{ 
			dataIndex	:'nama_sup',
			header		:'Supplier',
			width		:150
		},{ 
			dataIndex	:'tgl_po',
			header		:'Tanggal PO',
			width		:125
		},{ 
			dataIndex	:'status_po',
			header		:'Status PO',
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridPoList,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},
	});
	
	var searchPoList = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noPoList',
			id			:'noPoList',
			width		:310,
			fieldLabel	:'No PO'
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'datePoList1',
				id			:'datePoList1',
				width		:200,
				fieldLabel	:'Tanggal PO'
			},{
				xtype		:'datefield',
				name		:'datePoList2',
				id			:'datePoList2',
				width		:100,
				margin		:'0 0 0 10'
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridPoList.load();
			}
		}]
	});
	
	var listPo = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		height	:200,
		padding	:'5',
		title	:'<b>List Purchase Order</b>',
		items	:[gridPoList]
	}
	
	
	var poList = Ext.create('Ext.form.Panel', {
		title	:'List Purchase Order',
        margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchPoList,listPo,{
			xtype		:'button',
			width		:100,
			text		:'Cetak PO',
			margin		:'0 5 5 10',
			handler		:actCetakShow
		},{
			xtype		:'button',
			width		:100,
			hidden		:true,
			text		:'Approve PR',
			margin		:'0 5 5 10',
			handler		:function(){
				actInfoPr('app');
			}
		}]
	});
	
	var poTab = Ext.create('Ext.tab.Panel', {
		id			:'poTab',
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[poPanel,poList],
		renderTo	: Ext.getBody()
	});
	
	var gridInfoPo = Ext.create('Ext.grid.Panel', {
		store	:storeGridInfoPo,
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
			dataIndex	:'harga_brg_sph',
			header		:'Harga',
			renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');
            }
		},{ 
			dataIndex	:'jml_brg_sph',
			header		:'Jumlah',
		},{ 
			dataIndex	:'total',
			header		:'Total Harga',
			renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');
            }
		}],
		height	:160
	});
	
	var infoPoField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'noPoPop',
			name 		:'noPoPop',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No PO',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'tglPoPop',
			name 		:'tglPoPop',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Tanggal PO',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Barang</b>',
			items	:[gridInfoPo]
		},{
			xtype		:'button',
			width		:100,
			text		:'Cetak',
			margin		:'0 5 5 10',
			handler		:actCetak
		}]
	});
	
	var infoPoPop = new Ext.Window({
        id 		:'infoPrPop', 	
		title	:'Info Purchase Order',
		width	:750,
		height	:360,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[infoPoField],
		listeners: {
			close : {
				fn: function(){ infoPoField.getForm().reset(); }   
			}					
		} 
	});
	
	function actInfoPr(type){
		if(type=='info'){
			var dataGrid = gridListPr.getSelectionModel().selected.items;
		}else{
			var dataGrid = gridPoList.getSelectionModel().selected.items;
		}
		
		
		if(dataGrid.length>0){
			var noSph	= dataGrid[0].raw.no_sph;
			var nmSup	= dataGrid[0].raw.nama_sup;
			var noPr	= dataGrid[0].raw.no_pr;
			var tglPr	= dataGrid[0].raw.tgl_pr;
			var poolPr	= dataGrid[0].raw.pool_pr;
			var statPr	= dataGrid[0].raw.status_pr;
			Ext.getCmp('noSphInfoDialog').setValue(noSph);
			Ext.getCmp('supplierInfoDialog').setValue(nmSup);
			Ext.getCmp('noPrSphDialog').setValue(noPr);
			Ext.getCmp('tglPrSphDialog').setValue(tglPr);
			Ext.getCmp('poolSphDialog').setValue(poolPr);
			
			if(type!='info'){
				var statPo	= dataGrid[0].raw.status_po;
				Ext.getCmp('btnApprove').show();
				if(statPo=='Receive' && statPr=='Process'){
					infoSphDialog.show();
					storeGridInfoPr.load();
					storeGridPoList.load();
				}else{
					Ext.Msg.alert('Informasi', 'No PR : '+noPr+' dalam status '+statPr+'!');
				}
			}else{
				Ext.getCmp('btnApprove').hide();
				infoSphDialog.show();
				storeGridInfoPr.load();
			}
		}else{
			Ext.Msg.alert('Informasi', 'Silahkan pilih PO!');
		}
	}
	
	function actPo(){
		var dataGrid = gridListPr.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var noSph	= dataGrid[0].raw.no_sph;
			var nmSup	= dataGrid[0].raw.nama_sup;
			var noPr	= dataGrid[0].raw.no_pr;
			var statPr	= dataGrid[0].raw.status_pr;
			
			if(statPr=='Approve SPH'){
				Ext.getCmp('noSphDialog').setValue(noSph);
				Ext.getCmp('supplierDialog').setValue(nmSup);
				Ext.getCmp('noPrDialog').setValue(noPr);
				getNoPo();
				poDialog.show();
				storePo.load();
			}else{
				Ext.Msg.alert('Informasi', 'No PR : '+noPr+' sudah pernah diproses !');
			}
		}else{
			Ext.Msg.alert('Informasi', 'Silahkan pilih PO!');
		}
	}
	
	function actSimpan(){
		var dataGrid = gridListPr.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idPr	= dataGrid[0].raw.id_pr;
			var idSph	= dataGrid[0].raw.id_sph;
			var noPo	= Ext.getCmp('noPoDialog').getValue();
			
			if(idPr=='' || noPo=='' || idSph==''){
				Ext.Msg.alert('Informasi', 'Periksa Kelengkapan Isian!');
			}else{
				var paramsDat	= {idPr:idPr,noPo:noPo,idSph:idSph}
				loadingMask.show();
				Ext.Ajax.request({
					url		:baseUrl + 'po/actSimpan',
					params	:paramsDat,
					method	:'post',
					success :function(result){
						loadingMask.hide();				
						obj = Ext.JSON.decode(result.responseText);
						Ext.Msg.alert('Informasi', obj.message);
						resetAll();
					}
				});
			}
		}
	}
	
	function getNoPo(){
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'po/getNoPo',
			method	:'post',
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.getCmp('noPoDialog').setValue(obj.no_po);
				
			}
		});
	}
	
	function resetAll(){
		getNoPo();
		poDialog.hide();
		storeGridPr.load();
	}
	
	function actCetakShow(){
		var dataGrid = gridPoList.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var noPo	= dataGrid[0].raw.no_po;
			var tglPo	= dataGrid[0].raw.tgl_po;
			var statPo	= dataGrid[0].raw.status_po;
			
			if(statPo=='Sent'){
				Ext.getCmp('noPoPop').setValue(noPo);
				Ext.getCmp('tglPoPop').setValue(tglPo);
				infoPoPop.show();
				storeGridInfoPo.load({params:{noPo:noPo}});
			}else{
				Ext.Msg.alert('Informasi', 'No PO : '+noPo+' dalam status '+statPo+' !');
			}
		}else{
			Ext.Msg.alert('Informasi', 'Silahkan pilih PO!');
		}
	}
	
	function actCetak(){
		var dataGrid = gridPoList.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idPo		= dataGrid[0].raw.id_po;
			var	paramExp	= 'po/cetak/idPo/'+idPo;
			window.open(baseUrl + paramExp, "_blank");
		}
	}
	
	function actApprove(){
		var noPr = Ext.getCmp('noPrSphDialog').getValue();
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'po/actApprove',
			method	:'post',
			params	:{noPr:noPr},
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.Msg.alert('Informasi', obj.message);
				infoSphDialog.hide();
				storeGridPr.load();
				storeGridPoList.load();
			}
		});
	}
});
</script>