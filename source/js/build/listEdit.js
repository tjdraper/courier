COURIER.autoRun.push('listEdit');
COURIER.listEdit = function() {
	$('.js-list-edit').on('click', function() {
		var $el = $(this),
			$parent = $el.parents('.js-list-edit-parent'),
			$name = $parent.children('.js-list-edit-name'),
			$handle = $parent.children('.js-list-edit-handle'),
			$emailAddress = $parent.children('.js-list-edit-email-address'),
			listId = $parent.children('.js-list-edit-id').text().trim();

		$name.html('<input type="text" value="' + $name.children('.js-list-edit-name-value').text().trim() + '" name="update[' + listId + '][list_name]">');

		$handle.html('<input type="text" value="' + $handle.text().trim() + '" name="update[' + listId + '][list_handle]">');

		$emailAddress.html('<input type="text" value="' + $emailAddress.text().trim() + '" name="update[' + listId + '][list_email_address]">');

		$el.off('click');

		$el.on('click', function(e) {
			e.preventDefault();
		});
	});
};