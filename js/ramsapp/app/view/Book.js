Ext.define('ramsapp.view.Book', {
	extend: 'Ext.window.Window',
	alias: 'widget.bookwindow',

	title: 'Книга',
	layout: 'fit',
	autoShow: true,

	initComponent: function() {
		this.items = [{
			xtype: 'form',
			items: [{
				xtype: 'textfield',
				name : 'name',
				fieldLabel: 'Название'
			},{
				xtype: 'textfield',
				name : 'description',
				fieldLabel: 'Описание'
			},{
				xtype: 'textfield',
				name : 'ip',
				fieldLabel: 'IP'
			},{
				xtype: 'numberfield',
				name : 'port',
				fieldLabel: 'Порт',
				minValue: 1024,
				maxValue: 65535
			},{
				xtype: 'numberfield',
				name : 'id_support',
				fieldLabel: 'Порт'
			},{
				xtype: 'textfield',
				name : 'ip_list',
				fieldLabel: 'Набор IP'
			},
				{
				xtype: 'numberfield',
				name : 'year',
				fieldLabel: 'Год',
				minValue: 1
			}]
		}];
		this.dockedItems=[{
			xtype:'toolbar',
			docked: 'top',
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
		}];
		this.buttons = [{
			text: 'Очистить',
			scope: this,
			action: 'clear'
		}];

		this.callParent(arguments);
	}
});