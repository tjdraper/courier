<form method="post">
	<input
		type="hidden"
		name="<?php echo ee()->security->get_csrf_token_name(); ?>"
		value="<?php echo ee()->security->get_csrf_hash(); ?>"
	>
	<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="40px" align="center">
					ID
				</th>
				<th>
					Name
				</th>
				<th>
					Email
				</th>
				<th width="50px">
					Delete
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($listData['members'] as $member) { ?>
				<tr>
					<td align="center">
						<?php echo($member->id); ?>
					</td>
					<td>
						<?php echo($member->member_name); ?>
					</td>
					<td>
						<?php echo($member->member_email); ?>
					</td>
					<td>

					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</form>