<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';

Ext.onReady(function(){
	
	var storeJenkel = Ext.create('Ext.data.Store', {
		data 	: [{"jenkel":"Laki - laki"},{"jenkel":"Perempuan"}],
		fields 	: ['jenkel']
	});
	
	var storeStatus = Ext.create('Ext.data.Store', {
		data 	: <?=$getStatus;?>,
		fields 	: ['status','statusid']
	});
	
	var storeBag = Ext.create('Ext.data.Store', {
		data 	: <?=$getBagian;?>,
		fields 	: ['bagian','id_bag']
	});
	
	var storeGridUser = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'user/getGridUser',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:['username','nama_user','jenkel_user','alamat_user','telepon_user','bagian'],
		listeners: {
			beforeload: function(store, operation, options){
				user	= Ext.getCmp('userSrc').getValue();
				store.proxy.extraParams.user	= user;
			} 
		}
	});
	
	var entryForm = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10',
		items	:[{
			xtype		:'textfield',
			name		:'userId',
			id			:'userId',
			hidden		:true
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'userEnt',
			id			:'userEnt',
			width		:300,
			fieldLabel	:'Username',
			maxLength	:32,
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'userPass',
			id			:'userPass',
			inputType	:'password',
			width		:300,
			fieldLabel	:'Password',
			maxLength	:32,
			enforceMaxLength:true,
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'userDescEnt',
			id			:'userDescEnt',
			width		:300,
			fieldLabel	:'Nama User',
			maxLength	:32,
			enforceMaxLength:true,
		},{
			xtype		: 'combobox',
			width		: 300,
			labelAlign	: 'right',
			labelWidth	: 100,
			fieldLabel	: 'Jenis Kelamin',
			id			: 'jenkelEnt',
			name		: 'jenkelEnt',
			store		: storeJenkel,
			displayField: 'jenkel',
			valueField	: 'jenkel',
			editable	: false,
			emptyText	: "Silahkan pilih ..."
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'telpEnt',
			id			:'telpEnt',
			width		:300,
			fieldLabel	:'Telepon',
			maxLength	:16,
			enforceMaxLength:true,
		},{
			xtype		:'textarea',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'almEnt',
			id			:'almEnt',
			width		:300,
			fieldLabel	:'Alamat',
			maxLength	:128,
			enforceMaxLength:true,
		},{
			xtype		: 'combobox',
			width		: 300,
			labelAlign	: 'right',
			labelWidth	: 100,
			fieldLabel	: 'Bagian',
			id			: 'bagEnt',
			name		: 'bagEnt',
			store		: storeBag,
			displayField: 'bagian',
			valueField	: 'id_bag',
			editable	: false,
			emptyText	: "Silahkan pilih ..."
		},{
			xtype		: 'combobox',
			width		: 300,
			labelAlign	: 'right',
			labelWidth	: 100,
			fieldLabel	: 'Status',
			id			: 'statEnt',
			name		: 'statEnt',
			store		: storeStatus,
			displayField: 'status',
			valueField	: 'statusid',
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
	
	
	var gridUser = Ext.create('Ext.grid.Panel', {
		store	:storeGridUser,
		margin	:'10',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'username',
			header		:'Username',
		},{ 
			dataIndex	:'nama_user',
			header		:'Nama User',
		},{ 
			dataIndex	:'jenkel_user',
			header		:'Jenis Kelamin',
		},{ 
			dataIndex	:'alamat_user',
			header		:'Alamat',
		},{ 
			dataIndex	:'telepon_user',
			header		:'Telepon',
		},{ 
			dataIndex	:'bagian',
			header		:'Bagian',
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridUser,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},tbar:[{
			xtype	:'button',
			text	:'<b>Hapus User</b>',
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
			text	:'<b>Ubah User</b>',
			scale	:'small',
			iconCls	:'icon-del',
			handler	:actUbah
		}]
	});
	
	var listUser = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'10',
		height	:200,
		padding	:'5',
		title	:'<b>List User</b>',
		items	:[gridUser]
	}
	
	var userSrc = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'userSrc',
			id			:'userSrc',
			width		:310,
			fieldLabel	:'Username',
			maxLength	:32,
			enforceMaxLength:true,
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridUser.load();
			}
		}]
	});
	
	var usrPanel = Ext.create('Ext.form.Panel', {
		title	:'List User',
        margin	:'10',
		layout	:'anchor',
		items	:[userSrc,listUser],
	});
	
	
	var userForm = Ext.create('Ext.form.Panel', {
		title	:'Form User',
		id		:'userForm',
		margin	:'10',
		layout	:'anchor',
		items	:[entryForm]
	});
	
	var userTab = Ext.create('Ext.tab.Panel', {
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[usrPanel,userForm],
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
		var	userId		= Ext.getCmp('userId').getValue();
		var	userName	= Ext.getCmp('userEnt').getValue();
		var	userPass	= Ext.getCmp('userPass').getValue();
		var	userDesc	= Ext.getCmp('userDescEnt').getValue();
		var	userJenkel	= Ext.getCmp('jenkelEnt').getValue();
		var	userTelp	= Ext.getCmp('telpEnt').getValue();
		var	userAlm		= Ext.getCmp('almEnt').getValue();
		var	userBag		= Ext.getCmp('bagEnt').getValue();
		var	statEnt		= Ext.getCmp('statEnt').getValue();
		var	paramsJ		= {userName:userName,userPass:userPass,userDesc:userDesc,userJenkel:userJenkel,userTelp:userTelp,userAlm:userAlm,userBag:userBag,userId:userId,statEnt:statEnt}
		
		if(userName==''||userPass==''||userDesc==''||userJenkel==''||userTelp==''||userAlm==''||userBag==''){
			Ext.Msg.alert('Information', 'Lengkapi form isian !');
		}else{
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'user/actSimpan',
				method	:'post',
				params	:paramsJ,
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Information', obj.message);
					entryForm.getForm().reset();
					userTab.setActiveTab(0);
					storeGridUser.load();
				}
			});
		}
		Ext.getCmp('userEnt').setReadOnly(false);
	}
	
	function actUbah(){
		var dataGrid = gridUser.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idUsr	= dataGrid[0].raw.id_usr;
			var usrName	= dataGrid[0].raw.username;
			var usrDesc	= dataGrid[0].raw.nama_user;
			var usrJenkel	= dataGrid[0].raw.jenkel_user;
			var usrAlm	= dataGrid[0].raw.alamat_user;
			var usrTelp	= dataGrid[0].raw.telepon_user;
			var idBag	= dataGrid[0].raw.id_bag;
			var stat	= dataGrid[0].raw.status;
			
			Ext.getCmp('userId').setValue(idUsr);
			Ext.getCmp('userEnt').setValue(usrName);
			Ext.getCmp('userEnt').setReadOnly(true);
			Ext.getCmp('userPass').setValue('xx99999xx');
			Ext.getCmp('userDescEnt').setValue(usrDesc);
			Ext.getCmp('jenkelEnt').setValue(usrJenkel);
			Ext.getCmp('telpEnt').setValue(usrTelp);
			Ext.getCmp('almEnt').setValue(usrAlm);
			Ext.getCmp('bagEnt').setValue(idBag);
			Ext.getCmp('statEnt').setValue(stat);
			userTab.setActiveTab(1);
			
		}
	}
	
	function actDel(){
		var dataGrid = gridUser.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idUsr	= dataGrid[0].raw.id_usr;
			loadingMask.show();
			Ext.Ajax.request({
				url		:baseUrl + 'user/actDel',
				method	:'post',
				params	:{idUsr:idUsr},
				success :function(result){
					loadingMask.hide();				
					obj = Ext.JSON.decode(result.responseText);
					Ext.Msg.alert('Information', obj.message);
					entryForm.getForm().reset();
					userTab.setActiveTab(0);
					storeGridUser.load();
				}
			});
		}
	}
	
});
</script>