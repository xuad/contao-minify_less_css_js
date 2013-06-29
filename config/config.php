<?php 

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package   minify_less_css_js
 * @author    Patrick Mosch 
 * @license   LGPL 
 * @copyright Patrick Mosch 
 */

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD'], 2, array
(
	'application' => array
	(
		'minify_less_css_js'	=> 'ModuleMinifyCssJs'
	)
));