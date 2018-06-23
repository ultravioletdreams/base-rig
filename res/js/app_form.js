/*--------------------------------------------------------------------------------------------------------------------------------*/
// Attach app form submit handler to forms
/*--------------------------------------------------------------------------------------------------------------------------------*/

// *** ADDING IN FORM SUBMIT FUNCTIONS ***
function attach_form_submit()
{
	// Debug
	if(debug_mode) console.log( "Attached to form submit button." );
	
	// Overide default form submit use async form submit.
	$("form").submit( function( event ) 
	{
		if(debug_mode) console.log('DO FORM SUBMIT ->');
		// Get id of form that was submitted.
		var form_id = $(this).closest("form").attr("id");
		console.log(form_id);
		var xreq = new x_app();
		xreq.form_submit('#' + form_id);
		event.preventDefault();
	});
}