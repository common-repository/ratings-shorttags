(function() {	
 	//tinymce.PluginManager.requireLangPack('ratings_shorttags');
	tinymce.create('tinymce.plugins.ratings_mce', {
		init : function(ed, url) {
			
			ed.addButton('rating', {
				title : 'ratings_shorttags.insertRating',
				image : url + '/rating.png',
				onclick : function() {
					insertRating();
				}			
			});

		},


		getInfo : function() {
			return {
				longname : 'Review Ratings MCE Buttons',
				author : 'Joen Asmussen',
				authorurl : 'http://noscope.com/',
				infourl : '',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
		
	});

	// Register plugin
	tinymce.PluginManager.add('ratings_mce', tinymce.plugins.ratings_mce);
	
})();