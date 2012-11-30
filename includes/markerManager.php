<?php
class MarkerManager {

	/**
	 * @var array
	 */
	protected $cache = array();

	/**
	 * @var string
	 */
	protected $emptyValue = '';

	/**
	 * Check is a given marker can be resolved
	 * 
	 * @param string $markerName
	 * @param string $wrapper
	 * @return boolean
	 */
	public function hasMarker($markerName, $wrapper = '') {
		$marker = $wrapper . $markerName . $wrapper;
		if (isset($this->cache[$marker])) return TRUE;
		if (method_exists($this, $this->buildMarkerFunction($markerName))) return TRUE;
		return FALSE;
	}

	/**
	 * Returns the replacement value for a marker
	 * 
	 * @param string $markerName
	 * @param string $wrapper 
	 * @return string
	 */
	public function getMarker($markerName, $wrapper = '') {
		if (!$this->hasMarker($markerName, $wrapper)) return $this->emptyValue;

		$marker = $wrapper . $markerName . $wrapper;
		if (isset($this->cache[$marker])) return $this->cache[$marker];
		
		$methodName = $this->buildMarkerFunction($markerName);
		if (method_exists($this, $methodName)) {
			$value = $this->$methodName();
			$this->setMarker($marker, $value);
			return $value;
		}
		return $this->emptyValue;
	}

	/**
	 * Sets the value for a marker
	 * 
	 * @param string $markerName
	 * @param string $content
	 * @return void
	 */
	public function setMarker($markerName, $content) {
		$this->cache[$markerName] = $content;
	}

	/**
	 * Builds the correct getter method name for the given markerName
	 * 
	 * @param string $markerName
	 * @return string The method name
	 */
	protected function buildMarkerFunction($markerName) {
		return $funcFromMarker = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($markerName)))) . 'Marker';
	}
}
?>