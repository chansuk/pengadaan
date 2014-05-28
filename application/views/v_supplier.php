<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';

Ext.onReady(function(){
	
	var storeGridSup = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'supplier/getGridSup',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:['nama_sup','alamat_sup','telpon_sup'],
		listeners: {
			beforeload: function(store, operation, options){
				nmSup	= Ext.getCmp('nmSupSrc').getValue();
				store.proxy.extraParams.nmSup	= nmSup;
			} 
		}
	});
	
	var storeKor = Ext.create('Ext.data.Store', {
		data 	:[{"koridor":2},{"koridor":3}],
		fields 	: ['koridor'],
	});
	
	var entryForm = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'idSup',
			id			:'idSup',
			hidden		:true
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'nmSup',
			id			:'nmSup',
			maxLength	:64,
			width		:300,
			fieldLabel	:'Nama Supplier',
			enforceMaxLength:true,
		},{
			xtype		:'textarea',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'almSup',
			id			:'almSup',
			maxLength	:256,
			width		:300,
			fieldLabel	:'Alamat',
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'tlpSup',
			id			:'tlpSup',
			maxLength	:15,
			width		:300,
			fieldLabel	:'Telpon',
			enforceMaxLength:true,
		},{
			xtype		:'button',
			width		:195,
			text		:'Simpan',
			margin		:'5 5 5 105',
			handler		:actSimpan
		}]
	});
	
	
	var gridSup = Ext.create('Ext.grid.Panel', {
		store	:storeGridSup,
		margin	:'10',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'nama_sup',
			header		:'Nama Supplier',
			width		:200
		},{ 
			dataIndex	:'alamat_sup',
			header		:'Alamat',
			width		:200
		},{ 
			dataIndex	:'telpon_sup',
			header		:'Telepon',
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridSup,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},tbar:[{
			xtype	:'button',
			text	:'<b>Hapus Supplier</b>',
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
			text	:'<b>Edit Supplier</b>',
			scale	:'small',
			iconCls	:'icon-del',
			handler	:actUbah
		}]
	});
	
	var listSup = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'10',
		height	:200,
		padding	:'5',
		title	:'<b>List Sup</b>',
		items	:[gridSup]
	}
	
	var supSrc = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'nmSupSrc',
			id			:'nmSupSrc',
			width		:310,
			fieldLabel	:'Nama Supplier',
			maxLength	:64,
			enforceMaxLength:true,
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridSup.load();
			}
		}]
	});
	
	var supPanel = Ext.create('Ext.form.Panel', {
		title	:'List Supplier',
        margin	:'10',
		layout	:'anchor',
		items	:[supSrc,listSup],
	});
	
	
	var supForm = Ext.create('Ext.form.Panel', {
		title	:'Form Supplier',
		id		:'supForm',
		margin	:'10',
		layout	:'anchor',
		items	:[entryForm]
	});
	
	var supTab = Ext.create('Ext.tab.Panel', {
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[supPanel,supForm],
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
		var	idSup		= Ext.getCmp('idSup').getValue();
		var	nmSup		= Ext.getCmp('nmSup').getValue();
		var	almSup		= Ext.getCmp('almSup').getValue();
		var	tlpSup		= Ext.getCmp('tlpSup').getValue();
		var	paramsJ		= {idSup:idSup,nmSup:nmSup,almSup:almSup,tlpSup:tlpSup}
		
		if(nmSup==''||almSup==''||tlpSup==''){
			Ext.Msg.alert('Information', 'Lengkapi form isian !');
		}else{
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'supplier/actSimpan',
				method	:'post',
				params	:paramsJ,
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Information', obj.message);
					entryForm.getForm().reset();
					supTab.setActiveTab(0);
					storeGridSup.load();
				}
			});
		}
	}
	
	function actUbah(){
		var dataGrid = gridSup.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idSup	= dataGrid[0].raw.id_sup;
			var nmSup	= dataGrid[0].raw.nama_sup;
			var almSup	= dataGrid[0].raw.alamat_sup;
			var tlpSup	= dataGrid[0].raw.telpon_sup;
			
			Ext.getCmp('idSup').setValue(idSup);
			Ext.getCmp('nmSup').setValue(nmSup);
			Ext.getCmp('almSup').setValue(almSup);
			Ext.getCmp('tlpSup').setValue(tlpSup);
			supTab.setActiveTab(1);
			
		}
	}
	
	function actDel(){
		var dataGrid = gridSup.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idSup	= dataGrid[0].raw.id_sup;
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'supplier/actDel',
				method	:'post',
				params	:{idSup:idSup},
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Information', obj.message);
					entryForm.getForm().reset();
					supTab.setActiveTab(0);
					storeGridSup.load();
				}
			});
		}
	}
	
});
</script>