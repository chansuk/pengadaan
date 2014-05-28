<script type="text/javascript">
baseUrl	= '<?=base_url()?>index.php/';
userDesc= '<?=$user_desc?>';
Ext.onReady(function(){
 
    Ext.tip.QuickTipManager.init();

	var detailEl;
	
    var contentPanel = {
        id		:'content-panel',
        region	:'center',
        layout	:'card',
        margins	:'2 5 5 0',
        activeItem:0,
        items	:[{
			id	:'start-panel',
			layout	:'fit',
			title	:'Welcome',
		}]
    };

    var store = Ext.create('Ext.data.TreeStore', {
        root: {
            expanded: true
        },
        proxy: {
            type: 'ajax',
            url: baseUrl+'panel/menuTree'
        }
    });
    
    
    var treePanel = Ext.create('Ext.tree.Panel', {
        id		:'tree-panel',
        title	:'Menu',
        region	:'north',
        split	:true,
        height	:'100%',
        minSize	:150,
        rootVisible:false,
        autoScroll:true,
        store	:store
    });
    
    treePanel.getSelectionModel().on('select', function(selModel, record) {
        if (record.get('leaf')) {
			Ext.getBody().mask('Loading page..','x-mask-loading');
			var tree	= Ext.getCmp('tree-panel');
			var id		= tree.getSelectionModel().selected.items[0].raw.id;
			var title	= tree.getSelectionModel().selected.items[0].raw.text;
			var parentTxt	= tree.getSelectionModel().selected.items[0].parentNode.raw.text;
			//console.log(id);
			Ext.DomHelper.overwrite(
				'start-panel-body',
				{
					tag	:'iframe',
					id	:'ithe',
					src	:baseUrl+id,
					width	:'100%',
					height	:'100%',
					'class'	:'x-docked-noborder-top x-docked-noborder-bottom x-docked-noborder-left x-docked-noborder-right',
					onload	:'Ext.getBody().unmask(); var t = Ext.get(this);'
				}
			);
			Ext.getCmp('start-panel').setTitle(parentTxt+' > '+title);
        }
    });
	
	var topPanel = {
		region	:'north',
		border	:false,
		tbar	:[{
			text    :'<h1 style="color:navy;"><b>Sistem Informasi Pengadaan Barang</b></h1>',
		},{
			xtype	:'tbfill'
		},{
			text    : '<b style="color:navy;">'+userDesc+'</b>',
			iconCls : 'icon-user',
			margin	:'0 10 0 0',
			tooltip : 'My Account',
			menu    : [{
				text     : 'Logoff',
				iconCls  : 'icon-stop',
				handler  : function(){
					Ext.MessageBox.confirm(
						'Logoff'
						,'Are you sure to logoff?'
						,function(btn){
							if(btn=='yes'){
								location.href= baseUrl+'login/logout/'
							}else{
								return false;
							}
						}
					); 
				}
			}]
		}]
	}
	
    Ext.create('Ext.Viewport', {
        layout	:'border',
        items	:[{
            layout: 'border',
            id		:'header',
            region	:'north',
            //html	:'<h1>System Informasi Back To POS</h1>',
            height	:40,
			items	:[topPanel]
        },{
            layout	:'border',
            id		:'layout-browser',
            region	:'west',
            border	:false,
            split	:true,
            margins	:'2 0 5 5',
            width	:275,
            minSize	:100,
            maxSize	:500,
            items	:[treePanel]
        }, 
            contentPanel
        ],
        renderTo: Ext.getBody()
    });
});
</script>