<?php
// PHP Functions //

//php function to check whether the url exists or not and validate it  
function check_url($url)  
{  
$check = @fopen($url,"r"); // we are opening url with fopen  
if($check)  
 $status = true;  
else  
 $status = false;  
   
return $status;  
}  
 

function getDisclaimer() {
	$view = new TemplateView();
	return $view->render('disclaimer');
}

/**
 * Will check for wrong directory separators and inject the ones returned by the DIRECTORY_SEPARATOR constant
 * 
 * @param string $pathName The path to sanitize
 * @return string The sanitized path
 */
function sanitizeFilePath($pathName) {
	if (!is_string($pathName)) throw new Exception('Can\'t sanitize value of type ' . gettype($pathName) . ' as pathName. String expected.');
	if (!strlen($pathName)) return '';
	return preg_replace('!(\\\\|/)+!is', DIRECTORY_SEPARATOR, $pathName);
}
?>