if (typeof RedactorPlugins === 'undefined') var RedactorPlugins = {};
RedactorPlugins.clips = {
	init: function() {
		var callback = $.proxy(function() {
			$('#redactor_modal .redactor_clip_link').each($.proxy(function(i,s)	{
				$(s).click($.proxy(function() {
					this.insertClip($(s).next().html());
					return false;
				}, this));
			}, this));
			this.saveSelection();
			this.setBuffer();
		}, this);
		this.addBtn('clips', 'Шаблонные заготовки для редактора', function(obj)	{
			obj.modalInit('Заготовки для редактора', '#clipsmodal', 500, callback);	
		});		
	},
	insertClip: function(html) {
		this.restoreSelection();
		this.execCommand('inserthtml', html);
		this.modalClose();
	}
}