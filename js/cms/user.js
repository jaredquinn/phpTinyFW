Ext.onReady(function(){

var fm = Ext.form;

var User = Ext.data.Record.create([{ name: 'id', type: 'integer' },
				   { name: 'realName', type: 'string' },
				   { name: 'userName', type: 'string' },
				   { name: 'clear-password', type: 'string' },
				   { name: 'active', type: 'bool' }  ]);

var store = new Ext.data.JsonStore({ 
	url: '/cms/user/adapter',
	root: 'User',
	totalProperty: 'total',
  	remoteSort: true,
	autoLoad: false,
	fields: [ 'id', 'realName', 'userName', 'clear-password', 'active' ],
	writer: new Ext.data.JsonWriter({ listful: true, writeAllFields: true })
});
store.setDefaultSort('userName', 'asc');

var editor = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
});

var grid = new Ext.grid.GridPanel({
	store: store,
	width: 600,
	plugins: [editor],
	stripeRows: true,
	renderTo: 'editor-grid',
	height: 400,
	title: 'Users',
	frame: true,
	clicksToEdit: 1,
	colModel: new Ext.grid.ColumnModel({
	columns: [
	  {
		header: '#',
		dataIndex: 'id',
		width: 15
	  }, {
		header: 'Username',
		dataIndex: 'userName',
		width: 75,
		sortable: true,
		editor: new fm.TextField({ allowBlank: false }) 
	  }, {
		header: 'Real Name',
		dataIndex: 'realName',
		width: 125,
		sortable: true,
		editor: new fm.TextField({ allowBlank: false })
	  }, {
		header: 'Password',
		dataIndex: 'clear-password',
		width: 75,
		renderer: function() { return '*hidden*'; },
		editor: new fm.TextField({ allowBlank: true, 
					   inputType: 'password' })
	  }, {
		header: 'Active',
		dataIndex: 'active',
		width: 25,
		renderer: function(x) { if(x == true) { return 'Yes' } else { return 'No' } },
		editor: new fm.Checkbox()
	  }
	],	
	}),
	viewConfig: { forceFit: true },
	tbar: [{
		text: 'Add User',
		handler: function() {
			var e = new User({
				userName: 'username',	
				realName: 'Real Name',
				active: 'false'
			});
		   	editor.stopEditing();
			store.insert(0, e);
                	grid.getView().refresh();
                	grid.getSelectionModel().selectRow(0);
                	editor.startEditing(0);
		} 
	},
	{
		text: 'Save',
		handler: function() {
			grid.getStore().save();
		}
	} ],
	bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Records {0} - {1}/{2}',
            emptyMsg: "No Records to Display"
        })
});

//add listener to store's load event before you execute store.load():
store.on({
    'load':{
        fn: function(store, records, options){
            //store is loaded, now you can work with it's records, etc.
            console.info('store load, arguments:', arguments);
            console.info('Store count = ', store.getCount());
        },
        scope:this
    },
    'loadexception':{
        //consult the API for the proxy used for the actual arguments
        fn: function(obj, options, response, e){
            console.info('store loadexception, arguments:', arguments);
            console.info('error = ', e);
        },
        scope:this
    }
});

store.load({ params: { start: 0, limit: 10} } );

});

