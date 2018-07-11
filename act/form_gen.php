<?php
/********************************************************************************************************************************/
// Nutshell - administration core class
/********************************************************************************************************************************/
class form_gen
{	
/********************************************************************************************************************************/
// Class constructor
	function __construct()
	{
		// Call parent class constructor
		//parent::__construct();
	}
/********************************************************************************************************************************/
// Generate HTML: list of update forms	
	function do_list($action_data,$form_def)
	{
		$html = '';
		foreach($action_data as $index => $result)
		{
			$form_id = 'resultListItem-' . $index;
			
			$html .= '<div class="section"><span>&#42;</span> Item ID:' . $result['id'] .'</div>';
			$html .= '<div class="inner-wrap">';
					
			$html .= '<form id="' . $form_id . '" data-parsley-trigger="focusin focusout">';
			//echo '<legend>Item: ' . $result['id']  . '</legend>';
			foreach($form_def['inputs'] as $key => $input_def)
			{
				$html .= $this->do_input($key,$input_def,$result);
			} // Row
			
			$html .= '<input type="submit" value="Update">';
			$html .= '</form>';
			
			$html .= '<div style="width:100px;display:inline;"><button id="request_delete" class="local_action_btn action-button" data-idx="' . $result['id'] . '">Delete</button></div>';
			$html .= '</div>';		
		} 
		
		// Return forms HTML
		return $html;
	} 
/********************************************************************************************************************************/
// Generate HTML: input form
	function do_form($form_def)
	{
		$NL = "\n";
		$html = '';

		$html .= '<div class="section"><span>&#42;</span>' . $form_def['form']['label'] . '</div>' . $NL;
		$html .= '<div class="inner-wrap">' . $NL;
				
		$html .= '<form id="' . $form_def['form']['form_id'] . '" action="#"  method="post" data-parsley-trigger="focusin focusout">' . $NL;
		//echo '<legend>Item: ' . $result['id']  . '</legend>';
		foreach($form_def['inputs'] as $key => $input_def)
		{
			$html .= $this->do_input($key,$input_def,false);
		} // Row

		$html .= '<div class="button-section">';
		$html .= '<input type="submit" value="' . $form_def['form']['submit_txt'] . '">' . $NL;
		$html .= '</div>';
		$html .= '</form>' . $NL;
		$html .= '</div>' . $NL;
		
		// Return form HTML
		return $html;
	}
/********************************************************************************************************************************/
// Generate HTML: form input
	function do_input($input_index,$input_def,$input_data)
	{
		$NL = "\n";
		$html = '';
		
		if( $input_def['value_type'] == 'static' )
		{
			$tmp_input = $input_def['value'];
		}
		else
		{
			$tmp_input = $input_data[$input_def['value']];
		}
		
		$html .= ($input_def['type'] != 'hidden') ? '<div class="" >' : '';
		$html .= ($input_def['label'] === false || $input_def['type'] == 'hidden') ? '' : '<label>' . $input_def['label'] . '</label>';
		$html .= '<input type="' . $input_def['type'] . '" name="' . $input_def['name'] . '" value="' . $tmp_input . '" ' . $input_def['data-x'] . ' >';
		$html .= ($input_def['type'] != 'hidden') ? '</div>' : '';
		
		// Retutn form input HTML
		return $html;
	}
/********************************************************************************************************************************/
// END CLASS
/********************************************************************************************************************************/
}
/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>