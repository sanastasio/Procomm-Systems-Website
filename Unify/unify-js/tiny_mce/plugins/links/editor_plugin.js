(function() {

	tinymce.create('tinymce.plugins.links', {
		init : function(ed, url) {
		
			ed.addCommand('mceLinks', function() {
				makeLink();
			});

			ed.addButton('link', {
				title : 'Add or Edit a Link',
				cmd : 'mceLinks'
			});
			
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('link', n.nodeName == 'A');
			});

		},

		createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {
				longname : 'Unify Link Editor',
				author : 'Unit Interactive',
				authorurl : 'http://unitinteractive.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/links',
				version : "1.0"
			};
		}
	});

	tinymce.PluginManager.add('links', tinymce.plugins.links);
})();