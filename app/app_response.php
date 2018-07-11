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
		
		// Flag response returned
		$this->response_returned = false;
		
		// Session status
		$this->session_started = false;
		
		// Return JSON response only
		$this->force_json = false;

		// Application title
		$this->app_title = 'DEFAULT: Application Title';

		// Response data 
		$this->response_data = array();
		
		// Initialise session status
		if(session_id() == '')
		{
			//$this->response_data['app_status'][] = 'Session not started.';
			$this->session_started = false;
		}
		else
		{
			//$this->response_data['app_status'][] = 'Session started:' . session_id();
			$this->session_started = true;
		}
	}
/********************************************************************************************************************************/
// Set session data
	function get_s($value_key)
	{
		if($this->session_started)
		{
			return isset($_SESSION['app_data'][$value_key]) ? $_SESSION['app_data'][$value_key] : false;
		}
		else
		{
			$this->response_data['app_debug'] = 'Get session var failed: session not started.';
			return false;
		}
	}
/********************************************************************************************************************************/
// Get session data
	function set_s($value_key,$value)
	{
		if($this->session_started)
		{
			$_SESSION['app_data'][$value_key] = $value;
		}
		else
		{
			$this->target_data['app_debug'] = 'Set session var failed: session not started.';
			return false;
		}
	}
/********************************************************************************************************************************/
// Response data setter
	function set_response($target_id,$data)
	{
		$this->response_data['update_content'][$target_id] = $data;
	}
/********************************************************************************************************************************/
// Response call local JS setter	
	function set_local_js($target_id,$data)
	{
		$this->response_data['local_js'][$target_id] = $data;
	}
/********************************************************************************************************************************/
// Send callback request in response
	function set_callback($target_id,$data)
	{
		$this->response_data['callback_request'][$target_id]= $data;
	}
/********************************************************************************************************************************/
// Set a message dialogue to be displayed on the client
	function set_message($msg_title,$msg_text,$msg_type)
	{
		$this->response_data['app_message'][] = array($msg_title,$msg_text,$msg_type);
	}
/********************************************************************************************************************************/
// Set a message dialogue to be displayed on the client
	function set_dialog($msg_title,$msg_text,$msg_options)
	{
		$this->response_data['app_dialog'][] = array($msg_title,$msg_text,$msg_options);
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
// Load JSON from file and return as PHP array
	function load_json($json_path)
	{
		$json_string = file_get_contents($json_path);
		$json_data = json_decode($json_string,true);
		return $json_data; 
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
			$response_body = $this->response_data;
		}
		
		// Get Accept from request header
		$accept = $_SERVER['HTTP_ACCEPT'];
		// Explode it into an array
		$accept = explode(',',$accept);
		
		// Check the first element for json
		if($accept[0] == 'application/json' || $this->force_json || $force_json)
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
		$this->set_message('Unknown Action','An unknown action was requested: ' . $action_name,'warning');
	}
/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>