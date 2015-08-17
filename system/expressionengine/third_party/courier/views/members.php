<?php echo form_open($method_url . 'members'); ?>
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
				<th align="center" width="50px">
					Edit
				</th>
				<th align="center" width="50px">
					Delete
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($members as $member) { ?>
				<tr class="js-member-edit-parent">
					<td align="center" class="js-member-edit-id">
						<?php echo($member->id); ?>
					</td>
					<td class="js-member-edit-name">
						<?php echo($member->member_name); ?>
					</td>
					<td class="js-member-edit-email">
						<?php echo($member->member_email); ?>
					</td>
					<td align="center">
						<input
							type="checkbox"
							class="js-member-edit"
						>
					</td>
					<td align="center">
						<input
							type="checkbox"
							name="delete[<?php echo($member->id); ?>]"
							value="<?php echo($member->id); ?>"
						>
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
					>
				</td>
				<td>
					<input
						type="text"
						name="new[email]"
					>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="text-align: right;">
		<input type="submit" class="submit">
	</div>
</form>