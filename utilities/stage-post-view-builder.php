<?php

function sp_open_tag($tag, $classes = array(), $ids = array()) {

	echo '<' . $tag . ' ';

	if(count($ids)) {
		echo 'id=' . implode(' ', $ids) . '" ';
	}

	if(count($classes)) {
		echo 'class="' . implode(' ', $classes) . '"';
	}

	echo '>';

}

function sp_close_tag($tag) {
	echo '</' . $tag . '>';
}

function sp_html($tag, $content = '', $classes = array(), $ids = array()) {
	sp_open_tag($tag, $classes, $ids);
	echo $content;
	sp_close_tag($tag);
}

function sp_open_div($classes = array(), $ids = array()) {
	sp_open_tag('div', $classes, $ids);
}

function sp_close_div() {
	sp_close_tag('div');
}
