<?php
/********************************************************************************************************************************/
// App default
/********************************************************************************************************************************/
class app_action extends app_response
{
/********************************************************************************************************************************/
// Constructor
	function __construct()
	{
	}
	
/********************************************************************************************************************************/
// Execute
	function execute()
	{
		// Check for requested action
		$app_action = isset($_REQUEST['ax']) ? $_REQUEST['ax'] : 'default_action';
		
		// Execute request action
		switch($app_action)
		{	
			case 'default_action':
				$this->default_action();
			break;
			
			case 'example_action_one':
				$content = array();
				// Send data back to different targets
				$content['example_target_one'] = "<h1>Example response one.</h1>";
				$content['debug'] = "<h1>Success.</h1>";
				// Target data target_data expected on client side in js
				$target_data = array('target_data' => $content);
				// Send target_data as JSON in response back to client
				$this->return_json($target_data);
			break;
			
			case 'example_action_two':
				$content = array();
				// Send data back to target(s)
				$content['example_target_two'] = "<h1>Example response two.</h1>";
				$content['debug'] = "<h1>Success.</h1>";
				// Target data target_data expected on client side in js
				$target_data = array('target_data' => $content);
				// Send target_data as JSON in response back to client
				$this->return_json($target_data);
			break;
			
			default:
				$this->unknown_action($app_action);
			break;
		}
	}
/********************************************************************************************************************************/
// ACTIONS
/********************************************************************************************************************************/

/********************************************************************************************************************************/	
// Unknown action
	function unknown_action($app_action)
	{
		$target_data = array('debug' => 'Unknown action: ' . $app_action, 'content_one' => 'debug' );
		$response_data = array('target_data' => $target_data);
		$this->return_json($response_data);
	}
/********************************************************************************************************************************/	
// Default action
	function default_action()
	{
		$response_body =  $this->template_html('./html/template_default.html',false);
		$this->return_html($response_body);
	}
/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>