jQuery(function() {
	jQuery( '.wc-grow-percentage' ).click(function(){
		console.log( 'clicked the sessions' );
		var slider = jQuery(this).find('.wc-grow-slider');
		var sliderValue = jQuery(this).data('percentage');

		slider.slider({
			min: 0,
			max: 100,
			value: sliderValue,
			slide: function( event, ui ) {
				var parent = jQuery(this).parent();
				parent.find(".wc-grow-percentage-value").html( ui.value );
				parent.find("input.wc-grow-input").val( ui.value );
				parent.data('percentage', ui.value);
			},
			stop: function( event, ui ) {
				slider.slider( "destroy" );
			}
		});

	});

	jQuery('.wc-grow-target-bar').each(function(){
		jQuery(this).find('.wc-grow-target-bar-bar').animate({
			width:jQuery(this).attr('data-percent')
		},3000);
	});
});