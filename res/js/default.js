/*--------------------------------------------------------------------------------------------------------------------------------*/
// Nutshell admin console Javascript functions.
/*--------------------------------------------------------------------------------------------------------------------------------*/

// Run startup when document is ready
$(document).ready(function(){ startup(); });

// Startup function
function startup()
{
	// Create nutshell request object
	//var xapp = new x_app();
	// Send request for content_1
	//xapp.req('content_1');
	
	// Attach button handlers
	$('#example_action_one').on("click", example_action_one);
	$('#example_action_two').on("click", example_action_two);
}	

// *** Test DB connection
function example_action_one()
{
	var xreq = new x_app();
	xreq.req('example_action_one');
}

function example_action_two()
{
	var xreq = new x_app();
	xreq.req('example_action_two');
}