(function($) {
	$('.message .close').on('click', function() {
		$(this).closest('.message').remove();
	});
})(jQuery);