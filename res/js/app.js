// baserig - Clientside request / response handler

function x_app()
{	
	// Make this accessible through me
	var me = this;
	
	// Object variables
	this.debug_mode = 0;
	this.request_id = false;
	this.form_id = false;
	this.reset_form = false;
	
	// Request parameter name - the POST/GET variable name that the action handler id will be looked for server side.
	this.action_handler_param_id = 'appx';
	
	// Request parameter name - the POST/GET variable name that the action id will be looked for server side.
	this.action_param_id = 'ax';
	
	// Set base request URL where requests will be sent.
	this.request_url = 'index.php';
	
	// Send an action request and handle the response.
	this.ajax_req = function(action,action_params,action_handler)
	{
		// ***
		if(me.debug_mode >= 1) console.log('%c SEND REQUEST: ' + action,'background: #DFF0D8; color:#468847;');
		
		// Build request URL
		var request_url = me.request_url + '?' + me.action_param_id + '=' + action;
		
		// *** Alter request destination action handler
		if(typeof action_handler !== 'undefined')
		if(action_handler !== false)
		{
			// Append action handler to request
			request_url += '&appx=' + action_handler;
		}
		
		// Save action as request id
		me.request_id = action;
		
		// *** NEEDS WORK!!! Show loading indicator
		$('#loading').show();
		
		// Send request 
		$.ajax({
			type: 'POST',
			//url: me.request_url + '?ax=' + action,
			url: request_url,
			data: JSON.stringify(action_params), 
			success: me.req_done,
			error: me.req_fail,
			contentType: "application/json",
			dataType: 'json'
		});
	}

	// Update base template with application view conatiners and data
	this.req_done = function(data)
	{
		// *** 
		if(me.debug_mode >= 1) console.log('%c' + me.request_id + ': REQUEST OK ','background: #DFF0D8; color:#468847;');
		
		// *** Loading indicator
		$('#loading').hide();
		
		// Application message handler
		me.app_message(data);
		
		// Update content handler
		me.update_content(data);
		
		// Call local JS functions specified in response
		me.call_local_js(data);
		
		// Running callback requests
		me.callback_requests(data);
		
		// Debug alerts
		if(typeof data['error_info'] != 'undefined')
		{
			console.log('Error info: ' + data['error_info'][0] + ' : ' + data['error_info'][1] + ' : ' + data['error_info'][2]);
			swal(data['error_info'][0], data['error_info'][1], data['error_info'][2]);
			//swal("Good job!", "You clicked the button!", "success");
		}
	}
	
	// Form submit success handler
	this.form_done = function(data)
	{
		// ***
		if(me.debug_mode >= 2) console.log('%c' + me.form_id + ': FORM  REQUEST  OK ','background: #DFF0D8; color:#468847;');
		
		// Reset form inputs
		if(me.reset_form) $(me.form_id)[0].reset();
		
		// Pass response data onto normal request success handler
		me.req_done(data);
	}

	// Handle failure off async call to update application view
	this.req_fail = function (data)
	{
		$('#loading').hide();
		if(me.debug_mode >= 1) console.log('%c' + me.request_id + ': REQUEST ERROR ','background: #F2DEDE; color:#B94A48;');
	}
	
	// Messages
	this.app_message = function(data)
	{
		if(me.debug_mode >= 1) console.log('%c' + me.request_id + ': APP MESSAGE ','background: #F2DEDE; color:#B94A48;');
		if(typeof data['app_message'] != 'undefined')
		{
			$.each(data['app_message'], function (key,value)
			{
				/*if(me.debug_mode >= 1)*/ console.log('%c app_message: ' + key + ' : ' + value,'background: #F2DEDE; color:#B94A48;');
				let t1 = swal(value[0],value[1],value[2]);
			});
		}
		else
		{
			console.log('app_message - NOT DEFINED');	
		}
	}

	// Update content elements by id
	this.update_content = function(data)
	{
		$.each(data['update_content'],function (key,value)
		{
			if(me.debug_mode >= 2) console.log('%c Updateing: ' + key,'background: #DFF0D8; color:#468847;');
			// Update inner HTML of element
			$('#' + key).html(value);
		});
	}
	
	// Run callback requests
	this.callback_requests = function(data)
	{
		$.each(data['callback_request'],function (key,value)
		{
			// ***
			if(me.debug_mode >= 2) console.log('%cCALLBACK: ' + key + ' : ' + value ,'background: #DFF0D8; color:#468847;');
			me.ajax_req(key,value);
		});
	}
	
	// Call local JS scripts
	this.call_local_js = function(data)
	{
		// ***
		if(me.debug_mode >= 1) console.log('%c LOCAL JS ' ,'background: #DFF0D8; color:#468847;');
		if('local_js' in data)
		{
			if(me.debug_mode) console.log('Found!');
			$.each(data['local_js'],function (fn_name,value){
				if(me.debug_mode >= 2)  console.log('Calling:' + fn_name + '(' + value + ')');
				try
				{
					// Call function
					window[fn_name](value);
				}
				catch(err)
				{
					// ***
					if(me.debug_mode >= 1) console.log('Function not found:' + fn_name);
				}
			});
		}
		else
		{
			// ***
			if(me.debug_mode >= 1) console.log('No local JS!');
		}
	}
	
	this.form_submit = function()
	{
		$('#loading').show();
		if(me.debug_mode >= 1)  console.log( "Ajax form submit: " + me.form_id + " ..." );
		// BEGIN AJAX
		$.ajax({
		url: 'index.php',
		type: 'post',
		dataType: 'json',
		data: $(me.form_id).serialize(),
		success: function(data) { me.form_done(data); }
		});
		// END AJAX
	}
	
// End of object
}