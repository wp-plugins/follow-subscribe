<?php
function lafs_opt($link,$data,$data_div) {
	$linksalpha_tag_id = 'linksalpha_tag_'.rand();
	$html = '<div id="'.$linksalpha_tag_id.'" data-url="'.$link.'" data-fs="'.$data_div.'"></div>';
	$url = '//www.linksalpha.com/social/loader_fs?tag_id='.$linksalpha_tag_id.'&amp;'.$data;
	$html .= '<script type="text/javascript" src="'.$url.'"></script>';
	return $html;
}
?>