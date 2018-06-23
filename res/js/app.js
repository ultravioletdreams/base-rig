// Nutshell request object

var debug_mode = false;

function x_app()
{	
	var me = this;
	
	this.request_id = false;
	
	// Set base request URL
	this.request_url = 'index.php';
	
	// Get application layout from simple app controller 
	this.req = function(action)
	{
		me.request_id = action;
		$('#loading').show();
		g_post = $.getJSON("index.php?ax=" + action,'');
		g_post.done(me.req_done);
		g_post.fail(me.req_fail);
	}
	
	this.ajax_req = function(action,action_params)
	{
		me.request_id = action;
		$('#loading').show();
		$.ajax({
			type: 'POST',
			url: me.request_url + '?ax=' + action,
			//url: me.request_url,
			//data: '{"name":"jonas","idx":"' + action + '"}', // or JSON.stringify ({name: 'jonas'}),
			data: JSON.stringify(action_params), // or JSON.stringify ({name: 'jonas'}),
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
		if(debug_mode) console.log('Response recieved. ' + me.request_id);
		
		// Call response handlers
		if(debug_mode) console.log('Calling response handlers.');
		me.update_content(data);
		// Call local JS functions specified in response
		me.call_local_js(data);
	}

	// Handle failure off async call to update application view
	this.req_fail = function (data)
	{
		$('#loading').hide();
		if(debug_mode) console.log("Post Fail:" +  data.status + ":" + data.statusText);
	}

	// Update content elements by id
	this.update_content = function(data)
	{
		if(debug_mode) console.log('Response handler: Update Content');
		// Update targets with content data
		//if(data['target_data'].length <= 0) console.log('No target_data.');
		$.each(data['target_data'],function (key,value)
		{
			if(debug_mode) console.log('Updating: ' + key);
			// Update inner HTML of element
			$('#' + key).html(value);
		});
		if(debug_mode) console.log('Done.');
	}
	
	this.call_local_js = function(data)
	{
		if(debug_mode) console.log('Response handler: Local JS');
		if('call_local_js' in data)
		{
			if(debug_mode) console.log('Found!');
			$.each(data['call_local_js'],function (fn_name,value){
				if(debug_mode) console.log('Calling:' + fn_name);
				try
				{
					// Call function
					window[fn_name](value);
				}
				catch(err)
				{
					if(debug_mode) console.log('Function not found:' + fn_name);
				}
			});
		}
		else
		{
			if(debug_mode) console.log('No local JS!');
		}
	}
	
	this.form_submit = function(form_id)
	{
		$('#loading').show();
		if(debug_mode) console.log( "Ajax form submit: " + form_id + " ..." );
		// BEGIN AJAX
		$.ajax({
		url: 'index.php',
		type: 'post',
		dataType: 'json',
		data: $(form_id).serialize(),
		success: function(data) { me.req_done(data); }
		});
		// END AJAX
	}
	
// End of object
}