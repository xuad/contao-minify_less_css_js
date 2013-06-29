<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package   minify_less_css_js 
 * @author    Patrick Mosch 
 * @license   LGPL 
 * @copyright Patrick Mosch 
 * 
 */
/**
 * Namespace
 */

namespace minify_less_css_js;

/**
 * Read and write file informations for the template
 *
 * @copyright  Patrick Mosch 
 * @author     Patrick Mosch 
 * @package    minify_less_css_js
 */
class FileInformation
{

	/**
	 * Output filename
	 * @var int
	 */
	protected $filename;

	/**
	 * Output file
	 * @var int
	 */
	protected $file;

	/**
	 * information about write-status
	 * @var int
	 */
	protected $information;

	/**
	 * CSS class with status
	 * @var int
	 */
	protected $cssClass;

	/**
	 * Filesize
	 * @var int
	 */
	protected $fileSize;

	/**
	 * Getter methods
	 * 
	 * @param type $strKey
	 * @return type
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case "filename":
				return $this->filename;
				break;
			case "file":
				return $this->file;
				break;
			case "information":
				return $this->information;
				break;
			case "cssClass":
				return $this->cssClass;
				break;
			case "fileSize":
				return $this->fileSize;
				break;
		}

		return parent::__get($strKey);
	}

	/**
	 * Setter methods
	 * 
	 * @param type $strKey
	 * @param type $varValue
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case "filename":
				$this->filename = $varValue;
				break;
			case "file":
				$this->file = $varValue;
				break;
			case "information":
				$this->information = $varValue;
				break;
			case "cssClass":
				$this->cssClass = $varValue;
				break;
			case "fileSize":
				$this->fileSize = $varValue;
				break;
			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}

}

?>
