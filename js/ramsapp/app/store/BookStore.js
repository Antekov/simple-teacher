Ext.define('ramsapp.store.BookStore', {
	extend: 'Ext.data.Store',
	model: 'ramsapp.model.Book',
	autoLoad: true,
	storeId: 'BookStore',
	pageSize: 3,
	proxy: {
		type: 'ajax',
		url: 'services/fwctrl/connection/',
		reader: {
			type: 'json',
			root: 'items',
			successProperty: 'success'
		}
	}
});