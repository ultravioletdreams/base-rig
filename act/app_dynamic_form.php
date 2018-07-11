<?php
/********************************************************************************************************************************/
// App default
/********************************************************************************************************************************/
class app_dynamic_form extends app_response
{
/********************************************************************************************************************************/
// Constructor
	function __construct()
	{
		// Call parent class constructor
		parent::__construct();
		
		// Force JSON response
		$this->force_json = true;
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
// ACTION: UPDATE Form
	function test_swal()
	{
		$this->set_local_js('test_swal',false);
	}
/********************************************************************************************************************************/
// ACTION: UPDATE Form
	function new_form()
	{
		$db = new master_tables_db();
		$template_data['result_set'] = $db->select_all();
		// Generate result list HTML 
		$tmp = $this->template_html('./res/app_master/html/result_list.html',$template_data);
		$this->set_response('update_form',$tmp);
		// Call local JS to attach required request handler to input button
		$this->set_local_js('attach_form_submit',false);
		// Call local JS to attach action handlers to buttons
		$this->set_local_js('attach_actions',false);
	}
/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>