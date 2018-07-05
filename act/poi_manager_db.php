<?php
//********************************************************************************************************************************
// Queries for test DB
//********************************************************************************************************************************
class poi_manager_db extends app_db
{
//********************************************************************************************************************************	
// Constructor
	function __construct()
	{
		// DB connection string
		$db_string = 'host=localhost port=5432 dbname=poi_manager user=poi_user password=pretty-please-no-thanks';
		// Call parent constructor with db connect string
		parent::__construct($db_string);
		
		// Set table name that queries will operate on
		$this->table_name = 'poi';
	}
//********************************************************************************************************************************		
// Create entry into MASTER: app_obj_types
	function insert_item($lat,$lon)
	{	
		$query_string = "INSERT INTO poi "
		              . "(lat,lon) "
                      . "VALUES ("
					  . "'$lat',"
					  . "'$lon'"
					  . ") RETURNING id;";

		$result = $this->db_query($query_string);
		return $result[0]['id'];
	}
	
//********************************************************************************************************************************		
// reset_entries
	function reset_entries()
	{	
		$query_string = "TRUNCATE TABLE poi RESTART IDENTITY;";

		$result = $this->db_query($query_string);
		return $result;
		
	}
//********************************************************************************************************************************		
// SELECT - Select all entries in test table
	function select_all($sort_order=0,$sort_by=0)
	{		
		$query_string = "SELECT * FROM poi ORDER BY point_id ASC;";
		return $this->db_query($query_string);
	}
//********************************************************************************************************************************		
// UPDATE - Update single entry identified by id
	function update_entry($entry_values)
	{
		$query_string = "UPDATE poi SET lat = '" . $entry_values[1]  . "', lon = '" . $entry_values[2] . "' WHERE point_id = '" . $entry_values[0] . "' RETURNING point_id;";
		return $this->db_query($query_string);
	}
//********************************************************************************************************************************		
// DELETE - Delete single entry identified by id
	function delete_entry($entry_id)
	{
		$query_string = "DELETE FROM poi WHERE point_id = '" . $entry_id . "' RETURNING point_id;";
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