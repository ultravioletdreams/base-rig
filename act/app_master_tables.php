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
		
		// Set app HTML template path
		$this->html_template_path = './res/html/master_tables.html';
		// Set app title
		$this->app_title = 'Master Tables V1.0 Beta';
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
		$response_body =  $this->template_html($this->html_template_path,false);
		$this->return_response($response_body);
	}
/********************************************************************************************************************************/
// ACTION: Calls local client JS to request startup content from app
	function startup_js()
	{
		// Callbacks to request app content
		$this->set_callback('input_form',false);
		$this->set_callback('update_form',false);
		// Call local JS to attach app handler to action buttons
		$this->set_local_js('attach_actions',false);
	}
	
/********************************************************************************************************************************/
// ALIAS: $this->update_form()
	function refresh_form()
	{
		$this->input_form();
		$this->update_form();
		//$this->set_message('test_1','Error!','An error happened!','error');
		//$this->set_message('test_2','Just Kidding','Everythings O.K.','success');
	}
	
/********************************************************************************************************************************/
// Test alpha
	function do_alpha()
	{
		// error success info testing
		$alpha_mode = $this->get_s('alpha_mode');
		$this->set_s('alpha_mode',!$alpha_mode);
		if($this->get_s('alpha_mode'))
		{
			$this->target_data['error_info'] = array('Alpha','Alpha mode activated.','success');
			$this->set_message('alpha_1','Alpha','Alpha mode activated.','success');
		}
		else
		{
			$this->target_data['error_info'] = array('Alpha','Alpha mode de-activated.','warning');
			$this->set_message('test_1','Alpha','Alpha mode de-activated.','warning');
		}
	}
/********************************************************************************************************************************/
// VIEW: Input form - for creating new row / item into DB.
	function input_form()
	{
		// Send display template for input form
		$content = $this->template_html('./res/html/form_input_obj_type.html',false);
		$page_data['sort_order'] = $this->get_s('sort_order');
		$page_data['sort_by'] = $this->get_s('sort_by');
		$content .= "\n" . $this->template_html('./res/html/form_sort_order.html',$page_data);
		$this->set_response('input_form',$content);
		// Call local JS to attach required request handler to input button
		$this->set_local_js('attach_form_submit',false);
		// Tell client JS to sned request for update list form
		//$this->set_local_js('send_action','update_form');
	}
/********************************************************************************************************************************/
// VIEW: List of update forms one for each DB row / item		
	function update_form()
	{
		// NEW: sort order
		$sort_order = $this->get_s('sort_order');
		$sort_by = $this->get_s('sort_by');
		//$this->set_response('status','SORT ORDER:' . $sort_order);
		// Generate template data - list table contents
		$dbx = new app_test_db();
		$result = $dbx->select_all($sort_order,$sort_by);
		$page_data['result_set'] = $result;
		// Send display template for update form rendered with table data
		$content = $this->template_html('./res/html/form_list_obj_type.html',$page_data);
		$this->set_response('update_form',$content);
		// Call local JS to attach required request handler to input button
		$this->set_local_js('attach_form_submit',false);
		// Call local JS to attach action handlers to buttons
		$this->set_local_js('attach_actions',false);
	}
/********************************************************************************************************************************/
// ACTION: Change sort order
	function sort_order()
	{
		$sort_order = isset($_REQUEST['sort_order']) ? $_REQUEST['sort_order'] : false;
		$sort_by = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : false;
		$this->set_s('sort_order',$sort_order);
		$this->set_s('sort_by',$sort_by);
		$this->set_response('status','Sort order:' . $this->get_s('sort_order') . ' : ' . $sort_order . ' Sort by:' . $sort_by);
		$this->input_form();
		$this->update_form();
	}
	
/********************************************************************************************************************************/
// ACTION: INSERT - Create new item in DB and return result.
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
		$this->set_local_js('send_action','update_form');
	}
/********************************************************************************************************************************/
// ACTION: UPDATE - 
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
			$this->set_response('debug','Updated item number: ' . $result[0]['id']);
		}
		//*/
		// Call refresh content form to see result of update.
		$this->set_local_js('send_action','update_form');
	}
/********************************************************************************************************************************/
// ACTION: REQUEST DELETE - 
	function request_delete()
	{
		// Get request payload / decode to array
		$entityBody = file_get_contents('php://input');
		$tmp = json_decode($entityBody,true);
		
		// Save requested deletion in session
		$this->set_s('requested_id',$tmp['idx']);
		
		// Ask for confirmation
		$response  = '<h2>Confirm deletion of: ' . $this->get_s('requested_id') . ' ?</h2>';
		$response .= '<button id="delete_item" class="action_btn" data-idx="' . $tmp['idx'] . '">Delete</button>';
		$response .= '<button id="cancel_delete" class="action_btn">Cancel</button>';
		$this->set_response('status',$response);
		$this->set_local_js('attach_actions',false);
	}
	
	function cancel_delete()
	{
		$this->set_response('status','');
	}
/********************************************************************************************************************************/
// ACTION: DELETE - 
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
		$this->set_response('debug','Deleted item number: ' . $result[0]['id']);
		}
		
		// Call refresh content form to see result of delete.
		$this->set_local_js('send_action','update_form');
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