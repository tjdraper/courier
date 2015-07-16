<?php
$length = count($csv_items);
$i = 1;
?>
<?php foreach ($csv_items as $heading) {
	echo('"' . $heading . '"');

	if ($i !== $length) {
		echo(',');
	}

	$i++;
} ?>

<?php  foreach ($$csv_item_variable as $body) {
	$i = 1;

	foreach ($csv_items as $key => $val) {
		echo('"' . $body->{$key} . '"');

		if ($i !== $length) {
			echo(',');
		}

		$i++;
	}

	echo('
');
} ?>