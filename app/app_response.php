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
		// Flag
		$this->response_returned = false;
		
		// Target data 
		$this->target_data = array();
		$this->target_data['response_info'] = 'App: V0.1 delta';
		$this->target_data['target_data'] = false;
		$this->target_data['target_data']['debug'] = 'O.K.';
	}
/********************************************************************************************************************************/
// Response data getter and setter
	function set_response($target_id,$data)
	{
		$this->target_data['target_data'][$target_id] = $data;
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
	function return_response($response_body = false,$force_json = false)
	{
		// Use built in response data
		if($response_body === false)
		{
			$response_body = $this->target_data;
		}
		
		// Get Accept from request header
		$accept = $_SERVER['HTTP_ACCEPT'];
		// Explode it into an array
		$accept = explode(',',$accept);
		
		// Check the first element for json
		if($accept[0] == 'application/json' || $force_json)
		{
			$this->return_json($response_body);	
		}
		else
		{
			$this->return_html($response_body);	
		}
	}
/********************************************************************************************************************************/
// Generate JSON from PHP array	
	function return_json($response_body)
	{
		header('Content-Type: application/json');
		echo json_encode($response_body);
		$this->response_returned = true;
	}
/********************************************************************************************************************************/
// Generate HTML

	function return_html($response_body)
	{
		header('Content-Type: text/html');
		echo $response_body;
		$this->response_returned = true;
	}
/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>