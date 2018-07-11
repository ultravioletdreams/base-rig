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
		$this->html_template_path = './res/app_master/html/index.html';
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
		//$this->set_callback('input_form',false);
		$this->set_callback('update_form',false);
		// Call local JS to attach app handler to action buttons
		$this->set_local_js('attach_actions',false);
	}
/********************************************************************************************************************************/
// ******* TODO List
function todo()
{
	$this->set_message('TODO LIST','Make set message capable of handling Delete request message','info');
	$this->set_message('TODO LIST','Once set message can do it, make delete request a remote action again.','info');
	$this->set_message('TODO LIST','Continue form templating, have a think about form wrapper in form gen.','info');
	$this->set_message('TODO LIST','Continue form templating - Sort Order!','info');
}
/********************************************************************************************************************************/
// ******* TODO List
function dialogue()
{
	// Sweet alert options
	$options['dangerMode'] = true;
	$options['buttons']['cancels']['text'] = "Cancel";
	$options['buttons']['cancels']['value'] = "no";
	$options['buttons']['confirm']['text'] = "Yes";
	$options['buttons']['confirm']['value'] = "yes";
	$options['buttons']['other']['text'] = "Other";
	$options['buttons']['other']['value'] = "101";
	$options['buttons']['other']['value'] = "101";
	
	// Dialogue response options
	$response['app_dialog']['options'] = $options;
	$response['app_dialog']['title'] = 'Test Choice';
	$response['app_dialog']['message'] = 'Make a choice.';
	//$response['app_dialog']['type'] = 'success';
	
	// Action to send dialogue result to.
	$response['app_dialog']['callback'] = 'confirm_dialogue';
	
	$this->return_response($response,true);
	//$this->set_dialog('Dialogue result','You chose: ' . $tmp['result'],$options);
}

// ******** CONFIRM DIALOGUE
function confirm_dialogue()
{	
	$tmp = $this->get_post_body();
	$this->set_message('Dialogue result','You chose: ' . $tmp['result'],'success');
}
/********************************************************************************************************************************/
// ALIAS: $this->update_form()
	function test_json()
	{	
		//$tmp = $this->load_json('./res/app_master/html/def_object_type_input.json');
		
		$form_def = array();
$form_def['inputs'][] = array('label' => false,       'type' => 'hidden', 'name' => 'ax',        'value_type' => 'static', 'value' => 'update_item',  'data-x' => 'null');
$form_def['inputs'][] = array('label' => false,       'type' => 'hidden', 'name' => 'idx',       'value_type' => 'dynamic', 'value' => 'id',           'data-x' => 'null');
$form_def['inputs'][] = array('label' => 'ID',        'type' => 'hidden', 'name' => 'id',        'value_type' => 'dynamic', 'value' => 'id',           'data-x' => 'null');
$form_def['inputs'][] = array('label' => 'Type ID',   'type' => 'text', 'name' => 'type_id',   'value_type' => 'dynamic', 'value' => 'type_id',      'data-x' => 'data-parsley-required data-parsley-type="integer"');
$form_def['inputs'][] = array('label' => 'Type Name', 'type' => 'text', 'name' => 'type_name', 'value_type' => 'dynamic', 'value' => 'type_name',    'data-x' => 'data-parsley-required data-parsley-minlength="4"');

		$this->return_response($form_def,true);
	}	
/********************************************************************************************************************************/
// ALIAS: $this->update_form()
	function refresh_form()
	{
		//$this->input_form();
		$this->update_form();
	}
/********************************************************************************************************************************/
// VIEW: Input form - for creating new row / item into DB.
	function input_form()
	{
		// Send display template for input form
		$content = $this->template_html('./res/app_master/html/form_input_obj_type.html',false);
		$page_data['sort_order'] = $this->get_s('sort_order');
		$page_data['sort_by'] = $this->get_s('sort_by');
		$content .= "\n" . $this->template_html('./res/app_master/html/form_sort_order.html',$page_data);
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
		// Generate template data - list table contents
		$sort_order = $this->get_s('sort_order');
		$sort_by = $this->get_s('sort_by');
		$dbx = new master_tables_db();
		$result = $dbx->select_all($sort_order,$sort_by);
		$page_data['result_set'] = $result;
		// Set list sort order and type 
		$page_data['sort_order'] = $this->get_s('sort_order');
		$page_data['sort_by'] = $this->get_s('sort_by');
		// Load form definitions from JSON
		$page_data['input_form_def'] = $this->load_json('./res/app_master/html/def_object_type_input.json');
		$page_data['update_form_def'] = $this->load_json('./res/app_master/html/def_object_type_list.json');
		// Generate Input/Insert And Update forms.
		$fgen = new form_gen();
		$page_data['form_1'] = $fgen->do_form($page_data['input_form_def']);
		$page_data['form_2'] = $fgen->do_list($result,$page_data['update_form_def']);
		// Send display template for update form rendered with table data
		$content = $this->template_html('./res/app_master/html/result_list_dev.html',$page_data);
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
		$this->update_form();
	}
	
/********************************************************************************************************************************/
// ACTION: INSERT - Create new item in DB and return result.
	function new_item_1()
	{
		// CREATE
		$tmp_db = new master_tables_db();
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
		
		$tmp_db = new master_tables_db();
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
// ACTION: DELETE - 
	function delete_item()
	{
		// Get request payload / decode to array
		$entityBody = file_get_contents('php://input');
		$tmp = json_decode($entityBody,true);
		// QUERY - DELETE
		$tmp_db = new master_tables_db();
		$result = $tmp_db->delete_entry($tmp['idx']);
		if(count($result) <= 0)
		{
			$result = 'Nothing Deleted!';
			$this->set_response('debug',$result);
			$this->set_message('Nothing Deleted!','The item requested was not deleted!','error');
		}
		else
		{
		// RESULT
		$this->set_response('debug','Deleted item number: ' . $result[0]['id']);
		$this->set_message('Item Deleted','Deleted item number: ' . $result[0]['id'],'success');
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