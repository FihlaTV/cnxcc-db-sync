<?php

class Database
{
	private $dbh;	
	
	public function __construct($host, $user, $password, $db) 
	{ 
		$this->connect($host, $user, $password, $db);
	}

	public function connect($host, $user, $password, $db)
	{
		$this->dbh	= mysql_connect($host, $user, $password);
		
		if (!$this->dbh)
			throw new Exception("Error connecting to database");
	
		if (!mysql_select_db($db))
			throw new Exception("Error selecting database");		
	}
	
	public function query($sql)
	{
		$result	= mysql_query($sql);
		
		if (!$result)
			throw new Exception("Error executing query: '$sql' ". mysql_error());
		
		return $result;
	}
	
}

