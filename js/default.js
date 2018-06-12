/*--------------------------------------------------------------------------------------------------------------------------------*/
// Nutshell admin console Javascript functions.
/*--------------------------------------------------------------------------------------------------------------------------------*/

// Run startup when document is ready
$(document).ready(function(){ startup(); });

// Startup function
function startup()
{
	// Create nutshell request object
	var xapp = new x_app();
	// Send request for content_1
	xapp.req('content_1');
	
	// Attach button handlers
	$('#test').on("click", test_db);
}	

// *** Test DB connection
function test_db()
{
	var xreq = new x_app();
	xreq.req('list_tables');
}