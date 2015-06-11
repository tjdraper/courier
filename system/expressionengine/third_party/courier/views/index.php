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
					Handle
				</th>
				<th width="100px">
					Member Count
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
			<?php foreach ($lists as $list) { ?>
				<tr class="js-list-edit-parent">
					<td align="center" class="js-list-edit-id">
						<?php echo($list->id); ?>
					</td>
					<td class="js-list-edit-name">
						<a
							href="<?php echo($method_url . 'view_list' . AMP . 'id=' . $list->id); ?>"
							class="js-list-edit-name-value"
						>
							<?php echo($list->list_name); ?>
						</a>
					</td>
					<td class="js-list-edit-handle">
						<?php echo($list->list_handle); ?>
					</td>
					<td>
						<?php echo($list->member_count); ?>
					</td>
					<td align="center">
						<input
							type="checkbox"
							class="js-list-edit"
						>
					</td>
					<td align="center">
						<input
							type="checkbox"
							name="delete[<?php echo($list->id); ?>]"
							value="<?php echo($list->id); ?>"
						>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<br>
	<br>
	<h2>Add a New List</h2>
	<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="50%">
					Name
				</th>
				<th width="50%">
					Handle
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
						name="new[handle]"
						class="js-new-slug"
					>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="text-align: right;">
		<input type="submit" class="submit">
	</div>
</form>