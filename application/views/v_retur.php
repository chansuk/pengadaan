<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';

Ext.onReady(function(){
	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });
	
	function columnWrap(val){
		return '<div style="white-space:normal !important;">'+ val +'</div>';
	}

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
				date1	= Ext.getCmp('dateTtb1').getValue();
				date2	= Ext.getCmp('dateTtb2').getValue();
				noTtb	= Ext.getCmp('noTtb').getValue();
				
				store.proxy.extraParams.date1	= date1;
				store.proxy.extraParams.date2	= date2;
				store.proxy.extraParams.noTtb	= noTtb;
			} 
		}
	});
	
	var storeGridRetur = Ext.create('Ext.data.JsonStore', {  
		autoLoad :true,
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'retur/getGridRet',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows',
				totalProperty:'countrow'
			}
		},
		fields 	:['no_ret','no_ttb','tgl_ret','ket_ret','nama_sup'],
		listeners: {
			beforeload: function(store, operation, options){
				date1	= Ext.getCmp('dateRet1').getValue();
				date2	= Ext.getCmp('dateRet2').getValue();
				noTtb	= Ext.getCmp('noTtbSrc').getValue();
				noRet	= Ext.getCmp('noRetSrc').getValue();
				
				store.proxy.extraParams.date1	= date1;
				store.proxy.extraParams.date2	= date2;
				store.proxy.extraParams.noTtb	= noTtb;
				store.proxy.extraParams.noRet	= noRet;
			} 
		}
	});
	
	var storeGridBrg = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'retur/getGridBrg',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows'
			}
		},
		fields 	:['kode_brg','nama_brg','part_no','jml_brg_sph','jml_brg_ret',
				{name:'retur',type: 'bool'}],
		
	}); 
	
	var storeGridBrgFu = Ext.create('Ext.data.JsonStore', {  
		pageSize:10,
		proxy:{
			type	:'ajax',
			url		:baseUrl + 'retur/getGridBrgFu',
			actionMethods :{
				read   : 'GET',
			},
			reader	:{
				type:'json',
				root:'rows'
			}
		},
		fields 	:['kode_brg','nama_brg','part_no','jml_brg_ret',
				{name:'terima',type: 'bool'}],
		listeners: {
			beforeload: function(store, operation, options){
				noRet	= Ext.getCmp('noRetFuDialog').getValue();
				store.proxy.extraParams.noRet	= noRet;
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
			name		:'noTtb',
			id			:'noTtb',
			width		:310,
			fieldLabel	:'No TTB'
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'dateTtb1',
				id			:'dateTtb1',
				width		:200,
				fieldLabel	:'Tanggal Retur'
			},{
				xtype		:'datefield',
				name		:'dateTtb2',
				id			:'dateTtb2',
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
	
	var ttbPanel = Ext.create('Ext.form.Panel', {
		title	:'List Tanda Terima',
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchForm,listTtb,{
			xtype		:'button',
			width		:100,
			text		:'Buat Retur',
			margin		:'0 5 5 10',
			handler		:actBuatRet
		}],
		
	});
	
	var searchRet = Ext.create('Ext.form.Panel', {
        border	:0,
		layout	:'anchor',
		padding	:'10 10 10 10',
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noRetSrc',
			id			:'noRetSrc',
			width		:310,
			fieldLabel	:'No Retur'
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:100,
			name		:'noTtbSrc',
			id			:'noTtbSrc',
			width		:310,
			fieldLabel	:'No TTB'
		},{
			xtype		:'container',
			layout		:'column',
			items		:[{
				xtype		:'datefield',
				labelAlign	:'right',
				labelWidth	:100,
				name		:'dateRet1',
				id			:'dateRet1',
				width		:200,
				fieldLabel	:'Tanggal TTB'
			},{
				xtype		:'datefield',
				name		:'dateRet2',
				id			:'dateRet2',
				width		:100,
				margin		:'0 0 0 10'
			}]
		},{
			xtype		:'button',
			width		:205,
			text		:'Cari',
			margin		:'5 5 5 105',
			handler		:function(){
				storeGridRetur.load();
			}
		}]
	});
	
	var gridListRetur = Ext.create('Ext.grid.Panel', {
		store	:storeGridRetur,
		margin	:'10 0 0 5',
		columns	:[{ 
			xtype :'rownumberer', 
			width :35,
			header:'No',
		},{ 
			dataIndex	:'no_ret',
			header		:'No Retur',
			width		:125
		},{ 
			dataIndex	:'no_ttb',
			header		:'No TTB',
			width		:125
		},{ 
			dataIndex	:'tgl_ret',
			header		:'Tanggal Retur',
			width		:125
		},{ 
			dataIndex	:'nama_sup',
			header		:'Supplier',
			width		:125
		},{ 
			dataIndex	:'ket_ret',
			header		:'Keterangan Retur',
			width		:200,
			renderer	:columnWrap
		}],
		height	:160,
		bbar:{
			xtype	: 'pagingtoolbar',
			store 	: storeGridRetur,				
			displayInfo: true,
			displayMsg: 'Displaying {0} - {1} of {2}',
		},
	});
	
	var listRet = {
		xtype	:'fieldset',
		layout	:'anchor',
		margin	:'0 10 10 10',
		height	:200,
		padding	:'5',
		title	:'<b>List Retur Barang</b>',
		items	:[gridListRetur]
	}
	
	var returPanel = Ext.create('Ext.form.Panel', {
		title	:'List Retur Barang',
		margin	:'10 10 10 10',
		layout	:'anchor',
		items	:[searchRet,listRet,{
			xtype		:'button',
			width		:100,
			text		:'Cetak Retur',
			margin		:'0 5 5 10',
			handler		:actCetakShow
		},{
			xtype		:'button',
			width		:100,
			text		:'Terima Retur',
			margin		:'0 5 5 10',
			handler		:actTerima
		}],
		
	});
	
	var returTab = Ext.create('Ext.tab.Panel', {
		id			:'returTab',
		autoHeight	:true,
		bodyPadding	:10,
		margin		:'10 10 10 10',
		layout		:'anchor',
		items		:[ttbPanel,returPanel],
		renderTo	: Ext.getBody()
	});
	
	var gridRetFu = Ext.create('Ext.grid.Panel', { 
		store	:storeGridBrgFu,
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
			dataIndex	:'jml_brg_ret',
			header		:'Jumlah Retur',
		},{ 
			xtype		:'checkcolumn',
			dataIndex	:'terima',
			header		:'Terima',
			listeners	:{
				beforecheckchange:function(me,rowIndex,checked,e){
					var statCheck = storeGridBrgFu.data.items[rowIndex].raw.terima;
					
					if(statCheck=='1'){
						return false;
					}
					
				}
			}
		}],
		height	:160
		
	});
	
	var retFuField = new Ext.FormPanel({ 				
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
					id   		:'noRetFuDialog',
					name 		:'noRetFuDialog',
					labelAlign	:'right',
					labelWidth	:75,
					width		:320,
					margin		:'10 5 5 5',
					fieldLabel	:'No Retur',
					allowBlank	:false,
					readOnly	:true
				},{
					xtype		:'textfield',
					id   		:'noTtbFuDialog',
					name 		:'noTtbFuDialog',
					labelAlign	:'right',
					labelWidth	:75,
					width		:320,
					margin		:'10 5 5 5',
					fieldLabel	:'No TTB',
					allowBlank	:false,
					readOnly	:true
				},{
					xtype		:'textfield',
					id   		:'tglRetFuDialog',
					name 		:'tglRetFuDialog',
					labelAlign	:'right',
					labelWidth	:75,
					width		:320,
					margin		:'10 5 5 5',
					fieldLabel	:'Tanggal Retur',
					allowBlank	:false,
					readOnly	:true
				},{
					xtype		:'textfield',
					id   		:'supRetFuDialog',
					name 		:'supRetFuDialog',
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
				id   		:'ketFuDialog',
				name 		:'ketFuDialog',
				labelAlign	:'right',
				labelWidth	:75,
				width		:320,
				margin		:'10 5 5 5',
				fieldLabel	:'Keterangan',
				allowBlank	:false,
				readOnly	:true
			}]
		},{
			xtype	:'fieldset',
			layout	:'anchor',
			padding	:'10',
			margin	:'10 5 10 10',
			title	:'<b>List Barang</b>',
			items	:[gridRetFu]
		},{
			xtype		:'button',
			width		:100,
			id			:'btnCetak',
			text		:'Cetak',
			margin		:'0 5 5 10',
			handler		:actCetak
			
		},{
			xtype		:'button',
			width		:100,
			text		:'Simpan',
			id			:'btnTerima',
			margin		:'0 5 5 585',
			handler		:actSimpanFu
			
		}]
	});
	
	var retFuDialog = new Ext.Window({
        id 		:'retFuDialog', 	
		title	:'Terima Retur Barang',
		width	:710,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[retFuField],
		listeners: {
			close : {
				fn: function(){ retFuField.getForm().reset(); }   
			}					
		} 
	});
	
	var gridRet = Ext.create('Ext.grid.Panel', { 
		store	:storeGridBrg,
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
			dataIndex	:'jml_brg_sph',
			header		:'Jumlah Terima',
		},{ 
			dataIndex	:'jml_brg_ret',
			header		:'Jumlah Retur',
			editor		:{
                xtype		:'numberfield',
                allowBlank	:false,
				enableKeyEvents:true,
				minValue	:1,
				listeners	:{
					keypress:function(me,e){
						
						var datGrid = gridRet.getSelectionModel().selected.items;
						if(datGrid.length>0){
							me.setMaxValue(datGrid[0].data.jml_brg_sph);
						}
					}
				}
            },
			renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');
            }
		},{ 
			xtype		:'checkcolumn',
			dataIndex	:'retur',
			header		:'Retur'
		}],
		height	:160,
		width	:640,
		selModel: {
            selType: 'cellmodel'
        },
		plugins: [cellEditing],
	});
	
	var retField = new Ext.FormPanel({ 				
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
					id   		:'noRetDialog',
					name 		:'noRetDialog',
					labelAlign	:'right',
					labelWidth	:75,
					width		:320,
					margin		:'10 5 5 5',
					fieldLabel	:'No Retur',
					allowBlank	:false,
					readOnly	:true
				},{
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
					id   		:'tglTtbDialog',
					name 		:'tglTtbDialog',
					labelAlign	:'right',
					labelWidth	:75,
					width		:320,
					margin		:'10 5 5 5',
					fieldLabel	:'Tanggal TTB',
					allowBlank	:false,
					readOnly	:true
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
			items	:[gridRet]
		},{
			xtype		:'button',
			width		:100,
			text		:'Simpan',
			margin		:'0 5 5 585',
			handler		:actSimpan
			
		}]
	});
	
	var retDialog = new Ext.Window({
        id 		:'retDialog', 	
		title	:'Retur Barang',
		width	:710,
		closable:true,
		closeAction:'hide',
		resizable:false,               
		plain	:true,
		layout	:'fit',
		border	:false,
		overflow:'hidden',
		modal	:true,
		items	:[retField],
		listeners: {
			close : {
				fn: function(){ retField.getForm().reset(); }   
			}					
		} 
	});
	
	function getNoRet(){
		loadingMask.show();
		Ext.Ajax.request({
			url		:baseUrl + 'retur/getNoRet',
			method	:'post',
			success :function(result){
				loadingMask.hide();				
				obj = Ext.JSON.decode(result.responseText);
				Ext.getCmp('noRetDialog').setValue(obj.no_ret);
				
			}
		});
	}
	
	function actBuatRet(){
		var dataGrid = gridListTtb.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			getNoRet();
			var noTtb	= dataGrid[0].raw.no_ttb;
			var tglTtb	= dataGrid[0].raw.tgl_ttb;
			var noPo	= dataGrid[0].raw.no_po;
			var nmSup	= dataGrid[0].raw.nama_sup;
			var idSph	= dataGrid[0].raw.id_sph;
			var statTtb	= dataGrid[0].raw.status_ttb;
			
			if(statTtb=='Retur'){
				Ext.Msg.alert('Information', 'TTB No : '+noTtb+' masih proses retur !');
			}else{
				if(statTtb=='Retur Complete'){
					Ext.Msg.alert('Information', 'TTB No : '+noTtb+' sudah terima dari retur !');
				}else{
					storeGridBrg.load({params:{idSph:idSph}});
					Ext.getCmp('noTtbDialog').setValue(noTtb);
					Ext.getCmp('tglTtbDialog').setValue(tglTtb);
					Ext.getCmp('noPoDialog').setValue(noPo);
					Ext.getCmp('supplierDialog').setValue(nmSup);
					retDialog.show();
				}
			}
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih TTB !');
		}
		
	}
	
	function actTerima(){
		var dataGrid = gridListRetur.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			gridRetFu.columns[5].setVisible(true);
			retFuDialog.setTitle('Terima Retur Barang');
			Ext.getCmp('btnTerima').show();
			Ext.getCmp('btnCetak').hide();
			var noTtb	= dataGrid[0].raw.no_ttb;
			var noRet	= dataGrid[0].raw.no_ret;
			var nmSup	= dataGrid[0].raw.nama_sup;
			var tglRet	= dataGrid[0].raw.tgl_ret;
			var ketRet	= dataGrid[0].raw.ket_ret;
			Ext.getCmp('noRetFuDialog').setValue(noRet);
			Ext.getCmp('noTtbFuDialog').setValue(noTtb);
			Ext.getCmp('tglRetFuDialog').setValue(tglRet);
			Ext.getCmp('supRetFuDialog').setValue(nmSup);
			Ext.getCmp('ketFuDialog').setValue(ketRet);
			storeGridBrgFu.load();
			retFuDialog.show();
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih Retur !');
		}
		
	}
	
	function actSimpanFu(){
		var gridData	= storeGridBrgFu.data.items;
		var brgData		= '';
		var checkTer	= 0;
		Ext.Array.each(gridData, function(data, index) {
			var idBrg   = data.raw['id_brg'],
				idRet	= data.raw['id_ret'],
				terima 	= data.data['terima']
				terimaR	= data.raw['terima'];
			
			if(terima&&(terimaR!='1')){
				brgData		= idBrg+'#'+idRet+';'+brgData;
				checkTer	= checkTer+1;
			}
		});
		
		if(checkTer==0){
			Ext.Msg.alert('Information', 'Check kembali barang yang belum terima dari retur !');
		}else{
			var dataGrid = gridListRetur.getSelectionModel().selected.items;
			if(dataGrid.length>0){
				var idRet	= dataGrid[0].raw.id_ret;
				var noRet	= Ext.getCmp('noRetFuDialog').getValue();
				
				jParams	= {idRet:idRet,noRet:noRet,brgData:brgData}

				loadingMask.show();
				Ext.Ajax.request({
					url		:baseUrl + 'retur/actSimpanFu',
					method	:'post',
					params	:jParams,
					success :function(result){
						loadingMask.hide();				
						obj = Ext.JSON.decode(result.responseText);
						Ext.Msg.alert('Information', obj.message);
						storeGridBrgFu.load();
						storeGridTtb.load();
						retFuDialog.hide();
					}
				});
			}
		}
		
	}
	
	function actSimpan(){
		var gridData	= storeGridBrg.data.items;
		var brgData		= '';
		
		var msgBrg		= 'Masukkan Jumlah Retur :<br/>';
		
		var checkRet	= 0;
		
		Ext.Array.each(gridData, function(data, index) {
			var idBrg   = data.raw['id_brg'],
				nmBrg   = data.raw['nama_brg'],
				jmlBrg 	= data.data['jml_brg_ret'],
				retur 	= data.data['retur'];
			
			if(retur){
				if(jmlBrg==''){
					msgBrg		= msgBrg+nmBrg+'<br/>';
				}
				
				checkRet	= checkRet + 1;
				brgData		= idBrg+'#'+jmlBrg+';'+brgData;
			}
		});
		
		if(checkRet==0){
			Ext.Msg.alert('Information', 'Silahkan pilih barang yang diretur !');
		}else{
			var dataGrid = gridListTtb.getSelectionModel().selected.items;
			if(dataGrid.length>0){
				if(msgBrg.length>28){
					Ext.Msg.alert('Information', msgBrg);
				}else{
					var idTtb	= dataGrid[0].raw.id_ttb;
					var noRet	= Ext.getCmp('noRetDialog').getValue();
					var ketRet	= Ext.getCmp('ketDialog').getValue();
					
					if(ketRet==''){
						Ext.Msg.alert('Information', 'Silahkan isi keterangan !');
					}else{
						jParams	= {idTtb:idTtb,noRet:noRet,ketRet:ketRet,brgData:brgData}
					
						loadingMask.show();
						Ext.Ajax.request({
							url		:baseUrl + 'retur/actSimpan',
							method	:'post',
							params	:jParams,
							success :function(result){
								loadingMask.hide();				
								obj = Ext.JSON.decode(result.responseText);
								Ext.Msg.alert('Information', obj.message);
								Ext.getCmp('ketDialog').setValue('');
								retDialog.hide();
								storeGridTtb.load();

							}
						});
					}
				}
				
			}
		}
	}
	
	function actCetakShow(){
		var dataGrid = gridListRetur.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			gridRetFu.columns[5].setVisible(false);
			retFuDialog.setTitle('Cetak Retur Barang');
			Ext.getCmp('btnTerima').hide();
			Ext.getCmp('btnCetak').show();
			var noTtb	= dataGrid[0].raw.no_ttb;
			var noRet	= dataGrid[0].raw.no_ret;
			var nmSup	= dataGrid[0].raw.nama_sup;
			var tglRet	= dataGrid[0].raw.tgl_ret;
			var ketRet	= dataGrid[0].raw.ket_ret;
			Ext.getCmp('noRetFuDialog').setValue(noRet);
			Ext.getCmp('noTtbFuDialog').setValue(noTtb);
			Ext.getCmp('tglRetFuDialog').setValue(tglRet);
			Ext.getCmp('supRetFuDialog').setValue(nmSup);
			Ext.getCmp('ketFuDialog').setValue(ketRet);
			storeGridBrgFu.load();
			retFuDialog.show();
		}else{
			Ext.Msg.alert('Information', 'Silahkan pilih Retur !');
		}
	}
	
	function actCetak(){
		var dataGrid = gridListRetur.getSelectionModel().selected.items;
		if(dataGrid.length>0){
			var idRet		= dataGrid[0].raw.id_ret;
			var	paramExp	= 'retur/cetak/idRet/'+idRet;
			window.open(baseUrl + paramExp, "_blank");
		}
	}
});
</script>