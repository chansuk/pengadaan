<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';

Ext.onReady(function(){
	Ext.define('BarangModel', {
        extend: 'Ext.data.Model',
        fields: [
            'id_brg','kode_brg','nama_brg','part_no','jml_brg_mr'
		]
    });
	
	var storeNmBrg = Ext.create('Ext.data.JsonStore', {  
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'mr/getNmBrg',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json'
			}
		},
		fields:[
			{name:'id_brg',type: 'string'},
			{name:'nama_brg',type: 'string'}
		],
	});
	
	var storeGridMrSrc = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'mr/getGridMrSrc',
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
			{name:'no_mr',type: 'string'},
			{name:'tgl_mr',type: 'string'},
			{name:'status_mr',type: 'string'},
			{name:'id_bus',type: 'string'},
			{name:'koridor_bus',type: 'string'},
		],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('dateMrSrc1').getValue();
				date2	= Ext.getCmp('dateMrSrc2').getValue();
				noMr	= Ext.getCmp('noMrSrc').getValue();
				
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
			url		:baseUrl + 'mr/getGridInfoMr',
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
			{name:'status_brg_mr',type: 'boolean'},
			{name:'status_brg_mr_desc',type: 'string'},
			{name:'stok',type: 'string'}
		],
		listeners: {
			beforeload: function(store, operation, options){
				noMr	= Ext.getCmp('noMrDialog').getValue();
				store.proxy.extraParams.noMr	= noMr;
			} 
		}
	});
	
	var storeGridBarang = Ext.create('Ext.data.Store', {
        model: 'BarangModel',
    });
	
	var entryForm = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noBody',
			id			:'noBody',
			width		:300,
			fieldLabel	:'No Body',
			listeners	:{
				blur:function(me){
					if(me.getValue().length>0){
						getBusData();
					}
				}
			}
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noMr',
			id			:'noMr',
			width		:300,
			fieldLabel	:'No MR',
			readOnly	:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'koridor',
			id			:'koridor',
			width		:300,
			fieldLabel	:'Koridor',
			readOnly 	:true,
		},{
			xtype		:'button',
			width		:195,
			id			:'btnTambahBrg',
			text		:'Tambah Barang',
			margin		:'5 5 5 105',
			disabled	:true,
			handler		:function(){
				dialogCari.show();
			}
		}]
	});
	
	var gridBarang = Ext.create('Ext.grid.Panel', {
		store	:storeGridBarang,
		margin	:'10',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'id_brg',
			header		:'Id Barang',
			hidden		:true
		},{ 
			dataIndex	:'kode_brg',
			header		:'Kode Barang',
		},{ 
			dataIndex	:'nama_brg',
			header		:'Nama Barang',
			width		:210
		},{ 
			dataIndex	:'part_no',
			header		:'Part No',
		},{ 
			dataIndex	:'jml_brg_mr',
			header		:'Jumlah',
		}],
		height	:160,
		//width	:460,
		tbar:[{
			xtype	:'button',
			text	:'<b>Hapus Barang</b>',
			scale	:'small',
			iconCls	:'icon-del',
			handler	:function(){
				var sm = gridBarang.getSelectionModel();
                storeGridBarang.remove(sm.getSelection());
                if (storeGridBarang.getCount() > 0) {
                    sm.select(0);
                }
			}
		}]
	});
	
	var listBarang = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'10 10 10 10',
		//width	:480,
		height	:200,
		padding	:'5',
		title	:'<b>List Barang</b>',
		items	:[gridBarang]
	}
	
	var mrPanel = Ext.create('Ext.form.Panel', {
		title	:'Material Requisition Form',
        margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[entryForm,listBarang,{
			xtype		:'button',
			width		:100,
			text		:'Batal',
			margin		:'0 5 5 10',
			handler		:actBatal
		},{
			xtype		:'button',
			width		:100,
			text		:'Simpan',
			margin		:'0 5 5 0',
			handler		:actSimpan
		}],
		
	});
	
	var searchMr = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noMrSrc',
			id			:'noMrSrc',
			width		:310,
			fieldLabel	:'No MR'
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'dateMrSrc1',
				id			:'dateMrSrc1',
				width		:200,
				fieldLabel	:'Tanggal MR'
			},{
				xtype		:'datefield',
				name		:'dateMrSrc2',
				id			:'dateMrSrc2',
				width		:100,
				margin		:'0 0 0 10'
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridMrSrc.load();
			}
		}]
	});
	
	var gridMrSrc = Ext.create('Ext.grid.Panel', {
		store	:storeGridMrSrc,
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
			header		:'Tanggal',
			width		:125
		},{ 
			dataIndex	:'id_bus',
			header		:'No Body',
			width		:125
		},{ 
			dataIndex	:'koridor_bus',
			header		:'Koridor'
		},{ 
			dataIndex	:'status_mr',
			header		:'Status',
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridMrSrc,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},
	});
	
	var listMrSrc = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		height	:200,
		padding	:'5',
		title	:'<b>List Permintaan Barang</b>',
		items	:[gridMrSrc]
	}
	
	var mrListPanel = Ext.create('Ext.form.Panel', {
		title	:'List Material Requisition',
		id		:'mrListPanel',
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchMr,listMrSrc,{
			xtype		:'button',
			width		:100,
			text		:'Cetak MR',
			margin		:'0 5 5 10',
			handler		:actCetakShow
		}]
	});
	
	var mrTab = Ext.create('Ext.tab.Panel', {
		id			:'mrTab',
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[mrPanel,mrListPanel],
		renderTo	: Ext.getBody()
	});
	
	var cariForm = new Ext.FormPanel({ 				
		frame	:true,
		layout	:'anchor',
		items	:[{		
			xtype		:'combo',
			labelWidth	:75,
			labelAlign	:'right',
			name		:'nmBarang',
			id			:'nmBarang',
			store		:storeNmBrg,
			width		:320,
			fieldLabel	:'Nama Barang',
			displayField:'nama_brg',
			valueField	:'id_brg',
			emptyText	:'Nama barang...',
			typeAhead	:false,
			hideTrigger	:true,
			listConfig: {
				loadingText	: 'Mencari...',
				emptyText	: 'Barang tidak ada.',
			},
			listeners	: {
				change: function(combo, selection) {
					getBrgData(this.value);
				}
			}
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>Info Barang</b>',
			items	:[{
				xtype		:'textfield',
				id   		:'idBrg',
				name 		:'idBrg',
				labelAlign	:'right',
				labelWidth	:75,
				width		:290,
				hidden		:true,
				fieldLabel	:'Kode Barang'
			},{
				xtype		:'textfield',
				id   		:'kdBarang',
				name 		:'kdBarang',
				labelAlign	:'right',
				labelWidth	:75,
				width		:290,
				fieldLabel	:'Kode Barang'
			},{
				xtype		:'textfield',
				id   		:'partNo',
				name 		:'partNo',
				labelAlign	:'right',
				labelWidth	:75,
				width		:290,
				fieldLabel	:'Part No'
			},{
				xtype		:'textfield',
				id   		:'stok',
				name 		:'stok',
				labelAlign	:'right',
				labelWidth	:75,
				width		:290,
				fieldLabel	:'stok'
			}]
		},{
			xtype		:'textfield',
			id   		:'jmlBarang',
			name 		:'jmlBarang',
			labelAlign	:'right',
			labelWidth	:75,
			width		:320,
			margin		:'10 5 5 5',
			fieldLabel	:'Jumlah',
			allowBlank	:false  
		},{
			xtype		:'button',
			width		:100,
			text		:'Tambah',
			margin		:'5 5 5 225',
			handler		:actTambahBrg
		}]
	});
	
	var dialogCari = new Ext.Window({
        id 		:'dialogCari', 	
		title	:'Cari Barang',
		width	:350,
		height	:270,
		closable:true,
		closeAction:'hide',
		resizable:false,
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[cariForm],
		listeners: {
			close : {
				fn: function(){ cariForm.getForm().reset(); }   
			}					
		} 
	});
	
	var gridInfoMr = Ext.create('Ext.grid.Panel', {
		store	:storeGridInfoMr,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
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
			text		:'Cetak',
			margin		:'5 5 5 565',
			handler		:actCetak
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
	
	function getNoMr(){
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'mr/getNoMr',
			method	:'post',
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.getCmp('noMr').setValue(obj.no_mr);
			}
		});
	}
	
	function getBusData(){
		var idBus = Ext.getCmp('noBody').getValue();
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'glocont/getBusData',
			params	:{idBus:idBus},
			method	:'post',
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				
				if(obj.bus.length>0){
					Ext.getCmp('koridor').setValue(obj.bus[0].koridor_bus);
					Ext.getCmp('btnTambahBrg').enable();
				}else{
					Ext.getCmp('btnTambahBrg').disable();
					Ext.Msg.alert('Informasi', 'No Bus '+idBus+' tidak terdaftar!');
				}
			}
		});
	}
	
	function getBrgData(idBrg){
		if(idBrg != null){
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'glocont/getBrgData',
				params	:{idBrg:idBrg},
				method	:'post',
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					
					if(obj.brg.length>0){
						Ext.getCmp('kdBarang').setValue(obj.brg[0].kode_brg);
						Ext.getCmp('partNo').setValue(obj.brg[0].part_no);
						Ext.getCmp('stok').setValue(obj.brg[0].stok);
						Ext.getCmp('idBrg').setValue(obj.brg[0].id_brg);
					}
				}
			});
		}
	}
	
	function actTambahBrg(){
		var idBrg		= Ext.getCmp('idBrg').getValue();
		var nmBarang	= Ext.getCmp('nmBarang').getRawValue();
		var partNo		= Ext.getCmp('partNo').getValue();
		var kdBarang	= Ext.getCmp('kdBarang').getValue();
		var jmlBarang	= Ext.getCmp('jmlBarang').getValue();
		
		if(kdBarang!=''){
			storeGridBarang.add({
				id_brg	:idBrg,
				kode_brg:kdBarang,
				nama_brg:nmBarang,
				part_no	:partNo,
				jml_brg_mr: jmlBarang
			});
			
			cariForm.getForm().reset();
			dialogCari.hide();
		}else{
			Ext.Msg.alert('Informasi', 'Periksa isian barang!');
		}
	}
	
	function actSimpan(){
		var noBody		= Ext.getCmp('noBody').getValue();
		var noMr		= Ext.getCmp('noMr').getValue();
		var gridData	= storeGridBarang.data.items;
		var brgData		= '';
		
		Ext.Array.each(gridData, function(data, index) {
			var rowindex = data.index;
			var idBrg   = data.raw['id_brg'],
				jmlBrg 	= data.raw['jml_brg_mr'];
			
			brgData	= idBrg+'#'+jmlBrg+';'+brgData;
		});
		
		if(brgData=='' || noMr=='' || noBody==''){
			Ext.Msg.alert('Informasi', 'Periksa Kelengkapan Isian!');
		}else{
			var paramsDat	= {noBody:noBody,noMr:noMr,brgData:brgData}
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'mr/actSimpan',
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
	
	function actCetakShow(){
		
		var dataGrid = gridMrSrc.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idBus	= dataGrid[0].raw.id_bus;
			var noMr	= dataGrid[0].raw.no_mr;
			var koridor	= dataGrid[0].raw.koridor_bus;
			var statMr	= dataGrid[0].raw.status_mr;
			if(statMr=='Request'){
				Ext.getCmp('noBodyDialog').setValue(idBus);
				Ext.getCmp('noMrDialog').setValue(noMr);
				Ext.getCmp('koridorDialog').setValue(koridor);
				storeGridInfoMr.load();
				infoMrDialog.show();
			}else{
				Ext.Msg.alert('Information', 'No MR : '+noMr+' dalam status '+statMr+' !');
			}
		}else{
			Ext.Msg.alert('Informasi', 'Silahkan pilih MR !');
		}
	}
	
	function actCetak(){
		var dataGrid = gridMrSrc.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idMr		= dataGrid[0].raw.id_mr;
			var	paramExp	= 'mr/cetak/idMr/'+idMr;
			window.open(baseUrl + paramExp, "_blank");
		}
		
	}
	
	function actBatal(){
		resetAll();
	}
	
	function resetAll(){
		storeGridBarang.removeAll();
		cariForm.getForm().reset();
		entryForm.getForm().reset();
		getNoMr();
		Ext.getCmp('btnTambahBrg').disable();
	}
	
	var loadingMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading..."});
	
	function init(){
		getNoMr();
	}
	init();
});
</script>