(function() {

	tinymce.create('tinymce.plugins.deflist', {
		init : function(ed, url) {
			ed.addCommand('mceDeflist', function() {
			
				defListBuilder();
				
			});

			ed.addButton('deflist', {
				title : 'Definition List',
				cmd : 'mceDeflist'
			});
			
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('deflist', n.nodeName == 'DT' || n.nodeName == 'DD');
			});

		},
		
		createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {
				longname : 'Definition List',
				author : 'Unit Interactive',
				authorurl : 'http://unitinteractive.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/deflist',
				version : "1.0"
			};
		}
	});

	tinymce.PluginManager.add('deflist', tinymce.plugins.deflist);
})();
