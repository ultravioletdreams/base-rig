<?php
/********************************************************************************************************************************/
// Nutshell - administration core class
/********************************************************************************************************************************/
class app_response
{
/********************************************************************************************************************************/
// Class constructor
	function __construct()
	{
		// Call parent class constructor
		//parent::__construct();
	}
/********************************************************************************************************************************/
// Generate HTML from template
	function template_html($template_path,$template_data)
	{
		// Stores output buffer
		$html = '';
		// Start output buffering
		ob_start();
		// Make action data available to the template as $action_data;
		$action_data = $template_data;
		require($template_path);
		$html = ob_get_clean();
	
		// Return generated (buffered) HTML
		return $html;		
	}
/********************************************************************************************************************************/
// Detect "accept" types and return appropriate response type
	function return_response()
	{
		// Get Accept from request header
		$accept = $_SERVER['HTTP_ACCEPT'];
		$accept = explode(',',$accept);
	}
/********************************************************************************************************************************/
// Generate JSON from PHP array	
	function return_json($response_body)
	{
		header('Content-Type: application/json');
		echo json_encode($response_body);
	}
/********************************************************************************************************************************/
// Generate HTML

	function return_html($response_body)
	{
		header('Content-Type: text/html');
		echo $response_body;
	}
/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>