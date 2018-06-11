<?php
/********************************************************************************************************************************/
// App switcher
/********************************************************************************************************************************/
class app
{
	/****************************************************************************************************************************/
	// Constructor
	function __construct()
	{
	}
	
	/****************************************************************************************************************************/
	// Execute
	function execute()
	{
		// Check for requested app
		$app_name = isset($_REQUEST['appx']) ? $_REQUEST['appx'] : 'app_default';
		
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