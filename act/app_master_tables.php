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
		// Call parent class constructor
		parent::__construct();
		
		// ##$##$$ Clear content_one between actions
		$this->set_response('content_one','');
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
			
			case 'select_table':
				// Generate template data - list table contents
				$dbx = new app_test_db();
				$result = $dbx->list_all();
				$page_data['table_list'] = $result;
				// Send display template
				$html_snip = $this->template_html('./res/html/form_obj_type.html',$page_data);
				$this->set_response('content',$html_snip);
				$this->set_local_js('attach_form_submit',false);
			break;
			
			case 'test_entry':
				//$this->create_test_entry();
				//$this->list_tests();
				$this->set_response('content_one','Function Disabled...');
			break;
			
			case 'new_item_1':
				// CREATE
				$tmp_db = new app_test_db();
				$val_1 = isset($_REQUEST['type_id']) ? $_REQUEST['type_id'] : false;
				$val_2 = isset($_REQUEST['type_name']) ? $_REQUEST['type_name'] : false;
				if( $val_1 && $val_2)
				{
					$result = $tmp_db->create_master_entry($val_1,$val_2);
					$this->set_response('debug','New ID: ' . $result);
				}
				else
				{
					$result = '<h3>Input data is invalid.</h3>';
					$this->set_response('debug',$result);
				}	
				//
				//$this->list_tests();
			break;
			
			case 'reset_entries':
				//$this->reset_entries();
				//$this->list_tests();
				$this->set_response('content_one','Function Disabled...');
			break;
			
			case 'list_tests':
				$this->list_tests();
			break;
			
			case 'test':
				$this->return_response(false,true);
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
		$this->return_response($response_data);
	}
/********************************************************************************************************************************/	
// Startup app view
	function startup_view()
	{
		$response_body =  $this->template_html('./res/html/template.html',false);
		$this->return_response($response_body);
	}
/********************************************************************************************************************************/
// Create test entry
	function create_test_entry()
	{
		$data = 'TEST ENTRY';
		$server_stamp = date("Y-m-d H:i:s");
		// Insert test entry into db table.
		$dbx = new app_test_db();
		$result = 'New entry id: ' . $dbx->create_test_entry($data,$server_stamp);
		$this->set_response('content_one',$result);
		//$this->return_response();
	}
/********************************************************************************************************************************/
// reset_entries
	function reset_entries()
	{
		// Truncate test table and reset sequence : reset identity
		$dbx = new app_test_db();
		$result = $dbx->error_msg . ' : ' . print_r($dbx->reset_entries(),true);
		
		$this->set_response('content_one',$result);
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
				$content .= '<li>' . $row['id'] . ' : ' . $row['type_id'] . ' : ' . $row['type_name'] . '</li>';
			}
			$content .= '</ul>';
		}
		
		$this->set_response('item_list',$content);
	}
/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>