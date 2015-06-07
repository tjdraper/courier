<form method="post">
	<input
		type="hidden"
		name="<?php echo ee()->security->get_csrf_token_name(); ?>"
		value="<?php echo ee()->security->get_csrf_hash(); ?>"
	>
	<a href="<?php echo($method_url . 'members' . AMP . 'csv=true'); ?>" class="submit" download>
		Export CSV
	</a>
	<br>
	<br>
	<h2>Members</h2>
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
			<?php foreach ($members as $member) { ?>
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
	<br>
	<br>
	<h2>Add a New Member</h2>
	<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="50%">
					Name
				</th>
				<th width="50%">
					Email Address
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<input
						type="text"
						name="new[name]"
						class="js-name-slug"
						data-slug-target="js-new-slug"
					>
				</td>
				<td>
					<input
						type="text"
						name="new[email]"
						class="js-new-slug"
					>
				</td>
			</tr>
		</tbody>
	</table>
	<input type="submit" class="submit">
</form>