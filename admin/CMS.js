$(document).ready(function(){

	//Waits ½ a second after document ready to remove loading screen.
	setTimeout(function() {
		$('div.loadingDiv').fadeOut(1000);
	}, 500);
	
	//Collect "tabs/pages"
	$('div.shTab').each(function(i, obj) {
		tabs.push($(obj));
	});

	//dynamically creates show/hide buttons for tabs/pages
	for (var i = 0; i < tabs.length; i++) {
		$('.buttonContainer').append(
				$('<button/>', {
				text: tabs[i].attr('name'),
				id: tabs[i].attr('id'),
				'class': 'btn btn-success',
				'style': 'margin: 2px;',
				click: function (event) {
					$('button.btn').removeAttr('style');
					$(this).css({"border-top":"none","border-right":"thin solid #f5f5f5","border-bottom":"thin solid #f5f5f5","border-left":"none", "background-color": "white", "color":"#990632", "box-shadow":" 2px 2px 3px #CCCCCC inset"});
					for (var i = 0; i < tabs.length; i++) {
						tabs[i].hide('slow');
						if(tabs[i].attr('id') === this.id){
							tabs[i].show('slow');
						}
					}
				}
			})
		);
	}

	//Setting for SVG colorpicker
	$('.SVGspectrumControl').spectrum({
			showPalette: true,
			showSelectionPalette: true,
			showInitial: true,
			showInput: true,
			showAlpha: true,
			preferredFormat: 'hex',
			localStorageKey: 'spectrum.zitecraft'
	});

	
});
