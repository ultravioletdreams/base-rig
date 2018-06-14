/*--------------------------------------------------------------------------------------------------------------------------------*/
// Nutshell admin console Javascript functions.
/*--------------------------------------------------------------------------------------------------------------------------------*/

// Run startup when document is ready
$(document).ready(function(){ startup(); });

// Startup function
function startup()
{	
	// Attach button handlers
	$('#list_tables').on("click", list_tables);
	$('#test_entry').on("click", test_entry);
	$('#reset_entries').on("click", reset_entries);
}	

// *** Test DB connection
function list_tables()
{
	var xreq = new x_app();
	xreq.req('list_tests');
}
// *** Test DB connection
function test_entry()
{
	var xreq = new x_app();
	xreq.req('test_entry');
}
// *** Test DB connection
function reset_entries()
{
	var xreq = new x_app();
	xreq.req('reset_entries');
}