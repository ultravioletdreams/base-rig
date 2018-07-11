/*--------------------------------------------------------------------------------------------------------------------------------*/
// App startup
/*--------------------------------------------------------------------------------------------------------------------------------*/

// Run startup when document is ready
$(document).ready(function(){ startup(); });

var local_debug = true;

// Startup function
function startup()
{	
	// ***
	if(local_debug != false) console.log('%c APP START ERROR','background: #000; color:#fff;');
	
	// Request local script calls from app.
	send_action('startup_js');	
}

// Send action requests.
function send_action(action_id,action_params,action_handler)
{
	var xreq = new x_app();
	xreq.action_handler = "app_error";
	xreq.ajax_req(action_id,action_params,action_handler);
}

// Attach request to action buttons
function attach_actions()
{
	// ***
	if(local_debug != false) console.log('%c ATTACH ACTIONS ','background: #DFF0D8; color:#468847;');
	
	// Attach handler to buttons with class: action_btn
	var btns = $('.action_btn');
	$.each(btns,function (key,value)
	{
		$(value).off('click');
		$(value).on('click', btn_action);
	});
}

// Handle clicks on action buttons
function btn_action()
{
	// Get button ID
	var button_id = $(this).attr('id');
	// Get any data attributes from button
	var action_params =  $(this).data();
	// ***
	console.log(action_params.actionHandler);
	var action_handler = (typeof action_params.actionHandler !== 'undefined') ? action_params.actionHandler : false;
	// Send request 
	send_action(button_id,action_params,action_handler);
}