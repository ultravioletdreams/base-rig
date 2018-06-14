<?php
/********************************************************************************************************************************/
// App default
/********************************************************************************************************************************/
class app_master_tables extends app_response
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
			
			case 'test_entry':
				$this->test_entry();
			break;
			
			case 'reset_entries':
				$this->reset_entries();
			break;
			
			case 'list_tests':
				$this->list_tests();
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
// Create test entry
	function test_entry()
	{
		$data = 'TEST ENTRY';
		$server_stamp = date("Y-m-d H:i:s");
		// Insert test entry into db table.
		$dbx = new app_test_db();
		$result = 'New entry id: ' . $dbx->create_entry($data,$server_stamp);
		
		$target_data = array('content_one' => $result );
		$response_data = array('target_data' => $target_data);
		$this->return_json($response_data);
	}
/********************************************************************************************************************************/
// reset_entries
	function reset_entries()
	{
		// Truncate test table and reset sequence : reset identity
		$dbx = new app_test_db();
		$result = $dbx->error_msg . ' : ' . print_r($dbx->reset_entries(),true);
		
		$target_data = array('content_one' => $result );
		$response_data = array('target_data' => $target_data);
		$this->return_json($response_data);
	}
/********************************************************************************************************************************/
// List test entries
	function list_tests()
	{	
		// List all test entries 
		$dbx = new app_test_db();
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