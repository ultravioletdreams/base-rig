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

		// Application title
		$this->app_title = 'DEFAULT: Application Title';

		// Target data 
		$this->target_data = array();
		//$this->target_data['response_info'] = 'App: V0.1 delta';
		//$this->target_data['target_data'] = false;
		//$this->target_data['call_local_js'] = false;
		//$this->target_data['callback_requests'] = false;
		//$this->target_data['target_data']['debug'] = 'O.K.';
	}
/********************************************************************************************************************************/
// Response data setter
	function set_response($target_id,$data)
	{
		$this->target_data['target_data'][$target_id] = $data;
	}
/********************************************************************************************************************************/
// Response call local JS setter	
	function set_local_js($target_id,$data)
	{
		$this->target_data['call_local_js'][$target_id] = $data;
	}
/********************************************************************************************************************************/
// Send callback request in response
	function set_callback($target_id,$data)
	{
		$this->target_data['callback_requests'][$target_id]= $data;
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
		$action_data['app_title'] = $this->app_title;
		require($template_path);
		$html = ob_get_clean();
	
		// Return generated (buffered) HTML
		return $html;		
	}

/********************************************************************************************************************************/
// Get POST request body and treat it as JSON
	function get_post_body()
	{
		$entityBody = file_get_contents('php://input');
		$post_json = json_decode($entityBody,true);
		return $post_json; 
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
		echo is_array($response_body) ? print_r($response_body,true) : $response_body;
		$this->response_returned = true;
	}
/********************************************************************************************************************************/
// DEFAULT: Default - Unknown action requested.
	function default_action($action_name)
	{
		$this->set_response('debug','Unknown action: ' . $action_name);
	}
/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>