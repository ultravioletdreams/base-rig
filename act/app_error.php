<?php
/********************************************************************************************************************************/
// App default
/********************************************************************************************************************************/
class app_error extends app_response
{
/********************************************************************************************************************************/
// Constructor
	function __construct($error_msg=false)
	{
		// Call parent class constructor
		parent::__construct();
		
		$this->error_msg = $error_msg;
		
		// Set app HTML template path
		$this->html_template_path = './res/app_error/html/app_error.html';
		// Set app title
		$this->app_title = 'Application Error Handler - DEV 0.1 Alpha';
	}

/********************************************************************************************************************************/
// Execute - Perform requested action
	function execute()
	{	
		// Check for requested action
		$app_action = isset($_REQUEST['ax']) ? $_REQUEST['ax'] : 'startup_view';
		
		if(method_exists($this,$app_action))
		{
			// Call requested action
			$this->$app_action();
		}
		else
		{
			$this->default_action($app_action);
		}
	}	
	
/********************************************************************************************************************************/
// VIEW: Return initial application HTML from template
	function startup_view()
	{
		$data['error_msg'] = $this->error_msg;
		$response_body =  $this->template_html($this->html_template_path,$data);
		$this->return_response($response_body);
	}
/********************************************************************************************************************************/
// ACTION: Calls local client JS to request startup content from app
	function startup_js()
	{
		// Call local JS to attach app handler to action buttons
		//$this->set_local_js('attach_actions',false);
		$this->set_message('Application Error!','An application error has occurred!','error');
	}
/********************************************************************************************************************************/

/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>