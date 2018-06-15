<?php
/********************************************************************************************************************************/
// Nutshell - administration core class
/********************************************************************************************************************************/
class response
{
/********************************************************************************************************************************/
// Class variables
	//$this->target_data = array();
	// Response / JSON id of data for client side JS to look for.
	//$this->target_id = 'target_data';


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
		//$accept = $_SERVER['HTTP_ACCEPT'];
		//$accept = explode(',',$accept);
		//print_r($accept);
				$data = array('content_two' => 'TEST');
				$response = array('target_data' => $data);
				$this->return_json($response);
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