<?php
//********************************************************************************************************************************
// Queries for test DB
//********************************************************************************************************************************
class app_test_db extends app_db
{
//********************************************************************************************************************************	
// Constructor
	function __construct()
	{
		// Call parent constructor
		parent::__construct();
		// Set table name that queries will operate on
		$this->table_name = 'app_obj_types';
	}
//********************************************************************************************************************************		
// Create entry into test table
	function create_test_entry($data,$server_stamp)
	{	
		$query_string = "INSERT INTO test "
		              . "(data,teststamp) "
                      . "VALUES ("
					  . "'$data',"
					  . "'$server_stamp'"
					  . ") RETURNING id;";

		$result = $this->db_query($query_string);
		return $result[0]['id'];
	}
//********************************************************************************************************************************		
// Create entry into MASTER: app_obj_types
	function create_master_entry($type_id,$type_name)
	{	
		$query_string = "INSERT INTO app_obj_types "
		              . "(type_id,type_name) "
                      . "VALUES ("
					  . "'$type_id',"
					  . "'$type_name'"
					  . ") RETURNING id;";

		$result = $this->db_query($query_string);
		return $result[0]['id'];
	}
	
//********************************************************************************************************************************		
// reset_entries
	function reset_entries()
	{	
		$query_string = "TRUNCATE TABLE test RESTART IDENTITY;";

		$result = $this->db_query($query_string);
		return $result;
		
	}
//********************************************************************************************************************************		
// SELECT - Select all entries in test table
	function select_all($sensor_table_name='app_obj_types')
	{
		$query_string = "SELECT * FROM $this->table_name ORDER BY type_name DESC LIMIT 100";
		return $this->db_query($query_string);
	}
//********************************************************************************************************************************		
// UPDATE - Update single entry identified by id
	function update_entry($entry_values)
	{
		$query_string = "UPDATE $this->table_name SET type_id = '" . $entry_values[1]  . "', type_name = '" . $entry_values[2] . "' WHERE id = '" . $entry_values[0] . "' RETURNING id;";
		return $this->db_query($query_string);
	}
//********************************************************************************************************************************		
// DELETE - Delete single entry identified by id
	function delete_entry($entry_id)
	{
		$query_string = "DELETE FROM $this->table_name WHERE id = '" . $entry_id . "' RETURNING id;";
		return $this->db_query($query_string);
	}
//********************************************************************************************************************************
// END OF CLASS
//********************************************************************************************************************************
}
//********************************************************************************************************************************
// END OF FILE
//********************************************************************************************************************************
?>