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
$GLOBALS['TL_DCA']['tl_module']['palettes']['minify_less_css_js'] = '{title_legend},name,type;{input_files_legend},inputMinifyFiles,inputFiles;{output_files_legend},outputFolder,outputFilename,writeTemplate';

$GLOBALS['TL_DCA']['tl_module']['fields']['inputMinifyFiles'] = array
	(
	'label' => &$GLOBALS['TL_LANG']['tl_module']['minify_less_css_js_input_minify_files'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array('multiple' => true, 'fieldType' => 'checkbox', 'filesOnly' => true, 'orderField' => 'orderInputMinifyFiles',),
	'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['inputFiles'] = array
	(
	'label' => &$GLOBALS['TL_LANG']['tl_module']['minify_less_css_js_inputFiles'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array('multiple' => true, 'fieldType' => 'checkbox', 'filesOnly' => true, 'orderField' => 'orderInputFiles',),
	'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['orderInputFiles'] = array
	(
	'sql' => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['orderInputMinifyFiles'] = array
	(
	'sql' => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['outputFolder'] = array
	(
	'label' => &$GLOBALS['TL_LANG']['tl_module']['minify_less_css_js_outputFolder'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array('fieldType' => 'radio', 'mandatory' => true),
	'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['outputFilename'] = array
	(
	'label' => &$GLOBALS['TL_LANG']['tl_module']['minify_less_css_js_outputName'],
	'exclude' => true,
	'inputType' => 'text',
	'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
	'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['writeTemplate'] = array
	(
	'label' => &$GLOBALS['TL_LANG']['tl_module']['minify_less_css_js_write_template'],
	'default' => 1,
	'inputType' => 'checkbox',
	'sql' => "char(1) NOT NULL default '1'"
);