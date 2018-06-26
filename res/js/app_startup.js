/*--------------------------------------------------------------------------------------------------------------------------------*/
// App startup
/*--------------------------------------------------------------------------------------------------------------------------------*/

// Run startup when document is ready
$(document).ready(function(){ startup(); });

// Startup function
function startup()
{	
	// Request local script calls from app.
	send_action('startup_js');
	
}

// Attach request to action buttons
function attach_actions()
{
	if(debug_mode) console.log('Attaching action handler to buttons.');
	var btns = $('.action_btn');
	$.each(btns,function (key,value)
	{
		if(debug_mode > 1) console.log('Button:' + key + ' : ' + $(value).attr('id'));
		$(value).off('click');
		$(value).on('click',btn_action);
	});
}

function btn_action()
{
	// Get button ID
	button_id = $(this).attr('id');
	if(debug_mode) console.log('btn_action() - Called - ' + button_id + ', Button, ' + button_id + ' clicked.' );
	// Get any data attributes from button
	var action_params =  $(this).data();
	// Send request 
	send_action(button_id,action_params);
}

// Generic action sender
function send_action(action_id,action_params)
{
	if(debug_mode) console.log('Sending request: ' + action_id);
	var xreq = new x_app();
	xreq.ajax_req(action_id,action_params);
}