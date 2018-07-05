<?php
/********************************************************************************************************************************/
// App default
/********************************************************************************************************************************/
class poi_manager extends app_response
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
// ACTION VIEW: SELECT - from poi table	
	function poi_form()
	{
		// NEW: sort order
		//$sort_order = $this->get_s('sort_order');
		//$sort_by = $this->get_s('sort_by');
		//$this->set_response('status','SORT ORDER:' . $sort_order);
		// Generate template data - list table contents
		$dbx = new poi_manager_db();
		$result = $dbx->select_all();
		$page_data['result_set'] = $result;
		// Send display template for update form rendered with table data
		$content = $this->template_html('./res/app_master/html/poi_result_list.html',$page_data);
		$this->set_response('update_form',$content);
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