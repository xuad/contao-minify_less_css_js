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
 * Class ModuleNewsArchiveList 
 *
 * @copyright  Patrick Mosch 
 * @author     Patrick Mosch 
 * @package    Devtools
 */
class ModuleMinifyCssJs extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_minifycssjs';

	/**
	 * Generate module
	 */
	protected function compile()
	{
		$css = "";
		$js = "";
		$minifiedCss = "";

		// Templatevars
		$inputFilesCss = array();
		$inputFilesJs = array();

		// Foreignkeys from DB
		$this->inputMinifyFiles = deserialize($this->inputMinifyFiles);
		$this->inputFiles = deserialize($this->inputFiles);

		// Sort files		
		$sortedMinifyFiles = $this->sortFiles($this->inputMinifyFiles, $this->orderInputMinifyFiles);
		$sortedDefaultFiles = $this->sortFiles($this->inputFiles, $this->orderInputFiles);

		$toMinifyJsContent = "";

		// Get minify files
		foreach ($sortedMinifyFiles as $file)
		{
			// Get the file reference
			$objFile = \FilesModel::findByPk($file);

			if ($objFile !== null)
			{
				// Convert less-files or get content from css-files
				if ($objFile->extension === "less")
				{
					$generatedCss = $this->generateLessToCss(TL_ROOT . "/" . $objFile->path);

					// Rewrite urls e.g. imagepaths
					$generatedCss = $this->rewriteCssUrl($generatedCss, $objFile->path);

					// Compress css
					$minifiedCss .= $this->minifyCssFiles($generatedCss);

					$inputFilesCss[] = $objFile->name;
				}
				else if ($objFile->extension === "css")
				{
					$cssContent = $this->readFile($objFile->path);

					// Rewrite urls e.g. imagepaths
					$cssContent = $this->rewriteCssUrl($cssContent, $objFile->path);

					// Compress css
					$minifiedCss .= $this->minifyCssFiles($cssContent);

					$inputFilesCss[] = $objFile->name;
				}
				else if ($objFile->extension === "js")
				{
					// Combine js-file and compress after that
					$toMinifyJsContent .= $this->readFile($objFile->path);
					//$minifiedJs .= $this->minifyJsFiles($jsContent);

					$inputFilesJs[] = $objFile->name;
				}
			}
		}

		$minifiedJs = \JSMin::minify($toMinifyJsContent);

		// Default files
		foreach ($sortedDefaultFiles as $file)
		{
			// Get the file reference
			$objFile = \FilesModel::findByPk($file);

			if ($objFile !== null)
			{
				// Convert less-files or get content from css-files
				if ($objFile->extension === "less")
				{
					$cssContent = $this->generateLessToCss(TL_ROOT . "/" . $objFile->path);

					// Rewrite urls e.g. imagepaths and save css
					$css .= $this->rewriteCssUrl($cssContent, $objFile->path);

					$inputFilesCss[] = $objFile->name;
				}
				else if ($objFile->extension === "css")
				{
					$cssContent .= $this->readFile($objFile->path);

					// Rewrite urls e.g. imagepaths and save css
					$css .= $this->rewriteCssUrl($cssContent, $objFile->path);
					$inputFilesCss[] = $objFile->name;
				}
				else if ($objFile->extension === "js")
				{
					$js .= $this->readFile($objFile->path);
					$inputFilesJs[] = $objFile->name;
				}
			}
		}

		// Get the file reference for destination folder
		$objFolder = \FilesModel::findByPk($this->outputFolder);

		if ($objFolder !== null)
		{
			// Combine files				
			$combinedCss = $minifiedCss . $css;
			$combinedJs = $minifiedJs . $js;

			// Write css-file
			if (!empty($combinedCss))
			{
				$cssFilename = sprintf("%s/%s.%s", $objFolder->path, $this->outputFilename, "css");
				$result = $this->writeFile($combinedCss, $cssFilename);

				// Load writed file
				$file = new \File($cssFilename);

				// Templateinfo
				$fileInfo = new FileInformation();
				$fileInfo->filename = sprintf("%s.%s", $this->outputFilename, "css");
				$fileInfo->file = $cssFilename;
				$fileInfo->cssClass = $result === true ? "alert-success" : "alert-error";
				$fileInfo->fileSize = round($file->filesize / 1024, 2) . " KiB";

				$this->Template->outputFileCss = $fileInfo;
			}

			// write js-file
			if (!empty($combinedJs))
			{
				$jsFilename = sprintf("%s/%s.%s", $objFolder->path, $this->outputFilename, "js");
				$result = $this->writeFile($combinedJs, $jsFilename);

				// Load writed file
				$file = new \File($jsFilename);

				// Templateinfo
				$fileInfo = new FileInformation();
				$fileInfo->filename = sprintf("%s.%s", $this->outputFilename, "js");
				$fileInfo->file = $jsFilename;
				$fileInfo->cssClass = $result === true ? "alert-success" : "alert-error";
				$fileInfo->fileSize = round($file->filesize / 1024, 2) . " KiB";

				$this->Template->outputFileJs = $fileInfo;
			}
		}

		// Activate template-output
		if ($this->writeTemplate)
		{
			$this->Template->writeTemplate = true;

			if (count($inputFilesCss) > 0)
			{

				$this->Template->inputFilesCss = implode(", ", $inputFilesCss);
			}

			if (count($inputFilesJs) > 0)
			{

				$this->Template->inputFilesJs = implode(", ", $inputFilesJs);
			}

			// Template labels
			$this->Template->inputFilesCssLabel = $GLOBALS['TL_LANG']['MSC']['minify_less_css_js_label_input_files_css'];
			$this->Template->inputFilesJsLabel = $GLOBALS['TL_LANG']['MSC']['minify_less_css_js_label_input_files_js'];
			$this->Template->outputFileCssLabel = $GLOBALS['TL_LANG']['MSC']['minify_less_css_js_label_combined_file_css_label'];
			$this->Template->outputFileJsLabel = $GLOBALS['TL_LANG']['MSC']['minify_less_css_js_label_combined_file_js_label'];
			$this->Template->filesizeLabel = $GLOBALS['TL_LANG']['MSC']['minify_less_css_js_filesize_label'];
		}
	}

	/**
	 * Generate less-file to css string
	 * 
	 * @param type $lessFiles
	 */
	protected function generateLessToCss($file)
	{
		$less = new \lessc;
		return $less->compileFile($file);
	}

	/**
	 * Read a file and return as string
	 * 
	 * @param type $lessFiles
	 */
	protected function readFile($file)
	{
		$file = new \File($file);
		$content = $file->getContent();
		$file->close();

		return $content;
	}

	/**
	 * Minify css-files
	 * 
	 * @param type $files
	 */
	protected function minifyCssFiles($css)
	{
		// Compressfilter
		// For more informations see http://code.google.com/p/cssmin/wiki/Configuration
		$filters = array
			(
			"ImportImports" => false,
			"RemoveComments" => true,
			"RemoveEmptyRulesets" => true,
			"RemoveEmptyAtBlocks" => true,
			"ConvertLevel3AtKeyframes" => true,
			"ConvertLevel3Properties" => true,
			"Variables" => false,
			"RemoveLastDelarationSemiColon" => true
		);

		$commpressedCss = \CSSmin::minify($css, $filters);

		return $commpressedCss;
	}

	/**
	 * Minify js-files
	 * 
	 * @param type $files
	 */
	protected function minifyJsFiles($jsUncompressed)
	{
		return \JSMin::minify($jsUncompressed);
	}

	/**
	 * Write the file
	 * 
	 * @param type $content
	 * @param \File $file
	 * @return type
	 */
	protected function writeFile($content, $file)
	{
		$result = false;

		$file = new \File($file);
		$result = $file->write($content);
		$file->close();

		return $result;
	}

	/**
	 * Rewrite paths in css-file
	 * 
	 * @param type $css
	 * @param type $filePath
	 * @return type
	 */
	protected function rewriteCssUrl($css, $filePath)
	{
		$basePath = TL_ROOT;
		$pathParts = pathinfo($filePath);

		// without websitePath rewriteurls are incorrect
		$curPath = sprintf("%s%s/%s", $GLOBALS['TL_CONFIG']['websitePath'], TL_ROOT, $pathParts['dirname']);

		return \Minify_CSS_UriRewriter::rewrite($css, $curPath, $basePath, array());
	}

	/**
	 * Sort files based on sortorder
	 * For more information see ContentDownloads.php
	 * 
	 * @param type $files
	 * @param type $sortOrder
	 * @return type
	 */
	protected function sortFiles($unsortedFiles, $sortOrder)
	{
		$files = array();

		if (is_array($unsortedFiles))
		{

			foreach ($unsortedFiles as $file)
			{

				$files[] = array(
					'id' => $file
				);
			}

			if ($sortOrder != '')
			{
				// Turn the order string into an array and remove all values
				$arrOrder = explode(',', $sortOrder);
				$arrOrder = array_flip(array_map('intval', $arrOrder));
				$arrOrder = array_map(function() {
							
						}, $arrOrder);

				// Move the matching elements to their position in $arrOrder
				foreach ($files as $k => $v)
				{
					if (array_key_exists($v['id'], $arrOrder))
					{
						$arrOrder[$v['id']] = $v;
						unset($files[$k]);
					}
				}

				// Append the left-over files at the end
				if (!empty($files))
				{
					$arrOrder = array_merge($arrOrder, array_values($files));
				}

				// Remove empty (unreplaced) entries
				$files = array_filter($arrOrder);

				unset($arrOrder);
			}
		}

		return $files;
	}

}

?>