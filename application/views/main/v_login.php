<script type="text/javascript">
Ext.onReady(function(){
	var formLogin = new Ext.FormPanel({
		title	:'Login',
		width	:300,
		style: {
			marginTop	: '250px',
			marginBottom: 'auto',
			marginLeft	: 'auto',
			marginRight	: 'auto'
		},
		items	:[{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:75,
			width		:250,
			margin		:'10 0 5 0',
			name		:'username',
			id			:'username',
			fieldLabel	:'Username',
			allowBlank	:false
		},{
			xtype		:'textfield',
			labelAlign	:'right',
			labelWidth	:75,
			width		:250,
			name		:'password',
			id			:'password',
			inputType	:'password',
			fieldLabel	:'Password',
			allowBlank	:false,
			enableKeyEvent	:true,
			listeners	:{
				keypress:function(me,e,obj){
					if(e.keyCode == 13){
						
						login();
					}
				}
			}
		},{
			xtype		:'button',
			text		:'Login',
			width		:100,
			margin		:'5 0 0 80',
			handler		:login
		}],
		renderTo		:Ext.getBody()
	});
	
	function login(){
		loadingMask.show()
		formLogin.submit({
			url		:baseUrl+'login/userLogin',
			method	:'POST',
			success	:function (form, action) {
				var obj	= Ext.JSON.decode(action.response.responseText);
				if(obj.status){
					window.location = "<?=base_url()?>";
				}else{
					loadingMask.hide();
				}
			},
			failure: function(form, action) {
				var obj	= Ext.JSON.decode(action.response.responseText);
				if(obj.status){
					window.location = "<?=base_url()?>";
				}else{
					loadingMask.hide();
				}
			}
		});
		
	}
});
</script>