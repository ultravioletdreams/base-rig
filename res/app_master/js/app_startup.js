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
	if(local_debug != false) console.log('%c APP START ','background: #000; color:#fff;');
	
	// Request local script calls from app.
	send_action('startup_js');	
}

// Send action requests.
function send_action(action_id,action_params,action_handler)
{
	var xreq = new x_app();
	xreq.action_handler = "app_master_tables";
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
	
	// Attach handler to buttons with class: action_btn
	var btns = $('.local_action_btn');
	$.each(btns,function (key,value)
	{
		$(value).off('click');
		$(value).on('click', btn_local);
	});
}

// Handle clicks on action buttons - SEND ACTION REQUEST
function btn_action()
{
	// ***  Get button ID
	// ***  var button_id = $(this).attr('id');
	
	// *** GET data attributes from calling element. This tells us what to request and where to request it from.
	var action_params =  $(this).data();
	
	// *** GET HANDLER PARAMETER: data-app-handler / appHandler - Sets which action handler php class on the server that the action request goes to.
	console.log('%c' + action_params.appHandler,'background:#000;color:#0f0;');
	var app_handler = (typeof action_params.appHandler !== 'undefined') ? action_params.appHandler : false;
	
	// *** GET ACTION PARAMETER: data-app-action / appAction - Sets which action to request server side.
	console.log('%c' + action_params.appAction,'background:#000;color:#00f;');
	var app_action = (typeof action_params.appAction !== 'undefined') ? action_params.appAction : false;
	
	// Send request 
	//send_action(button_id,action_params,app_handler);
	if(app_action !== false) send_action(app_action,action_params,app_handler);
}

// Handle clicks on action buttons - LOCAL JS CALLS
function btn_local()
{
	// Get button ID
	var button_id = $(this).attr('id');
	// Get any data attributes from button
	var action_params =  $(this).data();
	
	try
	{
		window[button_id](action_params);
	}
	catch(err)
	{
		console.log('No local JS!' + err.message);
	}
	//console.log(action_params);
	//test_swal();
	// ***
	//if(local_debug != false) console.log(action_params.actionHandler);
	//var action_handler = (typeof action_params.actionHandler !== 'undefined') ? action_params.actionHandler : false;
	// Send request 
	//send_action(button_id,action_params,action_handler);
}