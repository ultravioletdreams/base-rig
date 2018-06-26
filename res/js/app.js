// Nutshell request object

var debug_mode = false;

function x_app()
{	
	var me = this;
	
	this.debug_mode = false;
	
	this.request_id = false;
	this.form_id = false;
	this.reset_form = false;
	
	// Set base request URL
	this.request_url = 'index.php';
	
	// GET JSON - Get application layout from simple app controller 
	this.req = function(action)
	{
		me.request_id = action;
		$('#loading').show();
		g_post = $.getJSON("index.php?ax=" + action,'');
		g_post.done(me.req_done);
		g_post.fail(me.req_fail);
	}
	
	// POST JSON 
	this.ajax_req = function(action,action_params)
	{
		me.request_id = action;
		$('#loading').show();
		$.ajax({
			type: 'POST',
			url: me.request_url + '?ax=' + action,
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
		$('#loading').hide();
		// Update page contents from response
		if(me.debug_mode) console.log('REQ DONE: Response recieved. ' + me.request_id);
		
		// Call response handlers
		if(me.debug_mode) console.log('REQ DONE: Calling response handlers.');
		me.update_content(data);
		
		// Call local JS functions specified in response
		if(me.debug_mode) console.log('REQ DONE: Calling local JS.');
		me.call_local_js(data);
		
		// Running callback requests
		if(me.debug_mode) console.log('REQ DONE: Sending callback requests.');
		me.callback_requests(data);
	}
	
	// Form submit success handler
	this.form_done = function(data)
	{
		if(me.debug_mode) console.log('FORM DONE:' + me.form_id);
		if(me.reset_form) $(me.form_id)[0].reset();
		// Pass response data onto normal request success handler
		me.req_done(data);
	}

	// Handle failure off async call to update application view
	this.req_fail = function (data)
	{
		$('#loading').hide();
		if(me.debug_mode) console.log("Post Fail:" +  data.status + ":" + data.statusText);
	}

	// Update content elements by id
	this.update_content = function(data)
	{
		if(me.debug_mode) console.log('Response handler: Update Content');
		// Update targets with content data
		//if(data['target_data'].length <= 0) console.log('No target_data.');
		$.each(data['target_data'],function (key,value)
		{
			if(me.debug_mode) console.log('Updating: ' + key);
			// Update inner HTML of element
			$('#' + key).html(value);
		});
		if(me.debug_mode) console.log('Done.');
	}
	
	// Run callback requests
	this.callback_requests = function(data)
	{
		if(me.debug_mode) console.log('CALLBACKS: Running.');
		$.each(data['callback_requests'],function (key,value)
		{
			console.log('CALLBACKS: ' + key + ' : ' + value );
			me.ajax_req(key,value);
		});
	}
	
	// Call local JS scripts
	this.call_local_js = function(data)
	{
		if(me.debug_mode) console.log('Response handler: Local JS');
		if('call_local_js' in data)
		{
			if(me.debug_mode) console.log('Found!');
			$.each(data['call_local_js'],function (fn_name,value){
				if(me.debug_mode) console.log('Calling:' + fn_name + '(' + value + ')');
				try
				{
					// Call function
					window[fn_name](value);
				}
				catch(err)
				{
					if(me.debug_mode) console.log('Function not found:' + fn_name);
				}
			});
		}
		else
		{
			if(me.debug_mode) console.log('No local JS!');
		}
	}
	
	this.form_submit = function()
	{
		$('#loading').show();
		if(me.debug_mode) console.log( "Ajax form submit: " + me.form_id + " ..." );
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