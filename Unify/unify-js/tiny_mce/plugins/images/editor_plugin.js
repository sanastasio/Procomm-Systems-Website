(function() {

	tinymce.create('tinymce.plugins.images', {
		init : function(ed, url) {
			ed.addCommand('mceImages', function() {
				
				imageEditBuilder(false);
				
			});

			ed.addButton('image', {
				title : 'Add or Edit an Image',
				cmd : 'mceImages'
			});
			
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('image', n.nodeName == 'IMG');
			});

		},

		createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {
				longname : 'Unify Image Editor',
				author : 'Unit Interactive',
				authorurl : 'http://unitinteractive.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/images',
				version : "1.0"
			};
		}
	});

	tinymce.PluginManager.add('images', tinymce.plugins.images);
})();
