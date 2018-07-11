<?php
/********************************************************************************************************************************/
// App switcher
/********************************************************************************************************************************/
class app
{
/********************************************************************************************************************************/
// Constructor
	function __construct()
	{
		// Use app_action class template as default app if one is not defined.
		$this->current_app = defined('APP_DEFAULT_APP') ? APP_DEFAULT_APP : 'app_action';
		
		// Start PHP session
		$start_session = defined('APP_SESSION_START') ? APP_SESSION_START : false;
		if($start_session)
		{
			session_start();
		}
	}
	
/********************************************************************************************************************************/
// Execute
	function execute()
	{
		// Check for requested app
		$app_name = isset($_REQUEST['appx']) ? $_REQUEST['appx'] : $this->current_app;
		
		// Pass off execution to requested application
		if(class_exists($app_name))
		{
			$app = new $app_name();
			$app->execute();
		}
		else
		{
			$error_handler = defined('APP_ERROR_HANDLER') ? APP_ERROR_HANDLER : false;
			if($error_handler)
			{
				//die('APP ERROR HANDLER!!!!');
				$app = new app_error('ACTION HANDLER CLASS NOT FOUND: ' . $app_name);
				$app->execute();
			}
		}

		// Always try and return a response unless the action handler already has.
		if($app->response_returned === false) $app->return_response();
	}

/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>