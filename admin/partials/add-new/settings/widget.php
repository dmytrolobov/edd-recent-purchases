<?php
/**
* Widget Settings
*
* @package     
* @subpackage  Settings
* @copyright   Copyright (c) 2017, Dmytro Lobov
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       1.0
*/

$widget_quntity = array(
	'id'   => 'widget_quntity',
	'name' => 'widget_quntity',	
	'type' => 'text',
	'val' => isset($param['widget_quntity']) ? $param['widget_quntity'] : 5,	
);

$widget = array(
	'id'   => 'widget',
	'name' => 'widget',	
	'type' => 'editor',
	'val' => isset($param['widget']) ? $param['widget'] : '{fname} {lname} purchased {download} for {price} {time} ago',	
);

$widget_img = array(
	'id'          => 'widget_img',
	'name'        => 'widget_img',	
	'type'        => 'select',
	'val'         => isset($param['widget_img']) ? $param['widget_img'] : 'none',
	'option'     => array(
		'none'     => 'None', 
		'download' => 'Download Image', 
		'avatar'   => 'User Avatar'
	),
);

$widget_img_size = array(
	'id'   => 'widget_img_size',
	'name' => 'widget_img_size',	
	'type' => 'text',
	'val' => isset($param['widget_img_size']) ? $param['widget_img_size'] : 32,	
);

?>