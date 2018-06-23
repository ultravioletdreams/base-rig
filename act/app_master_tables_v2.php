<?php
/********************************************************************************************************************************/
// App default
/********************************************************************************************************************************/
class app_master_tables_v2 extends app_response
{
/********************************************************************************************************************************/
// Constructor
	function __construct()
	{
		// Call parent class constructor
		parent::__construct();
		
		// Set app HTML template path
		$this->html_template_path = './res/html/template.html';
	}

/********************************************************************************************************************************/
// Execute - Perform requested action
	function execute()
	{	
		// Check for requested action
		$app_action = isset($_REQUEST['ax']) ? $_REQUEST['ax'] : 'startup_view';
		
		// Reset debug
		$this->set_response('debug','');
		
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
// Return initial application HTML from template
	function startup_view()
	{
		$response_body =  $this->template_html($this->html_template_path,false);
		$this->return_response($response_body);
	}
	
/********************************************************************************************************************************/
	function startup_js()
	{
		$this->set_local_js('send_action','content_form');
		$this->set_local_js('attach_actions',false);
		$this->set_local_js('attach_actions',false);
	}
/********************************************************************************************************************************/
	function test_1()
	{
		$this->set_response('content','<h1>TEST 1</h1>');
		$this->set_local_js('send_action','test_2');
	}
/********************************************************************************************************************************/
	function test_2()
	{
		$this->set_response('content','<h1>TEST 2</h1>');
	}
/********************************************************************************************************************************/
	function test_3()
	{
		$this->set_response('content','<h1>TEST 3</h1>');
	}
/********************************************************************************************************************************/
	function waste()
	{
		$counter = 0;
		for($d=0;$d<1000;$d++)
		{
			$counter++;
			for($f=0;$f<1000;$f++)
			{
				$counter++;
				for($g=0;$g<100;$g++)
				{
					$counter++;
				}
			}
		}
		$this->set_response('content',"<h1>Counter = $counter</h1>");
	}
/********************************************************************************************************************************/
	// Return test form to application content container div.
	function content_form()
	{
		// Generate template data - list table contents
		$dbx = new app_test_db();
		$result = $dbx->select_all();
		$page_data['table_list'] = $result;
		// Send display template
		$html_snip = $this->template_html('./res/html/form_obj_type.html',$page_data);
		$this->set_response('content',$html_snip);
		$this->set_local_js('attach_form_submit',false);
		$this->set_local_js('attach_actions',false);
	}
/********************************************************************************************************************************/
	// Create new item in DB and return result.
	function new_item_1()
	{
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
		
		// Call refresh content form to see new item.
		$this->set_local_js('send_action','content_form');
	}
/********************************************************************************************************************************/
// UPDATE - 
	function update_item()
	{
		// Get request payload / decode to array
		//$entityBody = file_get_contents('php://input');
		//$tmp = json_decode($entityBody,true);
		
		//$this->set_response('content',print_r($_REQUEST,true));
		///*
		// QUERY - UPDATE
		$entry_values[0] = $_REQUEST['id'];
		$entry_values[1] = $_REQUEST['type_id'];
		$entry_values[2] = $_REQUEST['type_name'];
		
		$tmp_db = new app_test_db();
		$result = $tmp_db->update_entry($entry_values);
		
		if(count($result) <= 0)
		{
			$result = 'Nothing Updated!';
			$this->set_response('debug',$result);
		}
		else
		{
			// RESULT
			$this->set_response('debug','Delted item number: ' . $result[0]['id']);
		}
		//*/
		// Call refresh content form to see result of update.
		//$this->set_local_js('send_action','content_form');
	}
/********************************************************************************************************************************/
// DELETE - 
	function delete_item()
	{
		// Get request payload / decode to array
		$entityBody = file_get_contents('php://input');
		$tmp = json_decode($entityBody,true);
		// QUERY - DELETE
		$tmp_db = new app_test_db();
		$result = $tmp_db->delete_entry($tmp['idx']);
		if(count($result) <= 0)
		{
			$result = 'Nothing Deleted!';
			$this->set_response('debug',$result);
		}
		else
		{
		// RESULT
		$this->set_response('debug','Delted item number: ' . $result[0]['id']);
		}
		
		// Call refresh content form to see result of delete.
		$this->set_local_js('send_action','content_form');
	}
/********************************************************************************************************************************/
// Default - Unknown action requested.
	function default_action($action_name)
	{
		$this->set_response('debug','Unknown action: ' . $action_name);
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