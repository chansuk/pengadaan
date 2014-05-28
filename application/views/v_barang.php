<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';

Ext.onReady(function(){
	
	var storeGridBarang = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'barang/getGridBarang',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:['id_brg','kode_brg','nama_brg','part_no','satuan','stok','stok_min','stok_max'],
		listeners: {
			beforeload: function(store, operation, options){
				kdBrg	= Ext.getCmp('kdBrgSrc').getValue();
				nmBrg	= Ext.getCmp('nmBrgSrc').getValue();
				store.proxy.extraParams.kdBrg	= kdBrg;
				store.proxy.extraParams.nmBrg	= nmBrg;
			} 
		}
	});
	
	var storeKdBrg = Ext.create('Ext.data.JsonStore', {  
		pageSize:5,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'barang/getGrupKode',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
			}
		},
		fields:['kode_brg'],
		listeners: {
			beforeload:function(store,records,successful,eOpts){
				store.removeAll();
			}
		}
	});
	
	var entryForm = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10',
		items	:[{
			xtype		:'textfield',
			name		:'idBrg',
			id			:'idBrg',
			hidden		:true
		},{		
			xtype		:'combo',
			labelWidth	:100,
			name		:'kdBrg',
			id			:'kdBrg',
			labelAlign	:'right',
			store		:storeKdBrg,
			width		:300,
			fieldLabel	:'Kode Grup Barang',
			displayField:'kode_brg',
			valueField	:'kode_brg',
			emptyText	:'type kode barang...',
			typeAhead	:false,
			hideTrigger	:true,
			maxLength	:7,
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'nmBrg',
			id			:'nmBrg',
			maxLength	:64,
			width		:300,
			fieldLabel	:'Nama Barang',
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'partNoBrg',
			id			:'partNoBrg',
			maxLength	:16,
			width		:300,
			fieldLabel	:'Part Number',
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'satBrg',
			id			:'satBrg',
			maxLength	:10,
			width		:300,
			fieldLabel	:'Satuan',
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'stokBrg',
			id			:'stokBrg',
			value		:0,
			readOnly	:true,
			width		:300,
			maxLength	:5,
			fieldLabel	:'Stok',
			enforceMaxLength:true,
		},{
			xtype		:'numberfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'stokMinBrg',
			id			:'stokMinBrg',
			width		:300,
			maxLength	:5,
			fieldLabel	:'Stok Min',
			enforceMaxLength:true,
		},{
			xtype		:'numberfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'stokMaxBrg',
			id			:'stokMaxBrg',
			width		:300,
			maxLength	:5,
			fieldLabel	:'Stok Max',
			enforceMaxLength:true,
		},{
			xtype		:'button',
			width		:195,
			text		:'Simpan',
			margin		:'5 5 5 105',
			handler		:actSimpan
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
			dataIndex	:'stok',
			header		:'Stok',
		},{ 
			dataIndex	:'stok_min',
			header		:'Stok Min',
		},{ 
			dataIndex	:'stok_max',
			header		:'Stok Max',
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridBarang,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},tbar:[{
			xtype	:'button',
			text	:'<b>Hapus Barang</b>',
			scale	:'small',
			iconCls	:'icon-del',
			handler	:function(){
				Ext.MessageBox.confirm('Informasi', 'Anda yakin menghapus? ',
					function(btn){
						if (btn == 'yes'){
							actDel();
						}
					}
				);
			}
		},{
			xtype	:'button',
			text	:'<b>Edit Barang</b>',
			scale	:'small',
			iconCls	:'icon-del',
			handler	:actUbah
		}]
	});
	
	var listBarang = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'10',
		height	:200,
		padding	:'5',
		title	:'<b>List Barang</b>',
		items	:[gridBarang]
	}
	
	var brgSrc = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'kdBrgSrc',
			id			:'kdBrgSrc',
			width		:310,
			fieldLabel	:'Kode Barang',
			maxLength	:12,
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'nmBrgSrc',
			id			:'nmBrgSrc',
			width		:310,
			fieldLabel	:'Nama Barang',
			maxLength	:64,
			enforceMaxLength:true,
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridBarang.load();
			}
		}]
	});
	
	var brgPanel = Ext.create('Ext.form.Panel', {
		title	:'List Barang',
        margin	:'10',
		layout	:'anchor',
		items	:[brgSrc,listBarang],
	});
	
	
	var brgForm = Ext.create('Ext.form.Panel', {
		title	:'Form Barang',
		id		:'userForm',
		margin	:'10',
		layout	:'anchor',
		items	:[entryForm]
	});
	
	var brgTab = Ext.create('Ext.tab.Panel', {
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[brgPanel,brgForm],
		renderTo	: Ext.getBody(),
		listeners	:{
			render: function() {
				this.items.each(function(i){
					i.tab.on('click', function(){
						entryForm.getForm().reset();
					});
				});
			}
		}
	});
	
	function actSimpan(){
		var	idBrg		= Ext.getCmp('idBrg').getValue();
		var	kdBrg		= Ext.getCmp('kdBrg').getValue();
		var	nmBrg		= Ext.getCmp('nmBrg').getValue();
		var	satBrg		= Ext.getCmp('satBrg').getValue();
		var	partNoBrg	= Ext.getCmp('partNoBrg').getValue();
		var	stokBrg		= Ext.getCmp('stokBrg').getValue();
		var	stokMinBrg	= Ext.getCmp('stokMinBrg').getValue();
		var	stokMaxBrg	= Ext.getCmp('stokMaxBrg').getValue();
		var	paramsJ		= {idBrg:idBrg,kdBrg:kdBrg,nmBrg:nmBrg,partNoBrg:partNoBrg,stokBrg:stokBrg,stokMinBrg:stokMinBrg,stokMaxBrg:stokMaxBrg,satBrg:satBrg}
		
		if(kdBrg==''||nmBrg==''||stokMinBrg==''||stokMaxBrg==''){
			Ext.Msg.alert('Information', 'Lengkapi form isian !');
		}else{
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'barang/actSimpan',
				method	:'post',
				params	:paramsJ,
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Information', obj.message);
					entryForm.getForm().reset();
					brgTab.setActiveTab(0);
					storeGridBarang.load();
				}
			});
		}
		Ext.getCmp('kdBrg').setReadOnly(false);
	}
	
	function actUbah(){
		var dataGrid = gridBarang.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idBrg	= dataGrid[0].raw.id_brg;
			var kdBrg	= dataGrid[0].raw.kode_brg;
			var nmBrg	= dataGrid[0].raw.nama_brg;
			var partNoBrg	= dataGrid[0].raw.part_no;
			var satBrg	= dataGrid[0].raw.satuan;
			var stokBrg	= dataGrid[0].raw.stok;
			var stokMin	= dataGrid[0].raw.stok_min;
			var stokMax	= dataGrid[0].raw.stok_max;
			
			Ext.getCmp('kdBrg').setReadOnly(true);
			Ext.getCmp('idBrg').setValue(idBrg);
			Ext.getCmp('kdBrg').setValue(kdBrg);
			Ext.getCmp('nmBrg').setValue(nmBrg);
			Ext.getCmp('partNoBrg').setValue(partNoBrg);
			Ext.getCmp('satBrg').setValue(satBrg);
			Ext.getCmp('stokBrg').setValue(stokBrg);
			Ext.getCmp('stokMinBrg').setValue(stokMin);
			Ext.getCmp('stokMaxBrg').setValue(stokMax);
			brgTab.setActiveTab(1);
			
		}
	}
	
	function actDel(){
		var dataGrid = gridBarang.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idBrg	= dataGrid[0].raw.id_brg;
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'barang/actDel',
				method	:'post',
				params	:{idBrg:idBrg},
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Information', obj.message);
					entryForm.getForm().reset();
					brgTab.setActiveTab(0);
					storeGridBarang.load();
				}
			});
		}
	}
	
});
</script>