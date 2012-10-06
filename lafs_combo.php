<?php
function lafs_opt($data,$data_div) {
	$linksalpha_tag_id = 'linksalpha_tag_'.rand();
	$html = '<div style="'.$style.'" id="'.$linksalpha_tag_id.'" data-fs="'.$data_div.'"></div>';
	$url = '//www.linksalpha.com/social/loader_fs?tag_id='.$linksalpha_tag_id.'&amp;'.$data;
	$html .= '<script type="text/javascript" src="'.$url.'"></script>';
	return $html;
}
?>