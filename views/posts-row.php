<?php
	function render_posts_table_header() {
?>

<tr>
	<th class="post-title">Post Title</th>
	<th class="post-existing">Existing Copies</th>
	<th class="post-quantity">Quantity</th>
	<th class="post-button"></th>
</tr>

<?php
	}

	function render_posts_row($post, $classes) {

	$classes_string = implode(' ', $classes);
?>
<tr class="<?php echo $classes_string?> table-row posts-form">
	<td class="post-title">
		<a href="<?php echo get_edit_post_link($post->ID); ?>"><?php echo $post->post_title; ?></a>
	</td>
	<td class="post-existing">
		<?php echo $post->stage_children; ?>
	</td>
	<td class="post-quantity">
		<input name="post_quantity" type="number" value="1" min="1">
	</td>
	<td class="post-button">
		<input name="post_id" type="hidden" value="<?php echo $post->ID ?>">
		<input type="submit" value="Add" class="button button-primary button-large">
	</td>
</tr>
<?php
}

	function render_orphans_table_header() {
?>

<tr>
	<th class="post-title">Post Title</th>
	<th class="post-is-parent">Is Parent</th>
	<th class="post-trash"></th>
</tr>

<?php
	}

	function render_orphans_row($post, $classes) {

	$classes_string = implode(' ', $classes);
?>
<tr class="<?php echo $classes_string?>">
	<td class="post-title">
		<a href="<?php echo get_edit_post_link($post->ID); ?>"><?php echo $post->post_title; ?></a>
	</td>
	<td class="post-is-parent">
		<?php echo $post->stage_children; ?>
	</td>
	<td class="post-trash">
		<input name="post_id" type="hidden" value="<?php echo $post->ID ?>">
		<button class="button button-primary button-large">Trash</button>
	</td>
</tr>
<?php
}
