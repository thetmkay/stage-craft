<?php

function include_posts_table_ajax() {
	add_action( 'admin_footer', 'posts_table_javascript' );
}

function posts_table_javascript() {
?>
<script type="text/javascript" >
jQuery(document).ready(function($) {

	$(".post-button input[type='submit']").click(function(event) {

		event.preventDefault();

		var $form = $(this).parent().parent();

		var post_id = $form.find("[name='post_id']").val();
		var post_quantity = $form.find("[name='post_quantity']").val();
		console.log(post_id);
		console.log(post_quantity);

		var data = {
			'action': 'stage_post',
			'post_id': post_id,
			'post_quantity': post_quantity
		};

		console.log(data);

		$.post(ajaxurl, data, function(response) {
			console.log("Received: " + response);
			location.reload();

		});
	});
});
</script>
<?php
}
