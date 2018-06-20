/*--------------------------------------------------------------------------------------------------------------------------------*/
// Nutshell admin console Javascript functions.
/*--------------------------------------------------------------------------------------------------------------------------------*/

// *** ADDING IN FORM SUBMIT FUNCTIONS ***
function attach_form_submit()
{
	console.log( "Attached to form submit button." );
	
	$("form").submit( function( event ) 
	{
		console.log('DO FORM SUBMIT ->');
		// Get id of form that was submitted.
		var form_id = $(this).closest("form").attr("id");
		console.log(form_id);
		var xreq = new x_app();
		xreq.form_submit('#' + form_id);
		event.preventDefault();
	});
}


// Run startup when document is ready
$(document).ready(function(){ startup(); });

// Startup function
function startup()
{	
	// Attach button handlers
	$('#list_tables').on("click", list_tables);
	$('#test_entry').on("click", test_entry);
	$('#reset_entries').on("click", reset_entries);
	$('#select_table').on("click", select_table);
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
// *** Test DB connection
function select_table()
{
	var xreq = new x_app();
	xreq.req('select_table');
}