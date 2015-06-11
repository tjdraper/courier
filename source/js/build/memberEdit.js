COURIER.autoRun.push('memberEdit');
COURIER.memberEdit = function() {
	$('.js-member-edit').on('click', function() {
		var $el = $(this),
			$parent = $el.parents('.js-member-edit-parent'),
			$name = $parent.children('.js-member-edit-name'),
			$email = $parent.children('.js-member-edit-email'),
			memberId = $parent.children('.js-member-edit-id').text().trim();

		$name.html('<input type="text" value="' + $name.text().trim() + '" name="update[' + memberId + '][member_name]">');

		$email.html('<input type="text" value="' + $email.text().trim() + '" name="update[' + memberId + '][member_email]">');

		$el.off('click');

		$el.on('click', function(e) {
			e.preventDefault();
		});
	});
};