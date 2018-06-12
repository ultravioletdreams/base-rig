<?php
/********************************************************************************************************************************/
// App default
/********************************************************************************************************************************/
class app_default extends response
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
		$app_action = isset($_REQUEST['ax']) ? $_REQUEST['ax'] : 'startup_view';
		
		// Execute request action
		switch($app_action)
		{
			case 'startup_view':
				$this->startup_view();
			break;
			
			case 'list_tables':
				$this->test_db_list();
			break;
			
			default:
				$this->default_action($app_action);
			break;
		}
	}
/********************************************************************************************************************************/
// ACTIONS
/********************************************************************************************************************************/

/********************************************************************************************************************************/	
// Default action
	function default_action($app_action)
	{
		$target_data = array('debug' => 'Unknown action: ' . $app_action, 'content_one' => 'debug' );
		$response_data = array('target_data' => $target_data);
		$this->return_json($response_data);
	}
/********************************************************************************************************************************/	
// Startup app view
	function startup_view()
	{
		$response_body =  $this->template_html('./html/template.html',false);
		$this->return_html($response_body);
	}
/********************************************************************************************************************************/
// *** Test DB connection

	function test_db_list()
	{	
		// Insert test entry into db.
		$dbx = new db();
		$result = $dbx->list_all();
		
		if($result === false)
		{
			$content = 'NO DATA RETURNED, LAST ERROR: ' . $db->error_msg;
		}
		else
		{
			$content = '<ul>';
			foreach($result as $row)
			{
				$content .= '<li>' . $row['id'] . ' : ' . $row['data'] . ' : ' . $row['teststamp'] . '</li>';
			}
			$content .= '</ul>';
		}
		
		$data = array('content_two' => $content);
		$response = array('target_data' => $data);
		$this->return_json($response);
	}
/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>