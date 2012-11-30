<?php

class TemplateView {

	/**
	 * The page template to use
	 *
	 * @var string
	 */
	protected $template;

	/**
	 * The markers to replace in the template
	 * 
	 * @var array
	 */
	protected $marker = array();

	/**
	 * Setter for the template to use
	 *
	 * @param string $template
	 * @return void
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}

	/**
	 * Setter for the markers to replace in the template
	 *
	 * @param array $marker
	 * @return void
	 */
	public function setMarker(array $marker) {
		$this->marker = $marker;
	}

	/**
	 * Renders the final page output
	 *
	 * @param string $templateName The template to render
	 * @return string The rendered page;
	 */
	public function render($templateName = NULL) {
		if ($templateName === NULL) $templateName = $this->template;
		$template = $this->getTemplate($templateName);
		return $this->replaceMarker($this->marker, $template);
	}

	/**
	 * Replaces a collection of markers in a template
	 *
	 * @param array $marker
	 * @param string $template
	 * @return string
	 */
	protected function replaceMarker(array $marker, $template) {
		return strtr($template, $marker);
	}

	/**
	 * Reads the content of a template and returns it
	 *
	 * @param string $templateName
	 */
	protected function getTemplate($templateName) {
		global $configuration;

		$root = getcwd();
		$templatePath = $root . DIRECTORY_SEPARATOR . $configuration['templatePath'] . DIRECTORY_SEPARATOR;
		$templateFile = $templateName . '.html';

		if (!is_file($templatePath . $templateFile)) throw new Exception(sprintf('Template file &quot;%s&quot; not found in the template path', $templateFile) );

		return file_get_contents($templatePath . $templateFile);
	}
}
?>