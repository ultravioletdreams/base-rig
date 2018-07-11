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
	this.action_handler = false;
	
	// Request parameter name - the POST/GET variable name that the action handler id will be looked for server side.
	this.action_handler_param_id = 'appx';
	
	// Request parameter name - the POST/GET variable name that the action id will be looked for server side.
	this.action_param_id = 'ax';
	
	// Set base request URL where requests will be sent.
	this.request_url = 'index.php';
	
	// Send an action request and handle the response.
	this.ajax_req = function(action,action_params,overide_action_handler)
	{
		// ***
		if(me.debug_mode >= 1) console.log('%c SEND REQUEST: ' + action,'background: #DFF0D8; color:#468847;');
		
		// Build request URL
		var request_url = me.request_url + '?' + me.action_param_id + '=' + action;
		
		// *** Alter request destination action handler
		//if(typeof action_handler !== 'undefined')
		if(typeof overide_action_handler !== 'undefined' && overide_action_handler !== false)
		{
			// Append action handler to request
			request_url += '&appx=' + overide_action_handler;
		}
		else
		{
			request_url += '&appx=' + me.action_handler;
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
		
		// Application dialog
		me.app_dialog(data);
		
		// Update content handler
		me.update_content(data);
		
		// Call local JS functions specified in response
		me.call_local_js(data);
		
		// Running callback requests
		me.callback_requests(data);
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
		//
		var p1 = Promise.resolve(true);
		if(typeof data['app_message'] != 'undefined')
		{
			me.app_messages = data['app_message'];
			me.message_index = 0;
			console.log('# MSGS:' + Object.keys(me.app_messages).length);

			for(i=0;i<Object.keys(me.app_messages).length;i++)
			{
				p1 = p1.then((value) => { return me.show_msg(value); });
			}
		}
	}
	
	// App dialogue
	this.app_dialog = function(data)
	{
		if(typeof data['app_dialog'] != 'undefined')
		{
			// Get dialog params from response
			var title    = data['app_dialog']['title'];
			var message  = data['app_dialog']['message'];
			var options  = data['app_dialog']['options'];
			var callback = data['app_dialog']['callback'];
			var type     = (typeof data['app_dialog']['type'] != 'undefined') ? data['app_dialog']['type'] : '';
			
			// Swet Alert and send resulting value remote using callback action
			swal(title,message,type,options).then((value) => { 
				console.log(value); 
				var payload = {};
				payload['result'] = value;
				me.ajax_req(callback,payload);
			});;
		}
	}
	
	// Show message
	this.show_msg = function(value)
	{
		var return_var = false;

		if(value === true)
		{
			console.log('COUNT:' + me.message_index);
			console.log(me.app_messages[me.message_index][0]);
			return_var =  swal(me.app_messages[me.message_index][0],me.app_messages[me.message_index][1],me.app_messages[me.message_index][2]);
			me.message_index += 1;
		}
		else
		{
			return_var = false;
		}
	
		return return_var;
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