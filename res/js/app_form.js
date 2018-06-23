/*--------------------------------------------------------------------------------------------------------------------------------*/
// Attach app form submit handler to forms
/*--------------------------------------------------------------------------------------------------------------------------------*/

// *** ADDING IN FORM SUBMIT FUNCTIONS ***
function attach_form_submit()
{
	// Debug
	if(debug_mode > 1) console.log( "Attached to form submit button." );
	
	// Remove any previously attached events from from submit biuttons
	$("form").off();
	// Overide default form submit use async form submit.
	$("form").submit( function( event ) 
	{
		if(debug_mode > 1) console.log('DO FORM SUBMIT ->');
		// Get id of form that was submitted.
		var form_id = $(this).closest("form").attr("id");
		if(debug_mode > 1) console.log('SUBMITTING FORM: ' + form_id);
		var xreq = new x_app();
		// Set form id of form to submit
		xreq.form_id = '#' + form_id;
		// Blank form inputs after request
		xreq.reset_form = true;
		// Submit form
		xreq.form_submit();
		event.preventDefault();
	});
}