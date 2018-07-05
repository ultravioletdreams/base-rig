/*--------------------------------------------------------------------------------------------------------------------------------*/
// Attach app form submit handler to forms
/*--------------------------------------------------------------------------------------------------------------------------------*/
var form_debug = true;

function attach_form_submit()
{
	if(form_debug != false) console.log('FUNCTION: attach_form_submit');
	
	// Get all forms on page and list their names
	var myforms = $('form');
	$.each(myforms, function(key,value)
	{
		if(form_debug != false) console.log('FORM:' + key + ' : ' + $(value).attr('id'));
		$(value).parsley();
		$(value).parsley().on('form:submit', function() {
			if(form_debug != false) console.log('Form submit attempted.');
			if(form_debug != false) console.log(this.$element.attr('id'));
			do_submit(this);
			// Prevent default form submit
			return false;
		});
	});
}

// Isolate the subit function
function do_submit(me)
{
	if(form_debug != false) console.log('DO FORM SUBMIT ->');
		// Get id of form that was submitted.
		var form_id = me.$element.attr("id");
		console.log('DO:' + form_id);
		
		var xreq = new x_app();
		// Set form id of form to submit
		xreq.form_id = '#' + form_id;
		// Blank form inputs after request
		xreq.reset_form = true;
		// Submit form
		xreq.form_submit();
}