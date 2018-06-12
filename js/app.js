// Nutshell request object
function x_app()
{	
	var me = this;
	// Get application layout from simple app controller 
	this.req = function(action)
	{
		g_post = $.getJSON("index.php?async=1&ax=" + action,"");
		g_post.done(me.req_done);
		g_post.fail(me.req_fail);
	}

	// Update base template with application view conatiners and data
	this.req_done = function(data)
	{
		// Update page contents from response
		console.log('REQUEST DONE.');
		me.update_content(data);
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
	
// End of object
}