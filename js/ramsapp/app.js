Ext.Loader.setConfig({
	enabled: true
});

Ext.application({
	requires: ['Ext.container.Viewport'],
	name: 'ramsapp',

	appFolder: 'js/ramsapp/app',

	controllers: ['Books'],


	launch: function() {
		Ext.create('Ext.container.Viewport', {
			layout: 'fit',
			items: [{
				xtype: 'booklist'
			}]
		});
	}
});