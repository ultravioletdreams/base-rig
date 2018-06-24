<?php
//********************************************************************************************************************************
// Postgres DB
//********************************************************************************************************************************
class app_db
{
//********************************************************************************************************************************	
// Constructor
	function __construct()
	{
		// echo 'Constructor!';
		// Postgre SQL 9.4 connection string
		// E.G. "host=localhost port=5432 dbname=logger user=pgadmin password=pgadmin123456"
		$this->db_string = 'host=localhost port=5432 dbname=nutshell-dev user=nutshell-dev password=one-two-alpha-beta';
		$this->error_msg = false;
	}
//********************************************************************************************************************************		
// Execute a DB query 
	function db_query($query_string)
	{
		$db_conn = pg_connect($this->db_string);
		$result = pg_query($db_conn,$query_string);
		
		// Check for error 
		$this->error_msg = pg_last_error($db_conn);
		
		// Package result rows.
		if($result)
		{
			$data = $this->db_rows($result);
		}
		else
		{
			$data = false;
		}
		
		pg_close($db_conn);
		
		return $data;
	}
//********************************************************************************************************************************	
// Process returned rows into php array.
	function db_rows($query_result)
	{
		$num_rows = pg_num_rows($query_result);
		$data = array();
		
		for($row_num=0;$row_num<$num_rows;$row_num++)
		{
			$data[$row_num] = pg_fetch_assoc($query_result);
		}	
		
		return $data;
	}
//********************************************************************************************************************************
// END OF CLASS
//********************************************************************************************************************************
}
//********************************************************************************************************************************
// END OF FILE
//********************************************************************************************************************************
?>