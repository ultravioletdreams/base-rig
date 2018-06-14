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
	}
	
/********************************************************************************************************************************/
// Execute
	function execute()
	{
		// Check for requested app
		$app_name = isset($_REQUEST['appx']) ? $_REQUEST['appx'] : $this->current_app;
		
		// Check we have a registered application by that name
		// TODO ... 
		
		// Pass off execution to requested application
		$app = new $app_name();
		$app->execute();
	}

/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>