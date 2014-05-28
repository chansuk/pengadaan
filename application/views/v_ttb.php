<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';
Ext.onReady(function(){
	
	var storeGridPo = Ext.create('Ext.data.JsonStore', {  
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
			{name:'status_po',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('datePo1').getValue();
				date2	= Ext.getCmp('datePo2').getValue();
				noPo	= Ext.getCmp('noPo').getValue();
				
				store.proxy.extraParams.date1	= date1;
				store.proxy.extraParams.date2	= date2;
				store.proxy.extraParams.noPo	= noPo;
			} 
		}
	});
	
	var storeTtb = Ext.create('Ext.data.JsonStore', {  
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
			{name:'jml_brg_sph',type: 'int'},
			{name:'harga_brg_sph',type: 'int'},
			{name:'total',type: 'int'},
		],
		listeners: {
			beforeload: function(store, operation, options){
				noPo	= Ext.getCmp('noPoDialog').getValue();
				store.proxy.extraParams.noPo	= noPo;
			} 
		}
	});
	
	var storeGridTtb = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'retur/getGridTtb',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:['no_ttb','no_po','no_sph','nama_sup','tgl_ttb','status_ttb'],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('dateTtbList1').getValue();
				date2	= Ext.getCmp('dateTtbList2').getValue();
				noTtb	= Ext.getCmp('noTtbList').getValue();
				
				store.proxy.extraParams.date1	= date1;
				store.proxy.extraParams.date2	= date2;
				store.proxy.extraParams.noTtb	= noTtb;
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
			name		:'noPo',
			id			:'noPo',
			width		:310,
			fieldLabel	:'No PO'
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'datePo1',
				id			:'datePo1',
				width		:200,
				fieldLabel	:'Tanggal PO'
			},{
				xtype		:'datefield',
				name		:'datePo2',
				id			:'datePo2',
				width		:100,
				margin		:'0 0 0 10'
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridPo.load();
			}
		}]
	});
	
	var gridListPo = Ext.create('Ext.grid.Panel', {
		store	:storeGridPo,
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
			dataIndex	:'nama_sup',
			header		:'Supplier',
			width		:150
		},{ 
			dataIndex	:'tgl_po',
			header		:'Tanggal PO',
			width		:125
		},{ 
			dataIndex	:'status_po',
			header		:'Status',
		}],
		height	:160,
		//width	:700,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridPo,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},
	});
	
	var listPo = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		//width	:720,
		height	:200,
		padding	:'5',
		title	:'<b>List Purchase Order</b>',
		items	:[gridListPo]
	}
	
	var ttbPanel = Ext.create('Ext.form.Panel', {
		title	:'List Purchase Order',
        //width	:750,
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchForm,listPo,{
			xtype		:'button',
			width		:100,
			text		:'Buat TTB', 
			margin		:'0 5 5 10',
			handler		:actBuatTtb
		}]
	});
	
	var gridTtb = Ext.create('Ext.grid.Panel', {
		store	:storeTtb,
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
	
	var ttbField = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{
			xtype	:'container',
			layout	:'column',
			items	:[{
				xtype	:'container',
				layout	:'anchor',
				items	:[{
					xtype		:'textfield',
					id   		:'noTtbDialog',
					name 		:'noTtbDialog',
					labelAlign	:'right',
					labelWidth	:75,
					width		:320,
					margin		:'10 5 5 5',
					fieldLabel	:'No TTB',
					allowBlank	:false,
					readOnly	:true
				},{
					xtype		:'textfield',
					id   		:'noRefSjDialog',
					name 		:'noRefSjDialog',
					labelAlign	:'right',
					labelWidth	:75,
					width		:320,
					margin		:'10 5 5 5',
					fieldLabel	:'No Surat Jalan',
					allowBlank	:false
				},{
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
					id   		:'tglPoDialog',
					name 		:'tglPoDialog',
					labelAlign	:'right',
					labelWidth	:75,
					width		:320,
					margin		:'10 5 5 5',
					fieldLabel	:'Tanggal PO',
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
				}]
			},{
				xtype		:'textarea',
				id   		:'ketDialog',
				name 		:'ketDialog',
				labelAlign	:'right',
				labelWidth	:75,
				width		:320,
				margin		:'10 5 5 5',
				fieldLabel	:'Keterangan',
				allowBlank	:false
			}]
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Barang</b>',
			items	:[gridTtb]
		},{
			xtype		:'button',
			width		:100,
			id			:'btnSimpan',
			text		:'Simpan',
			margin		:'0 5 5 10',
			handler		:actSimpan
			
		},{
			xtype		:'button',
			width		:100,
			id			:'btnCetak',
			text		:'Cetak',
			margin		:'0 5 5 10',
			handler		:actCetak
			
		}]
	});
	
	var ttbDialog = new Ext.Window({
        id 		:'poDialog', 	
		title	:'Tanda Terima Barang',
		width	:710,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[ttbField],
		listeners: {
			close : {
				fn: function(){ ttbField.getForm().reset(); }   
			}					
		} 
	});
	//--
	var ttbListForm = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noTtbList',
			id			:'noTtbList',
			width		:310,
			fieldLabel	:'No TTB'
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'dateTtbList1',
				id			:'dateTtbList1',
				width		:200,
				fieldLabel	:'Tanggal TTB'
			},{
				xtype		:'datefield',
				name		:'dateTtbList2',
				id			:'dateTtbList2',
				width		:100,
				margin		:'0 0 0 10'
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridTtb.load();
			}
		}]
	});
	
	var gridListTtb = Ext.create('Ext.grid.Panel', {
		store	:storeGridTtb,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'no_ttb',
			header		:'No TTB',
			width		:125
		},{ 
			dataIndex	:'no_po',
			header		:'No PO',
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
			dataIndex	:'tgl_ttb',
			header		:'Tanggal TTB',
			width		:125
		},{ 
			dataIndex	:'status_ttb',
			header		:'Status TTB',
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridTtb,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},
	});
	
	var listTtb = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		height	:200,
		padding	:'5',
		title	:'<b>List Tanda Terima</b>',
		items	:[gridListTtb]
	}
	
	var ttbListPanel = Ext.create('Ext.form.Panel', {
		title	:'List Tanda Terima',
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[ttbListForm,listTtb,{
			xtype		:'button',
			width		:100,
			text		:'Cetak TTB',
			margin		:'0 5 5 10',
			handler		:actCetakTtb
		}],
		
	});
	
	var ttbTab = Ext.create('Ext.tab.Panel', {
		id			:'ttbTab',
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10',
		layout		:'anchor',
		items		:[ttbPanel,ttbListPanel],
		renderTo	: Ext.getBody()
	});
	
	function actBuatTtb(){
		var dataGrid = gridListPo.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			Ext.getCmp('btnSimpan').show();
			Ext.getCmp('btnCetak').hide();
			var statPo	= dataGrid[0].raw.status_po;
			var noPo	= dataGrid[0].raw.no_po;
			if(statPo=='Sent'){
				getNoTtb();
				Ext.getCmp('ketDialog').setReadOnly(false);
				Ext.getCmp('noRefSjDialog').setReadOnly(false);
				
				var tglPo	= dataGrid[0].raw.tgl_po;
				var noSph	= dataGrid[0].raw.no_sph;
				var sup		= dataGrid[0].raw.nama_sup;
				
				
				Ext.getCmp('noPoDialog').setValue(noPo);
				Ext.getCmp('tglPoDialog').setValue(tglPo);
				Ext.getCmp('noSphDialog').setValue(noSph);
				Ext.getCmp('supplierDialog').setValue(sup);
				storeTtb.load();
				ttbDialog.show();
			}else{
				Ext.Msg.alert('Information', 'No PO : '+noPo+' sudah '+statPo+'!');
			}
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih PO!');
		}
	}
	
	function actCetakTtb(){
		var dataGrid = gridListTtb.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			Ext.getCmp('btnSimpan').hide();
			Ext.getCmp('btnCetak').show();
			var noTtb	= dataGrid[0].raw.no_ttb;
			var noRefSj	= dataGrid[0].raw.no_ref_sj;
			var noPo	= dataGrid[0].raw.no_po;
			var tglPo	= dataGrid[0].raw.tgl_po;
			var noSph	= dataGrid[0].raw.no_sph;
			var sup		= dataGrid[0].raw.nama_sup;
			var ketTtb	= dataGrid[0].raw.ket_ttb;
			var statTtb	= dataGrid[0].raw.status_ttb;
			var statPo	= dataGrid[0].raw.status_po;
			
			if(statTtb=='Receive'){
				Ext.getCmp('ketDialog').setReadOnly(true);
				Ext.getCmp('noRefSjDialog').setReadOnly(true);
				
				Ext.getCmp('noTtbDialog').setValue(noTtb);
				Ext.getCmp('noRefSjDialog').setValue(noRefSj);
				Ext.getCmp('noPoDialog').setValue(noPo);
				Ext.getCmp('tglPoDialog').setValue(tglPo);
				Ext.getCmp('noSphDialog').setValue(noSph);
				Ext.getCmp('supplierDialog').setValue(sup);
				Ext.getCmp('ketDialog').setValue(ketTtb);
				storeTtb.load();
				ttbDialog.show();
			}else{
				Ext.Msg.alert('Information', 'No PO : '+noPo+' sudah '+statPo+'!');
			}
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih PO!');
		}
	}
	
	function getNoTtb(){
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'ttb/getNoTtb',
			method	:'post',
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.getCmp('noTtbDialog').setValue(obj.no_ttb);
				
			}
		});
	}
	
	
	function actSimpan(){
		noTtb	= Ext.getCmp('noTtbDialog').getValue();
		sj		= Ext.getCmp('noRefSjDialog').getValue();
		ketTtb	= Ext.getCmp('ketDialog').getValue();
		
		
		if(noTtb=='' || sj=='' || ketTtb==''){
			Ext.Msg.alert('Information', 'Silahkan check form isian !');
		}else{
			var dataGrid = gridListPo.getSelectionModel().selected.items;
			if(dataGrid.length>0){
				var idPo	= dataGrid[0].raw.id_po;
				params		= {noTtb:noTtb,sj:sj,ketTtb:ketTtb,idPo:idPo}
				loadingMask.show();
				Ext.Ajax.request({
					url		:baseUrl + 'ttb/actSimpan',
					method	:'post',
					params	:params,
					success :function(result){
						loadingMask.hide();				
						obj = Ext.JSON.decode(result.responseText);
						Ext.Msg.alert('Information', obj.message);
						Ext.getCmp('noRefSjDialog').setValue('');
						Ext.getCmp('ketDialog').setValue('');
						ttbDialog.hide();
						storeGridPo.load();
					}
				});
			}
		}
		
	}
	
	function actCetak(){
		var dataGrid = gridListTtb.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idTtb		= dataGrid[0].raw.id_ttb;
			var	paramExp	= 'ttb/cetak/idTtb/'+idTtb;
			window.open(baseUrl + paramExp, "_blank");
		}
	}
	
	getNoTtb();
});
</script>