<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';
Ext.util.Format.thousandSeparator = '.';

Ext.onReady(function(){
	var storeGridPr = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'sph/getGridPr',
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
			{name:'tgl_pr',type: 'string'},
			{name:'pool_pr',type: 'string'},
			{name:'status_pr',type: 'string'}
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
			url		:baseUrl + 'sph/getGridInfoPr',
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
				noPr	= Ext.getCmp('noPrDialog').getValue();
				store.proxy.extraParams.noPr	= noPr;
			} 
		}
	});
	
	var storeGridSph = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'sph/getGridInfoPr',
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
			{name:'jml_brg_fpb',type: 'int'},
			{name:'harga',type: 'int'},
			{name:'total',type: 'int'},
			
		],
		listeners: {
			beforeload: function(store, operation, options){
				noPr	= Ext.getCmp('noPrSphDialog').getValue();
				store.proxy.extraParams.noPr	= noPr;
			} 
		}
	});
	
	var storeSphDetBrg = Ext.create('Ext.data.JsonStore', {
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'sph/getSphDetBrg',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows'
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
	
	var storeGridSphDet = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'sph/getGridSphDet',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows'
			}
		},
		fields 	:[
			{name:'no_sph',type: 'string'},
			{name:'no_sph_ref',type: 'string'},
			{name:'nama_sup',type: 'string'},
			{name:'tgl_sph',type: 'string'},
			{name:'status',type: 'bool'},
			{name:'total',type: 'int'}
			
		],
		listeners: {
			beforeload: function(store, operation, options){
				noPr	= Ext.getCmp('noPrSphDet').getValue();
				store.proxy.extraParams.noPr	= noPr;
			} 
		}
	});
	
	var storeSup = Ext.create('Ext.data.Store', {
		data 	: <?=$getSup;?>,
		fields 	: ['id_sup','nama_sup']
	});
	
	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
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
		margin	:'10',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'no_pr',
			header		:'No PR',
			width		:125
		},{ 
			dataIndex	:'tgl_pr',
			header		:'Tanggal',
			width		:125
		},{ 
			dataIndex	:'pool_pr',
			header		:'Pool',
		},{ 
			dataIndex	:'status_pr',
			header		:'Status',
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			id 		: 'toolbargrid',
			store 	: storeGridPr,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		}
	});
	
	var listPr = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		height	:200,
		padding	:'5',
		title	:'<b>List Purchase Request</b>',
		items	:[gridListPr]
	}
	
	
	var sphPanel = Ext.create('Ext.form.Panel', {
		title	:'List Purchase Request',
        //width	:500,
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchForm,listPr,{
			xtype		:'button',
			width		:100,
			text		:'Info Detil PR',
			margin		:'0 5 5 10',
			handler		:actInfoPr
		},{
			xtype		:'button',
			width		:100,
			text		:'Buat SPH',
			margin		:'0 5 5 0',
			handler		:actBuatSph
		},{
			xtype		:'button',
			width		:100,
			text		:'Info SPH',
			margin		:'0 5 5 0',
			handler		:actInfoSph
		}],
		
	});
	
	
	var gridSphDet = Ext.create('Ext.grid.Panel', {
		store	:storeGridSphDet,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			xtype		:'checkcolumn',
			dataIndex	:'status',
			width 		:35,
			listeners	:{
				beforecheckchange:function(me,rowIndex,checked,e){
					var statCheck = storeGridSphDet.data.items[rowIndex].raw.status;
					
					if(statCheck=='1'){
						return false;
					}
					
				}
			}
		},{ 
			dataIndex	:'no_sph',
			header		:'No SPH',
			width		:140
		},{ 
			dataIndex	:'no_sph_ref',
			header		:'No Ref SPH',
			width		:140
		},{ 
			dataIndex	:'nama_sup',
			header		:'Supplier',
			width		:160
		},{ 
			dataIndex	:'tgl_sph',
			header		:'Tgl SPH',
			width		:130
		},{ 
			dataIndex	:'total',
			header		:'Total Harga',
			renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');;
            }
		}],
		height	:160,
	});
	
	var gridSphDetBrg = Ext.create('Ext.grid.Panel', {
		store	:storeSphDetBrg,
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
			field		:{
                xtype			:'numberfield',
                allowBlank		:false,
				
            },
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
		height	:100,
	});
	
	var sphList = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			id   		:'noPrSphDet',
			name 		:'noPrSphDet',
			labelAlign	:'right',
			labelWidth	:100,
			width		:350,
			margin		:'10 5 5 5',
			fieldLabel	:'No PR',
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'tglPrSphDet',
			name 		:'tglPrSphDet',
			labelAlign	:'right',
			labelWidth	:100,
			width		:350,
			margin		:'10 5 5 5',
			fieldLabel	:'Tanggal PR',
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'poolSphDet',
			name 		:'poolSphDet',
			labelAlign	:'right',
			labelWidth	:100,
			width		:350,
			margin		:'10 5 5 5',
			fieldLabel	:'Pool',
			readOnly	:true
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List SPH</b>',
			items	:[gridSphDet]
		},{
			xtype		:'button',
			width		:100,
			text		:'Detail Barang',
			margin		:'0 5 5 10',
			handler		:actDetBrg
		},{
			xtype		:'button',
			width		:100,
			text		:'Disetujui',
			margin		:'0 5 5 10',
			handler		:actSetuju
		}]
	});
	
	var sphDetPanel = Ext.create('Ext.form.Panel', {
		title	:'List Penawaran Harga',
        //width	:500,
		padding	:'10',
		layout	:'anchor',
		items	:[sphList],
		
	});
	
	var sphTab = Ext.create('Ext.tab.Panel', {
		id			:'sphTab',
		autoHeight	:true,
		//width		:800,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[sphPanel,sphDetPanel],
		renderTo	: Ext.getBody()
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
			width		:150
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
		//width	:480
	});
	
	var infoPrField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
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
			id   		:'tglPrDialog',
			name 		:'tglPrDialog',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Tanggal PR',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'poolPrDialog',
			name 		:'poolPrDialog',
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
			items	:[gridInfoPr]
		}]
	});
	
	var infoPrDialog = new Ext.Window({
        id 		:'infoPrDialog', 	
		title	:'Info Detil Purchase Request',
		width	:550,
		height	:370,
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
	
	var gridSph = Ext.create('Ext.grid.Panel', {
		store	:storeGridSph,
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
			dataIndex	:'harga',
			header		:'Harga',
			field		:{
                xtype			:'numberfield',
                allowBlank		:false,
				
            },
			renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');;
            }
		},{ 
			dataIndex	:'jml_brg_fpb',
			header		:'Jumlah',
		},{ 
			dataIndex	:'total',
			header		:'Total Harga',
			renderer: function(value, metaData, record, rowIdx, colIdx, store, view) {
				return Ext.util.Format.number((record.get('harga') * record.get('jml_brg_fpb')), '0,000');
			}
		}],
		selModel: {
            selType: 'cellmodel'
        },
		plugins: [cellEditing],
		height	:160,
		width	:640
	});
	
	var sphField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype		:'textfield',
			id   		:'noSphDialog',
			name 		:'noSphDialog',
			labelAlign	:'right',
			labelWidth	:100,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No SPH',
			allowBlank	:false,
			readOnly	:true
		},{
			xtype		:'textfield',
			id   		:'noSphRefDialog',
			name 		:'noSphRefDialog',
			labelAlign	:'right',
			labelWidth	:100,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'No Referensi SPH',
			allowBlank	:false
		},{
			xtype		: 'combobox',
			width		: 320,
			labelAlign	: 'right',
			margin		: '10 5 5 5',
			labelWidth	: 100,
			fieldLabel	: 'Supplier',
			id			: 'supplierDialog',
			name		: 'supplierDialog',
			store		: storeSup,
			displayField: 'nama_sup',
			valueField	: 'id_sup',
			emptyText	: "Supplier..",
 			editable	:false
		},{
			xtype		:'textfield',
			id   		:'noPrSphDialog',
			name 		:'noPrSphDialog',
			labelAlign	:'right',
			labelWidth	:100,
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
			labelWidth	:100,
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
			labelWidth	:100,
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
			items	:[gridSph]
		},{
			xtype		:'button',
			width		:100,
			text		:'Simpan',
			margin		:'0 5 5 10',
			handler		:actSimpan
			
		}]
	});
	
	var sphDialog = new Ext.Window({
        id 		:'sphDialog', 	
		title	:'Form Entry SPH',
		width	:710,
		//height	:500,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[sphField],
		listeners: {
			close : {
				fn: function(){ sphField.getForm().reset(); }   
			}					
		} 
	});
	
	var sphDetBrgDialog = new Ext.Window({
        id 		:'sphDetBrgDialog', 	
		title	:'Detail Penawaran',
		width	:710,
		height	:260,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[gridSphDetBrg],
		listeners: {
			close : {
				fn: function(){ sphField.getForm().reset(); }   
			}					
		} 
	});
	
	
	function actInfoPr(){
		var dataGrid = gridListPr.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var noPr	= dataGrid[0].raw.no_pr;
			var tglPr	= dataGrid[0].raw.tgl_pr;
			var poolPr	= dataGrid[0].raw.pool_pr;
			
			Ext.getCmp('noPrDialog').setValue(noPr);
			Ext.getCmp('tglPrDialog').setValue(tglPr);
			Ext.getCmp('poolPrDialog').setValue(poolPr);
			infoPrDialog.show();
			storeGridInfoPr.load();
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih PR!');
		}
	}
	
	function actBuatSph(){
		var dataGrid = gridListPr.getSelectionModel().selected.items;
		sphField.getForm().reset();
		if(dataGrid.length>0){
			getNoSph();	
			var statPr	= dataGrid[0].raw.status_pr;
			var noPr	= dataGrid[0].raw.no_pr;
			var tglPr	= dataGrid[0].raw.tgl_pr;
			var poolPr	= dataGrid[0].raw.pool_pr;
			
			if(statPr=='Request' ||statPr=='Review'){
				Ext.getCmp('noPrSphDialog').setValue(noPr);
				Ext.getCmp('tglPrSphDialog').setValue(tglPr);
				Ext.getCmp('poolSphDialog').setValue(poolPr);
				sphDialog.show();
				storeGridSph.load();
			}else{
				Ext.Msg.alert('Information', 'PR No '+noPr+' sudah pernah di proses !');
			}
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih PR!');
		}
	}
	
	function actSimpan(){
		var noSph		= Ext.getCmp('noSphDialog').getValue();
		var noSphRef	= Ext.getCmp('noSphRefDialog').getValue();
		var idSup		= Ext.getCmp('supplierDialog').getValue();
		var idPr	 	= gridListPr.getSelectionModel().selected.items[0].raw.id_pr;
		var gridData	= storeGridSph.data.items;
		var brgData		= '';
		var msgHarga	= 0;
		Ext.Array.each(gridData, function(data, index) {
			var rowindex = data.index;
			var idBrg   = data.raw['id_brg'],
				jmlBrg 	= data.raw['jml_brg_fpb'],
				harga 	= data.data['harga'];
			//console.log();
			if(harga==0){
				msgHarga = msgHarga +1;
			}
			brgData	= idBrg+'#'+jmlBrg+'#'+harga+';'+brgData;
		});

		if(noSph=='' || idSup==null || noSphRef==''){
			Ext.Msg.alert('Informasi', 'Periksa Kelengkapan Isian!');
		}else{
			if(msgHarga>0){
				Ext.Msg.alert('Informasi', 'Pastikan harga yang dicantumkan sudah benar!');
			}else{
				var paramsDat	= {noSph:noSph,noSphRef:noSphRef,idSup:idSup,idPr:idPr,brgData:brgData}
				loadingMask.show();
				Ext.Ajax.request({
					url		:baseUrl + 'sph/actSimpan',
					params	:paramsDat,
					method	:'post',
					success :function(result){
						loadingMask.hide();				
						obj = Ext.JSON.decode(result.responseText);
						Ext.Msg.alert('Informasi', obj.message);
						sphDialog.hide();
						storeGridPr.load();
					}
				});
			}
		}
	}
	
	function actInfoSph(){
		var dataGrid = gridListPr.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var noPr	= dataGrid[0].raw.no_pr;
			var tglPr	= dataGrid[0].raw.tgl_pr;
			var poolPr	= dataGrid[0].raw.pool_pr;
			var statPr	= dataGrid[0].raw.status_pr;
			
			Ext.getCmp('noPrSphDet').setValue(noPr);
			Ext.getCmp('tglPrSphDet').setValue(tglPr);
			Ext.getCmp('poolSphDet').setValue(poolPr);
			storeGridSphDet.load();
			sphTab.setActiveTab(1);
		}
	}
	
	function actDetBrg(){
		var dataGrid = gridSphDet.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idSph	= dataGrid[0].raw.id_sph;
			var noSph	= dataGrid[0].raw.no_sph;
			storeSphDetBrg.proxy.extraParams.idSph = idSph;
			storeSphDetBrg.load();
			sphDetBrgDialog.setTitle('Penawaran Harga SPH : '+noSph);
			sphDetBrgDialog.show();
			
		}else{
			Ext.Msg.alert('Informasi', 'Silahkan pilih barang !');
		}
	}
	
	function actSetuju(){
		var dataGrid = gridSphDet.store.data.items;
		if(dataGrid.length>0){
			var dataGridPr = gridListPr.getSelectionModel().selected.items;
			if(dataGridPr.length>0){
				var statPr	= dataGridPr[0].raw.status_pr;
				var noPr	= dataGridPr[0].raw.no_pr;
				if(statPr!='Review'){
					Ext.Msg.alert('Informasi', 'No PR : '+noPr+' dalam status '+statPr+' !');
				}else{
					var countSph	= 0;
					var idSph		= 0;
					var statSph		= false;
					//console.log(dataGrid);
					Ext.Array.each(dataGrid, function(data, index) {
						var statSph	= data.data.status;
						if(statSph){
							idSph	= data.raw.id_sph;
							countSph 	= countSph + 1;
						}
						
					});
					
					if(countSph>1 || countSph==0){
						Ext.Msg.alert('Informasi', 'Pilih salah satu supplier !');
					}
					
					if(countSph==1){
						appSph(idSph);
					}
				}
			}
		}else{
			Ext.Msg.alert('Informasi', 'Tidak ada supplier yang menawarkan !');
		}
	}
	
	function appSph(idSph){
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'sph/appSph',
			method	:'post',
			params	:{idSph:idSph},
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.Msg.alert('Informasi', obj.message);
				resetAll();
			}
		});
	}
	
	function resetAll(){
		storeSphDetBrg.proxy.extraParams.idSph = 0;
		storeGridPr.load();
		storeGridSphDet.load();
		sphTab.setActiveTab(0);
		Ext.getCmp('noPrSphDet').setValue('');
		Ext.getCmp('tglPrSphDet').setValue('');
		Ext.getCmp('poolSphDet').setValue('');
	}
	
	function getNoSph(){
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'sph/getNoSph',
			method	:'post',
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.getCmp('noSphDialog').setValue(obj.no_sph);
			}
		});
	}
	
	
});
</script>