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
// List all entries in test table
	function list_all($sensor_table_name='app_obj_types')
	{
		$query_string = "SELECT * FROM $sensor_table_name ORDER BY type_name DESC LIMIT 100";
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