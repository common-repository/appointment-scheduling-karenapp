/* global tinymce */
(function() {
	tinymce.create( 'tinymce.plugins.karenapp_schedule', {

		init : function( editor ) {
			editor.addButton( 'karenapp_schedule', {
				title : 'Add karenapp schedule',
				cmd   : 'karenapp_add_schedule',
				icon  : 'karenapp'
			});
			editor.addCommand('karenapp_add_schedule', function() {
                editor.execCommand( 'mceInsertContent', 0, '[karenapp-schedule /]' );
			});
		},

		createControl : function() {
			return null;
		},

		getInfo : function() {
			return {
				longname : 'karenapp Schedule',
				author   : 'Automattic',
				version  : '1'
			};
		}
	});

	tinymce.PluginManager.add( 'karenapp_schedule', tinymce.plugins.karenapp_schedule );
})();