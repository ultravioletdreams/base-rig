<?php
/********************************************************************************************************************************/
// App default
/********************************************************************************************************************************/
class demo extends app_response
{
/********************************************************************************************************************************/
// Constructor
	function __construct()
	{
		// Call parent class constructor
		parent::__construct();
		
		// Set app HTML template path
		$this->html_template_path = './res/html/demo.html';
		$this->app_title = 'Demo Application - V0.1 Delta';
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
// VIEW: Return initial application HTML from template
	function startup_view()
	{
		$response_body =  $this->template_html($this->html_template_path,false);
		$this->set_local_js('attach_actions',false);
		$this->return_response($response_body);
	}
/********************************************************************************************************************************/
// ACTION: Send local JS calls
	function startup_js()
	{
		$this->set_local_js('attach_actions',false);
	}
/********************************************************************************************************************************/
// ACTION: Send local JS calls
	function example_action_1()
	{
		$post_data = $this->get_post_body();
		$response = '<h2>You pressed example 1.' .  $post_data['test1'] . '</h2>';
		$this->set_response('target_item_1',$response);
	}
/********************************************************************************************************************************/
// ACTION: Send local JS calls
	function example_action_2()
	{
		$response = '<h2>You pressed example 2. But it actually calls Example 1 & 3 also</h2>';
		$this->set_response('target_item_2',$response);
		// Tell app to call 1 and 3
		$this->set_callback('example_action_1',false);
		$this->set_callback('example_action_3',false);
	}
/********************************************************************************************************************************/
// ACTION: Send local JS calls
	function example_action_3()
	{
		$response = '<h2>You pressed example 3.</h2>';
		$this->set_response('target_item_3',$response);
	}
/********************************************************************************************************************************/
// ACTION: Clear all targets
	function clear()
	{
		$response = '';
		$this->set_response('target_item_1',$response);
		$this->set_response('target_item_2',$response);
		$this->set_response('target_item_3',$response);
	}
/********************************************************************************************************************************/
// ACTION: Take a long time for progress indicator testing...
	function waiter()
	{
		$counter = 0;
		//for($z = 1; $z <= 1000; $z++){};
		for($z = 0; $z < 1000; $z++ )
		{ 
			$counter++;
			for($x=0;$x<500;$x++)
			{
				$counter++;
				for($y=0;$y<100;$y++)
				{
					$counter++;
				}
			}
		}
//*/
		$stamp = date('d-m-Y H:i:s (e)');
		$this->set_response('target_item_1','Counter: ' . $counter);
		$this->set_response('target_item_2','Stamp: ' . $stamp);
	}
/********************************************************************************************************************************/
// DEFAULT: Default - Unknown action requested.
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