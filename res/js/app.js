// Nutshell request object
function x_app()
{	
	var me = this;
	
	// Set base request URL
	this.request_url = 'index.php';
	
	// Get application layout from simple app controller 
	this.req = function(action)
	{
		g_post = $.getJSON("index.php?ax=" + action,"");
		g_post.done(me.req_done);
		g_post.fail(me.req_fail);
	}

	// Update base template with application view conatiners and data
	this.req_done = function(data)
	{
		// Update page contents from response
		console.log('REQUEST DONE.');
		me.update_content(data);
		// Call local JS functions specified in response
		me.call_local_js(data);
	}

	// Handle failure off async call to update application view
	this.req_fail = function (data)
	{
		console.log("Post Fail:" +  data.status + ":" + data.statusText);
	}

	// Update content elements by id
	this.update_content = function(data)
	{
		console.log('UPDATE CONTENT:' + data);
		// Update targets with content data
		//if(data['target_data'].length <= 0) console.log('No target_data.');
		$.each(data['target_data'],function (key,value){
			console.log('TARGET ELEMENT:' + key);
			// Warn if target element id is not found
			console.log('TARGET:' + key + ' : ' + $('#' + key).length );
			if($('#' + key).length ) { console.log('TARGET:' + key + ' : ' + $('#' + key).length ); }
			// Update inner HTML of element
			$('#' + key).html(value);
		});
		console.log('UPDATE CONTENT DONE.');
	}
	
	this.call_local_js = function(data)
	{
		console.log('CHECK FOR LOCAL_JS?');
		if('call_local_js' in data)
		{
			console.log('Found!');
			$.each(data['call_local_js'],function (fn_name,value){
				console.log('Calling:' + fn_name);
				try
				{
					// Call function
					window[fn_name]();
				}
				catch(err)
				{
					console.log('Function not found:' + fn_name);
				}
			});
		}
		else
		{
			console.log('No local JS!');
		}
	}
	
	this.form_submit = function(form_id)
	{
		console.log( "Ajax form submit: " + form_id + " ..." );
		// BEGIN AJAX
		$.ajax({
		url: 'index.php',
		type: 'post',
		dataType: 'json',
		data: $(form_id).serialize(),
		success: function(data) { me.update_content(data); }
		});
		// END AJAX
	}
	
// End of object
}