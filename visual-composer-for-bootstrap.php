<?php
	/**
	Plugin Name: WPBakery Page Builder for Twitter Bootstrap Themes
	Version: 2.0
	Author: CyberXoft
	Plugin URI: http://www.cyberxoft.com/product/using-visual-composer-for-twitter-bootstrap-themes/
	Author URI: http://www.cyberxoft.com/
	Text Domain: cx-wc-uvcftbt-page
	Description: With this plugin the <a href="http://www.cyberxoft.com/product/visual-composer-page-builder-for-wordpress/"><strong>Visual Composer</strong></a> will add Twitter Bootstrap CSS classes for adding rows and columns instead of its defined CSS classes. All wordpress themes built on Twitter Bootstrap framework can now install <a href="http://www.cyberxoft.com/product/visual-composer-page-builder-for-wordpress/"><strong>Visual Composer</strong></a> and easily manage the content for their website. So now its going to be very easy for those not familiar enough with HTML or CSS that they could make required content changes very easily. It works out of the box, and there are no settings for you to configure. <strong>I also offer Upto 66% Discount on all WordPress Themes and Plugins sold on my website, <a href="http://www.cyberxoft.com/product-category/wp-themes/">check that...</a></strong>
	*/
	
if($isReset = array_key_exists('rst', $_POST)){
	delete_option('vc-bootstrap');
}

$json = get_option('vc-bootstrap');
$plugin_file = plugin_basename(__FILE__);
$dir = plugin_dir_path( __DIR__ );
$isWPBPBActive = in_array('js_composer/js_composer.php', apply_filters('active_plugins', get_option('active_plugins')));
$v = ($isWPBPBActive?WPB_VC_VERSION :$wp_version);

$options = json_decode(json_encode(array_merge([
	'css'=>'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css',
	'js'=>'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js',
	'rmv'=>false, //remove WPBakery Page Builder Frontend CSS File
	'bs'=>false, //add provided Bootstrap CSS and Javascript Files
	'bsv'=>4, //probable bootstrap version
	'en'=>true, //Enable/Disable replacing css classes
	'rmc'=>'wpb_row,vc_row-fluid,wpb_column,vc_column_container'
], ($json?json_decode($json, true) :[]))), false);

include(is_admin()?'backend.php' :'frontend.php');

if($isWPBPBActive){
	include('bootstrap'.$options->bsv.'/inc.php');
}