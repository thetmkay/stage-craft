<?php

function include_orphans_table_ajax() {
	add_action( 'admin_footer', 'orphans_table_javascript' );
}

function orphans_table_javascript() {
?>
<script type="text/javascript" >
jQuery(document).ready(function($) {

	$(".post-trash button").click(function(event) {

		event.preventDefault();

		var $form = $(this).parent().parent();

		var post_id = $form.find("[name='post_id']").val();
		console.log(post_id);

		var data = {
			'action': 'stage_post_trash',
			'post_id': post_id,
		};

		$.post(ajaxurl, data, function(response) {
			console.log("Received: " + response);
			location.reload();
		});
	});
});
</script>
<?php
}
