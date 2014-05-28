<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';

Ext.onReady(function(){
	
	var storeGridBus = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'bus/getGridBus',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:['id_bus','nopol_bus','koridor_bus'],
		listeners: {
			beforeload: function(store, operation, options){
				idBus	= Ext.getCmp('idBusSrc').getValue();
				nopol	= Ext.getCmp('nopolSrc').getValue();
				store.proxy.extraParams.idBus	= idBus;
				store.proxy.extraParams.nopol	= nopol;
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
			name		:'kdBus',
			id			:'kdBus',
			hidden		:true
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'idBus',
			id			:'idBus',
			maxLength	:5,
			width		:300,
			fieldLabel	:'No Body',
			readOnly	:true,
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'nopol',
			id			:'nopol',
			maxLength	:10,
			width		:300,
			fieldLabel	:'No Polisi Bus',
			enforceMaxLength:true,
		},{
			xtype		: 'combobox',
			width		: 300,
			labelAlign	: 'right',
			labelWidth	: 100,
			fieldLabel	: 'Koridor',
			id			: 'koridor',
			name		: 'koridor',
			store		: storeKor,
			displayField: 'koridor',
			valueField	: 'koridor',
			editable	: false,
			emptyText	: "Silahkan pilih ..."
		},{
			xtype		:'button',
			width		:195,
			text		:'Simpan',
			margin		:'5 5 5 105',
			handler		:actSimpan
		}]
	});
	
	
	var gridBus = Ext.create('Ext.grid.Panel', {
		store	:storeGridBus,
		margin	:'10',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'id_bus',
			header		:'No Body',
		},{ 
			dataIndex	:'nopol_bus',
			header		:'Nopol Bus',
		},{ 
			dataIndex	:'koridor_bus',
			header		:'Koridor',
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridBus,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},tbar:[{
			xtype	:'button',
			text	:'<b>Hapus Bus</b>',
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
			text	:'<b>Edit Bus</b>',
			scale	:'small',
			iconCls	:'icon-del',
			handler	:actUbah
		}]
	});
	
	var listBus = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'10',
		height	:200,
		padding	:'5',
		title	:'<b>List Bus</b>',
		items	:[gridBus]
	}
	
	var busSrc = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'idBusSrc',
			id			:'idBusSrc',
			width		:310,
			fieldLabel	:'No Body',
			maxLength	:5,
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'nopolSrc',
			id			:'nopolSrc',
			width		:310,
			fieldLabel	:'Nopol',
			maxLength	:10,
			enforceMaxLength:true,
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridBus.load();
			}
		}]
	});
	
	var busPanel = Ext.create('Ext.form.Panel', {
		title	:'List Barang',
        margin	:'10',
		layout	:'anchor',
		items	:[busSrc,listBus],
	});
	
	
	var busForm = Ext.create('Ext.form.Panel', {
		title	:'Form Bus',
		id		:'busForm',
		margin	:'10',
		layout	:'anchor',
		items	:[entryForm]
	});
	
	var busTab = Ext.create('Ext.tab.Panel', {
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[busPanel,busForm],
		renderTo	: Ext.getBody(),
		listeners	:{
			render: function() {
				this.items.each(function(i){
					i.tab.on('click', function(){
						entryForm.getForm().reset();
						getIdBus();
					});
				});
			}
		}
	});
	
	function getIdBus(){
		Ext.Ajax.request({
			url		:baseUrl + 'bus/getIdBus',
			method	:'post',
			success :function(result){
				obj = Ext.JSON.decode(result.responseText);
				entryForm.getForm().reset();
				Ext.getCmp('idBus').setValue(obj);
			}
		});
	}
	
	function actSimpan(){
		var	kdBus		= Ext.getCmp('kdBus').getValue();
		var	idBus		= Ext.getCmp('idBus').getValue();
		var	nopol		= Ext.getCmp('nopol').getValue();
		var	koridor		= Ext.getCmp('koridor').getValue();
		var	paramsJ		= {idBus:idBus,nopol:nopol,koridor:koridor,kdBus:kdBus}
		
		if(nopol==''||koridor==''){
			Ext.Msg.alert('Information', 'Lengkapi form isian !');
		}else{
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'bus/actSimpan',
				method	:'post',
				params	:paramsJ,
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Information', obj.message);
					entryForm.getForm().reset();
					busTab.setActiveTab(0);
					storeGridBus.load();
				}
			});
		}
	}
	
	function actUbah(){
		var dataGrid = gridBus.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idBrg	= dataGrid[0].raw.id_bus;
			var nopol	= dataGrid[0].raw.nopol_bus;
			var koridor	= dataGrid[0].raw.koridor_bus;
			
			Ext.getCmp('idBus').setValue(idBrg);
			Ext.getCmp('kdBus').setValue(idBrg);
			Ext.getCmp('nopol').setValue(nopol);
			Ext.getCmp('koridor').setValue(koridor);
			busTab.setActiveTab(1);
			
		}
	}
	
	function actDel(){
		var dataGrid = gridBus.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idBus	= dataGrid[0].raw.id_bus;
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'bus/actDel',
				method	:'post',
				params	:{idBus:idBus},
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Information', obj.message);
					entryForm.getForm().reset();
					busTab.setActiveTab(0);
					storeGridBus.load();
				}
			});
		}
	}
	
});
</script>