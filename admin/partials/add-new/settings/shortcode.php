<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Content settings
*
* @package     
* @subpackage  Add New
* @copyright   Copyright (c) 2017, Dmytro Lobov
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       1.0
*/

$quntity = array(
	'id'   => 'quntity',
	'name' => 'quntity',	
	'type' => 'text',
	'val' => isset($param['quntity']) ? $param['quntity'] : 5,	
);

$content = array(
	'id'   => 'content',
	'name' => 'content',	
	'type' => 'editor',
	'val' => isset($param['content']) ? $param['content'] : '{fname} {lname} purchased {download} for {price} {time} ago',	
);

$shortcode_img = array(
	'id'          => 'shortcode_img',
	'name'        => 'shortcode_img',	
	'type'        => 'select',
	'val'         => isset($param['shortcode_img']) ? $param['shortcode_img'] : 'none',
	'option'     => array(
		'none'     => 'None', 
		'download' => 'Download Image', 
		'avatar'   => 'User Avatar'
	),
);

$shortcode_img_size= array(
	'id'   => 'shortcode_img_size',
	'name' => 'shortcode_img_size',	
	'type' => 'text',
	'val' => isset($param['shortcode_img_size']) ? $param['shortcode_img_size'] : 32,	
);