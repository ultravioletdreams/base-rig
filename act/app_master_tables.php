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
		$this->html_template_path = './res/html/template.html';
	}
	
/********************************************************************************************************************************/
// Execute - Perform requested action
	function execute()
	{
		// Check for requested action
		$app_action = isset($_REQUEST['ax']) ? $_REQUEST['ax'] : 'startup_view';
		
		// Execute request action
		switch($app_action)
		{
			// Return initial application HTML from template
			case 'startup_view':
				$response_body =  $this->template_html($this->html_template_path,false);
				$this->return_response($response_body);
			break;
			
			case 'startup_js':
				$this->set_local_js('select_table',false);
			break;
			
			// Return test form to application content container div.
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
			
			// Create new item in DB and return result.
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
			break;
			
			// Default - Unknown action requested.
			default:
				$this->set_response('debug','Unknown action: ' . $app_action);
			break;
		}
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