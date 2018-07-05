/*--------------------------------------------------------------------------------------------------------------------------------*/
// App specific functions: Master Tables 
/*--------------------------------------------------------------------------------------------------------------------------------*/
function request_delete_2(action_params)
{
	request_delete(action_params)
}
function request_delete(action_params)
{
	console.log(action_params);
	//var item_id = "1001";

var options = { dangerMode: true, buttons: { cancels: { text: "Cancel", value: "no"}, confirm: { text: "Yes", value: "yes"} } };
	
	var promised_result = swal("Are you sure you want to delete item: " + action_params['idx'] + " ?",options);//.then((value) => {console.log('VALUE: ' + value)});
	
	promised_result.then( (value) => 
	{
		console.log('VALUE2: ' + value);
		if(value == 'yes')
		{
			//var action_params = '';// $(this).data();
			send_action('delete_item',action_params);
		}
	});
	
}