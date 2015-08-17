<?php echo form_open($method_url . 'list_import_csv&id=' . $listData['list']->id, array('enctype' => 'multipart/form-data')); ?>
	<input type="file" name="csv_file" required>
	<br>
	<br>
	<input type="submit" class="submit" value="Import CSV">
	<a href="<?php echo($method_url . 'view_list&id=' . $listData['list']->id . AMP . 'csv=true'); ?>" class="submit" download>
		Export CSV
	</a>
</form>
<?php echo form_open($method_url . 'view_list&id=' . $listData['list']->id); ?>
	<input
		type="hidden"
		name="list_id"
		value="<?php echo $listData['list']->id; ?>"
	>
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
	<br>
	<br>
	<h2>Add a Member to This List</h2>
	<select name="existing_member">
		<option value="">Choose a member</option>
		<?php foreach ($members as $member) { ?>
			<option value="<?php echo($member->id); ?>">
				<?php echo($member->member_name); ?> (<?php echo($member->member_email); ?>)
			</option>
		<?php } ?>
	</select>
	<br>
	<br>
	<small>or</small>
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
						name="new_name"
					>
				</td>
				<td>
					<input
						type="text"
						name="new_email"
					>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="text-align: right;">
		<input type="submit" class="submit">
	</div>
</form>