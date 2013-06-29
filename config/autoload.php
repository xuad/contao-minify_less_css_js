<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package minify_less_css_js
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
	(
	'minify_less_css_js',
));

/**
 * Register the classes
 */
ClassLoader::addClasses(array
	(
	// Modules
	'minify_less_css_js\ModuleMinifyCssJs' => 'system/modules/minify_less_css_js/modules/ModuleMinifyCssJs.php',
	// Models
	'minify_less_css_js\FileInformation' => 'system/modules/minify_less_css_js/models/FileInformation.php',
	// Classes
	'lessc' => 'system/modules/minify_less_css_js/classes/lessphp/lessc.inc.php',
	'CSSmin' => 'system/modules/minify_less_css_js/classes/cssmin-v3.0.1.php',
	'JSMin' => 'system/modules/minify_less_css_js/classes/jsmin.php',
	'Minify_CSS_UriRewriter' => 'system/modules/minify_less_css_js/classes/minify-master/min/lib/Minify/CSS/UriRewriter.php'
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
	(
	'mod_minifycssjs' => 'system/modules/minify_less_css_js/templates'
));
