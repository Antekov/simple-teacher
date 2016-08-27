/*
Ext.Loader.setPath('Ext.ux', '/js/extjs/examples/ux');

Ext.require([
	'Ext.ux.CheckColumn'
]);
*/
Ext.define('ramsapp.view.BookList' ,{
	extend: 'Ext.grid.Panel',
	alias: 'widget.booklist',

	title: 'Межсетевые экраны',
	store: 'BookStore',
	loadMask: true,
	width: 700,
	height: 500,

	initComponent: function() {
		this.columns = [
			{header: 'Название',  dataIndex: 'name',  flex: 1},
			{header: 'IP',  dataIndex: 'ip',  flex: 1},
			{header: 'Порт', dataIndex: 'port', flex: 1},
			{header: 'Тип', dataIndex: 'id_support',
				width: 130
			},
			{header: 'Набор IP', dataIndex: 'ip_list', flex: 1},
			{header: 'Активен', dataIndex: 'is_active', width: 130}
		];

		this.dockedItems=[{
			xtype:'toolbar',
			dock: 'top',
			items: [{
				text:'Создать',
				iconCls:'new-icon',
				action: 'new'
			},{
				text:'Сохранить',
				iconCls:'save-icon',
				action: 'save'
			},{
				text:'Удалить',
				iconCls:'delete-icon',
				action: 'delete'
			}]
		},{
			xtype: 'pagingtoolbar',
			store:'BookStore',
			dock: 'bottom',
			displayInfo: true,
			beforePageText: 'Страница',
			afterPageText: 'из {0}',
			displayMsg: 'Межсетевые экраны {0} - {1} из {2}'
		}];

		this.callParent(arguments);
	}
});