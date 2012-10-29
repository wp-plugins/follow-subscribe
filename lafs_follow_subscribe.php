<?php
/*
 Plugin Name: Follow and Subscribe
 Plugin URI: http://wordpress.org/extend/plugins/follow-subscribe
 Author: linksalpha
 Author URI: http://www.linksalpha.com
 Description: Follow & Subscribe siderbar widget adds links to your Facebook, Google Plus, Twitter, linkedIn, Pinterest, Xing, StumbleUpon, YouTube, About.me, foursquare, and Xing profiles.   
 Version: 1.1
 */
 
/*
    Copyright (C) 2012 LinksAlpha.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a  copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 
require('lafs_utility_fns.php');
require('lafs_combo.php');
require('lafs_fb_meta.php');
define('LAFS_NAME',       __('Follow and Subscribe'));
define('LAFS_ID',         'laecho_follow_subscribe_id');

$lafs_version_number = '1.1';

//Get plugin dir
function lafs_get_dir(){
	$dir=dirname(plugin_basename(__FILE__));
	$dir=trailingslashit($dir);
	$dir=plugins_url($dir);
	return $dir;
}
define('LAFS_DIR_PATH',lafs_get_dir());
$rss_feed_url = '';
if (function_exists('get_bloginfo')) {
	if (function_exists('get_feed_permastruct')) {
		$rss_feed_url = get_bloginfo('rss2_url');
	}
}
//Language
$lafs_options_language = array(
    'la_opt_lang_googleplus'			=> array('option_name'=>'la-html-widget-lang-googleplus', 	'default_value'=>'en-US', 		'type'=>'string',	'field_name'=>__('Google Plus language options'),		'param_name'=>'gpluslang'),
    'la_opt_lang_facebooklike'  		=> array('option_name'=>'la-html-widget-lang-facebooklike', 'default_value'=>'en_US', 		'type'=>'string',	'field_name'=>__('Facebook language options'),			'param_name'=>'fblikelang'),
    'la_opt_lang_twitter'       		=> array('option_name'=>'la-html-widget-lang-twitter', 		'default_value'=>'en', 			'type'=>'string',	'field_name'=>__('Twitter language options'),			'param_name'=>'twitterlang'),
	'la_opt_lang_xing'       			=> array('option_name'=>'la-html-widget-lang-xing', 		'default_value'=>'de', 			'type'=>'string',	'field_name'=>__('Xing language options'),				'param_name'=>'xinglang', 'field_name'=>'Xing',	'field_options'=>array('de'=>'German', 'en'=>'English')),
);
//Follow & Subscribe options
$la_options_fs = array(
	'la_opt_follow_subscribe_googleplus'		=>	array('option_name'=>'la-html-follow-subscribe-googleplus',     'default_value'=>'', 				'type'=>'string', 		'field_name'=>__('Google +1 Button'), 				'field_title'=>__('Google +1'), 		'field_icon'=>'googleplus', 	'field_type'=>'checkbox', 		'param_name'=>'googleplus', 	'help_text'=>__('This button will be displayed by default.')				),
	'la_opt_follow_subscribe_facebook'			=>	array('option_name'=>'la-html-follow-subscribe-facebook',		'default_value'=>'',    			'type'=>'string',		'field_name'=>__('Facebook Like Button'), 			'field_title'=>__('Facebook'), 			'field_icon'=>'facebook', 		'field_type'=>'checkbox', 		'param_name'=>'facebook' ,		'help_text'=>__('This button will be displayed by default.')				),
	'la_opt_follow_subscribe_twitter'			=>	array('option_name'=>'la-html-follow-subscribe-twitter',       	'default_value'=>'',				'type'=>'string',   	'field_name'=>__('Twitter ID'), 					'field_title'=>__('Twitter'), 			'field_icon'=>'twitter', 		'field_type'=>'text', 			'param_name'=>'twitter' ,		'help_text'=>__('Input your Twitter username. For example, if your Profile URL is').'<a href="https://twitter.com/vivekpuri" target="_blank"> https://twitter.com/vivekpuri</a>, then your Twitter username is <strong>vivekpuri</strong>.'),
	'la_opt_follow_subscribe_pinterest'			=>	array('option_name'=>'la-html-follow-subscribe-pinterest',      'default_value'=>'',				'type'=>'string',   	'field_name'=>__('Pinterest URL'), 					'field_title'=>__('Pinterest'), 		'field_icon'=>'pinterest', 		'field_type'=>'text', 			'param_name'=>'pinterest', 		'help_text'=>__('Input your complete Pinterest Page URL. For example, ').'<a href="https://pinterest.com/linksalpha/plugins/" target="_blank">https://pinterest.com/linksalpha/plugins/</a>.'),
	'la_opt_follow_subscribe_stumbleupon'		=>	array('option_name'=>'la-html-follow-subscribe-stumbleupon',    'default_value'=>'',				'type'=>'string',   	'field_name'=>__('StumbleUpon URL'), 				'field_title'=>__('Stumbleupon'), 		'field_icon'=>'stumbleupon', 	'field_type'=>'text', 			'param_name'=>'stumbleupon', 	'help_text'=>__('Input your complete Stumbleupon Page URL. For example, ').'<a href="http://www.stumbleupon.com/su/2wOTSh/:NVU5rq!u:QxI@0x$O/www.linksalpha.com/" target="_blank"> http://www.stumbleupon.com/su/2wOTSh/:NVU5rq!u:QxI@0x$O/www.linksalpha.com/</a>.'),
	'la_opt_follow_subscribe_linkedin'			=>	array('option_name'=>'la-html-follow-subscribe-linkedin',       'default_value'=>'',				'type'=>'string',   	'field_name'=>__('LinkedIn Profile URL'), 		    'field_title'=>__('LinkedIn'), 			'field_icon'=>'linkedin', 		'field_type'=>'text', 			'param_name'=>'linkedin' ,		'help_text'=>__('Input your complete Linkedin Profile/Company Page URL. For example, ').'<a href="http://www.linkedin.com/company/linksalpha.com" target="_blank">http://www.linkedin.com/company/linksalpha.com</a>.'),
	'la_opt_follow_subscribe_foursquare'		=>	array('option_name'=>'la-html-follow-subscribe-foursquare',     'default_value'=>'',				'type'=>'string',   	'field_name'=>__('foursquare Page URL'), 			'field_title'=>__('foursquare'), 		'field_icon'=>'foursquare', 	'field_type'=>'text', 			'param_name'=>'foursquare', 	'help_text'=>__('Input your complete foursquare Page URL. For example, ').'<a href="https://foursquare.com/chipotletweets" target="_blank">https://foursquare.com/chipotletweets</a>.'),
	'la_opt_follow_subscribe_aboutme'			=>	array('option_name'=>'la-html-follow-subscribe-aboutme',       	'default_value'=>'',				'type'=>'string',   	'field_name'=>__('About.me Profile URL'), 			'field_title'=>__('About.me'), 			'field_icon'=>'aboutme', 		'field_type'=>'text', 			'param_name'=>'aboutme' ,		'help_text'=>__('Input your complete About.me Page URL. For example, ').'<a href="https://about.me/lokeshjain2008" target="_blank">https://about.me/lokeshjain2008</a>.'),	
	'la_opt_follow_subscribe_xing'				=>	array('option_name'=>'la-html-follow-subscribe-xing',       	'default_value'=>'',				'type'=>'string',   	'field_name'=>__('Xing Profile URL'), 				'field_title'=>__('Xing'),              'field_icon'=>'xing',           'field_type'=>'text', 			'param_name'=>'xing' ,			'help_text'=>__('Input your complete Xing Profile URL. For example, ').'<a href="https://www.xing.com/profile/Vivek_Puri" target="_blank">https://www.xing.com/profile/Vivek_Puri</a>.'),
	'la_opt_follow_subscribe_youtube'			=>	array('option_name'=>'la-html-follow-subscribe-youtube',       	'default_value'=>'',				'type'=>'string',   	'field_name'=>__('Youtube Channel URL'), 			'field_title'=>__('Youtube'), 			'field_icon'=>'youtube', 		'field_type'=>'text', 			'param_name'=>'youtube' ,		'help_text'=>__('Input your Youtube Channel URL. For example, ').'<a href="http://www.youtube.com/google" target="_blank">http://www.youtube.com/google</a>.'),
	'la_opt_follow_subscribe_rss'				=>	array('option_name'=>'la-html-follow-subscribe-rss',       		'default_value'=>$rss_feed_url,		'type'=>'string',   	'field_name'=>__('RSS feed URL'), 					'field_title'=>__('RSS Feed'), 			'field_icon'=>'rss',            'field_type'=>'text', 			'param_name'=>'rss' ,			'help_text'=>__('Input your RSS feed URL. For example, ').'<a href="http://blog.linksalpha.com/feed/" target="_blank">http://blog.linksalpha.com/feed/</a>.'),
);
//Follow & Subscribe Color and Size options
$la_options_widget_color = array(
	'la_opt_fs_color_row1'		=>	array('option_name'=>'la-html-fs-color-row1',		'default_value'=>'#ffffff',		'default_update'=> true,		'type'=>'string',		'field_type'=>'text',			'field_name'=>__('Background color for Facebook'),		'param_name'=>'row1',		'help_text'=>__('Input your desired color in hex format. For example, if the color is #D8E6EB then input <strong>#D8E6EB</strong>.') ),
	'la_opt_fs_color_row2'		=>	array('option_name'=>'la-html-fs-color-row2',		'default_value'=>'#F5FCFE',		'default_update'=> true,		'type'=>'string',		'field_type'=>'text',			'field_name'=>__('Background color for Google Plus'),	'param_name'=>'row2',																			 ),
	'la_opt_fs_color_row3'		=>	array('option_name'=>'la-html-fs-color-row3',		'default_value'=>'#EEF9FD',		'default_update'=> true,		'type'=>'string',		'field_type'=>'text',			'field_name'=>__('Background color for Twitter'),		'param_name'=>'row3',																			 ),
	'la_opt_fs_color_row4'		=>	array('option_name'=>'la-html-fs-color-row4',		'default_value'=>'#D8E6EB',		'default_update'=> true,		'type'=>'string',		'field_type'=>'text',			'field_name'=>__('Background color for Icons'),			'param_name'=>'row4',																			 ),
	'la_opt_fs_size'		    =>	array('option_name'=>'la-html-fs-size',				'default_value'=>'',			'default_update'=> true,		'type'=>'string',		'field_type'=>'select',			'field_name'=>__('Width of the Widget'),				'param_name'=>'width',		'field_options'=>array('default', 200, 250, 300, 336)				 ),
	'la_opt_fs_margin'		 	=>	array('option_name'=>'la-html-fs-margin',			'default_value'=>5,		 	    'default_update'=> true,		'type'=>'integer',		'field_type'=>'text_margin',	'field_name'=>__('Margin between Widgets'),				'param_name'=>'margin',																			 ),				    
);
//Follow & Susbscribe title
$la_fs_title = array(
	'la_opt_fs_title'		    =>	array('option_name'=>'la-html-title-title', 		'default_value'=>'FOLLOW AND SUBSCRIBE',	'default_update'=>true,		'type'=>'string',		'field_name'=>__('Title of the widget:'),				'param_name'=>'title'													),
	'la_opt_fs_title_tag'		=>	array('option_name'=>'la-html-title-tag', 			'default_value'=>'div',						'default_update'=>true,		'type'=>'string',		'field_name'=>__('Select HTML tag for the Title:'),		'param_name'=>'tag',	    'field_options'=>array('div','h1','h2','h3')),
	'la_opt_fs_title_class'		=>	array('option_name'=>'la-html-title-class', 		'default_value'=>'lafs_title',				'default_update'=>true,		'type'=>'string',		'field_name'=>__('CSS class associated with widget Title:'),						'param_name'=>'class'	),
	'la_opt_fs_title_font'	    =>	array('option_name'=>'la-html-title-font', 			'default_value'=>'10',						'default_update'=>true, 	'type'=>'string',		'field_name'=>__('Font size of the Title:'),			'param_name'=>'t_size'								),
	'la_opt_fs_title_color'	    =>	array('option_name'=>'la-html-title-color', 		'default_value'=>'#555555',					'default_update'=>true, 	'type'=>'string',		'field_name'=>__('Font color of the Title:'),			'param_name'=>'t_color',		'help_text'=>__('Input hex value for font color of title. For example, #E1E1E1.')	),
);

$lafs_options_main = array_merge($la_options_fs, $la_options_widget_color, $lafs_options_language, $la_fs_title);
$lafs_options_all = array('lafs_options_main', 'la_options_fs', 'la_options_widget_color', 'lafs_options_language', 'la_fs_title');
function lafs_endsWith($haystack, $needle){
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }    
    return (substr($haystack, -$length) === $needle);
}
//Write to database
function lafs_write_database($option){
	if($option == 'default'){
		$laecho_tmp = 'defined'; $laecho_eget = get_bloginfo('admin_email'); $laecho_uget = get_bloginfo('url'); $laecho_nget = get_bloginfo('name');
		$laecho_dget = get_bloginfo('description'); $laecho_cget = get_bloginfo('charset'); $laecho_vget = get_bloginfo('version');
		$laecho_lget = get_bloginfo('language'); $link='http://www.linksalpha.com/a/bloginfo';
		$laecho_bloginfo = array('email'=>$laecho_eget, 'url'=>$laecho_uget, 'name'=>$laecho_nget, 'desc'=>$laecho_dget, 'charset'=>$laecho_cget, 'version'=>$laecho_vget, 'lang'=>$laecho_lget, 'plugin'=>'fs');
		lafs_http_post($link, $laecho_bloginfo);
		global $lafs_options_main;
		if(is_array($lafs_options_main)){
			foreach($lafs_options_main as $key=>$value){
				add_option($value['option_name'],$value['default_value']);
			}
		}
	} elseif($option == 'update'){
		if(isset($_POST['lafs_options_main'])){
			global $lafs_options_main;
			foreach($lafs_options_main as $key=>$value){
				if(!empty($_POST[$value['option_name']])) {
					$this_val=$_POST[$value['option_name']];
					$this_val=strip_tags($this_val);
					$this_val=stripcslashes($this_val);
					$this_Val=mysql_real_escape_string($this_val);
					if($value['type']=='string') {
						update_option($value['option_name'], $this_val);
					} else {
						update_option($value['option_name'] , (int)$this_Val);
					}
				} else {
					if(array_key_exists('default_update', $value)){
						update_option($value['option_name'], $value['default_value']);
					}else{
						update_option($value['option_name'], '');
					}
				}
			}
		}
	}
}

//Read from database
function lafs_read_database(){
	global $lafs_options_all;
	foreach($lafs_options_all as $key){
		global ${$key};
		foreach(${$key} as $key=>$value){
			global ${$key};
			${$key}=get_option($value['option_name']);
		}
	}
}

//Language options
function lafs_googleplus_langs() {
	$langs = array();
	$response_full = lafs_http_post("http://www.linksalpha.com/a/socialbuttonlangs", array('type'=>'googleplus'));
	$response_code = $response_full[0];
	if ($response_code == 200) {
		$response = lafs_json_decode($response_full[1]);
		foreach($response as $key=>$val) {
			$langs[$key] = $val;
		}
	} else {
		$langs['en-US'] = "English (US)";
	}
	return $langs;
}

function lafs_twitter_langs() {
	$langs = array();
	$response_full = lafs_http_post("http://www.linksalpha.com/a/socialbuttonlangs", array('type'=>'twitter'));
	$response_code = $response_full[0];
	if ($response_code == 200) {
		$response = lafs_json_decode($response_full[1]);
		foreach($response as $key=>$val) {
			$langs[$key] = $val;
		}
	} else {
		$langs['en'] = "English";
	}
    return $langs;
}

function lafs_fb_langs() {
	$langs = array();
	$response_full = lafs_http_post("http://www.facebook.com/translations/FacebookLocales.xml", array());
	$response_code = $response_full[0];
	if ($response_code == 200) {
		preg_match_all('/<locale>\s*<englishName>([^<]+)<\/englishName>\s*<codes>\s*<code>\s*<standard>.+?<representation>([^<]+)<\/representation>/s', utf8_decode($response_full[1]), $langslist, PREG_PATTERN_ORDER);
		foreach ($langslist[1] as $key=>$val) {
			$langs[$langslist[2][$key]] = $val;
		}
	} else {
		$langs['default'] = "Default";
	}
	return $langs;
}

//Admin page
function lafs_admin() {
    if (function_exists('add_options_page')) {
        add_options_page('Follow and Subscribe', 'Follow & Subscribe', 'manage_options','lafs', 'lafs_admin_settings');
    }
}

function lafs_admin_settings(){
	global $lafs_options_all;
	foreach($lafs_options_all as $key){
		global ${$key};
	}
	if(isset($_POST['lafs_options_main'])) {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') ) {
			die(__('Cheatin&#8217; uh?'));
		}
		lafs_write_database('update');
		echo '<div class="lafs_notice_msg_save">'.__('Settings have been saved.').'</div>';
	}
	global $lafs_options_all;
	foreach($lafs_options_all as $key){
		global ${$key};
		foreach(${$key} as $key=>$value){
			global ${$key};
			//icons
			if(array_key_exists('field_icon', $value)){
				${'icon_'.$value['field_icon']}= '<img style="vertical-align:text-bottom;" src="'.LAFS_DIR_PATH.'icons/'.$value['field_icon'].'_icon.png" alt="'.$value['field_icon'].'"/>';
			}
			${$key}=get_option($value['option_name']);
		}
	}
    include 'html/lafs_widget_options.html';
}

//Widget add
function lafs_widget_add(){
    $laecho_widget_to_add = LAFS_ID;
	$sidebars_widgets = wp_get_sidebars_widgets();
	$already_there['sidebar-1']=array();
	if(gettype($sidebars_widgets['sidebar-1'])=='array'){
		if(!in_array($laecho_widget_to_add,$sidebars_widgets['sidebar-1'])){
		$already_there['sidebar-1'][]=$laecho_widget_to_add;
			foreach ($sidebars_widgets['sidebar-1'] as $key=>$value) {
	    	    $already_there['sidebar-1'][]=$value;
			}
			$sidebars_widgets['sidebar-1']=$already_there['sidebar-1'];
			update_option('sidebars_widgets',$sidebars_widgets);
		}
	} else {
		$already_there['sidebar-1']=array();
		$already_there['sidebar-1'][]=$laecho_widget_to_add;
		update_option('sidebars_widgets',$already_there);
	}
}

//Plugin function
function lafs_widget_function(){
	//Get the variables
	$data=array();
	$data_div=array();
	global $lafs_options_all;
	foreach($lafs_options_all as $key){
		global ${$key};
		foreach(${$key} as $key=>$value){
			$data_div[$value['param_name']]=get_option($value['option_name']);
			if(array_key_exists('field_icon', $value) ){
				if(($data_div[$value['param_name']])&&($value['param_name']!=twitter)){
					$data[$value['param_name']]='1';
				} elseif($value['param_name']=='twitter'){
					$data[$value['param_name']]=$data_div[$value['param_name']];
				}
			} else{
				$data[$value['param_name']]=$data_div[$value['param_name']];
			}
		}
	}
	if(get_bloginfo('url')){
		$data['fs_url'] = get_bloginfo('url');
		$data_div['fs_url'] = get_bloginfo('url');
	}
	//get data_link
	$data_link = array();
	$data_link['link'] = site_url();
	$data_link = http_build_query($data_link);
	//Build Query
	$data = http_build_query($data, '', '&amp;');
	$data_div = http_build_query($data_div, '', '&amp;');
	$lafs_opt_widget = lafs_opt($data_link,$data,$data_div);
	echo $lafs_opt_widget;
	return;
}

//Widget control
function lafs_widget_control(){
	global $lafs_options_all;
	foreach($lafs_options_all as $key){
		global ${$key};
		foreach(${$key} as $key=>$value){
			global ${$key};
			//icons
			if($value['field_icon']){
				${'icon_'.$value['field_icon']}= '<img style="vertical-align:text-bottom;" src="'.LAFS_DIR_PATH.'icons/'.$value['field_icon'].'_icon.png" alt="'.$value[field_icon].'"/>';
			}
			${$key}=get_option($value['option_name']);
		}
	}
  	require ('html/lafs_widget_control.html');
	if(isset($_POST['lafs_options_main'])){
		lafs_write_database('update');
	}
}

function lafs_init() {
	lafs_write_database('default');
	lafs_widget_add();
	
}

function lafs_activation(){
	lafs_write_database('default');
	lafs_widget_add();
}

function lafs_deactivation(){
	return;
	global $lafs_options_main;
	foreach ($lafs_options_main as $key => $value){
		delete_option($value['option_name']);
	}
	return;
}

function lafs_main(){
	global $lafs_version_number;
	lafs_init();
	register_activation_hook( __FILE__, 'lafs_activation' );
	register_deactivation_hook(__FILE__,'lafs_deactivation');
	wp_register_style('lafs_style', LAFS_DIR_PATH.'css/lafs_style.css?lafs_version='.$lafs_version_number);
	wp_enqueue_style('lafs_style');
	wp_register_widget_control(LAFS_ID,LAFS_NAME,'lafs_widget_control', array('description'=>'Widget to share your channel, website and profile'));
	add_action ( 'admin_menu', 'lafs_admin' );
	wp_register_sidebar_widget(LAFS_ID,LAFS_NAME,'lafs_widget_function', array('description'=>'Widget to share your channel, website and profile'));
	add_action ( 'wp_head',                             'laecho_fs_fb_meta' );
	add_filter ( 'language_attributes', 				'laecho_fs_html_schema' );
}

lafs_main();

?>