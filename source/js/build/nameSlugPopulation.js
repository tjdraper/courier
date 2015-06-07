COURIER.autoRun.push('nameSlugPopulation');

COURIER.nameSlugPopulation = function() {
	$('.js-name-slug').each(function() {
		var $input = $(this);

		$input.on('keyup', function() {
			$('.' + $input.data('slug-target')).val(COURIER.slugify($input.val()));
		});
	});
};