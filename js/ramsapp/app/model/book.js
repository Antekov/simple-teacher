/**
 * Created by Antekov on 22.03.2015.
 */
Ext.define('ramsapp.model.Book', {
	extend: 'Ext.data.Model',
	fields: ['id','name', 'description', 'ip', 'port', 'id_support', 'ip_list', 'is_active']
});