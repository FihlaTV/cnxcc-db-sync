<?php
require_once 'XMLRPCClient.class.php';
require_once 'Database.class.php';
require_once 'CheckClients.php';

class ActiveClients
{	
	const QUERY_STRING = 'cnxcc.active_clients';  
	private $url;
	private $db;
	private $dbParams;
	private $hasChanged;
	
	public function __construct($url, $dbParams)
	{
		$this->url		= $url;
		$this->dbParams	= $dbParams;
		$this->db		= new Database($dbParams['host'], $dbParams['user'], 
										$dbParams['password'], $dbParams['database']);
	}
	
	public function retrieve()
	{
		if (!$this->db)
			throw new Exception("No database connection");
		
		$rpcClient	= new XMLRPCClient($this->url, true);
		$resp 		= $rpcClient->call(self::QUERY_STRING, array());
				
		if ($resp == '')
		{
			$this->dropAll();
			return;
		}
		
		if (is_array($resp) && array_key_exists('faultCode', $resp))
			throw new Exception("Error on RPC request {$resp['faultString']}");
		
		$rows 			= explode(';', $resp);
		$cliendIDArray	= array();		
		
		foreach($rows as $row)
		{
			if ($row == '')
				break;
		
			$fields	= explode(',', $row);
			$kvp	= array();
			
			foreach($fields as $field)
			{
				list($label, $value) = explode(':', $field);				
				$kvp[$label]	= $value;
			}			
			
			$this->register($kvp);
			
			array_push($cliendIDArray, "'{$kvp['client_id']}'");	
			
			$checkClient	= new CheckClients($this->url, $this->dbParams);
			$checkClient->retrieve($kvp['client_id']);
		}
		
		$this->dropAbsent($cliendIDArray);
	}
	
	private function dropAll()
	{
		$sql = "DELETE FROM credit_data";
		$this->db->query($sql);
	}
	
	private function dropAbsent($clientIDArray)
	{
		$clients = implode(',', $clientIDArray);
		
		$sql = "DELETE FROM credit_data WHERE client_id NOT IN ($clients)";
		
		$this->db->query($sql);
	}
	
	private function register($kvp)
	{
		$numberOfCalls = 0;

		if (!$this->existsInDatabase($kvp, &$numberOfCalls))
		{
			$this->insert($kvp);
			return;
		}
		
		$this->hasChanged = ($kvp['number_of_calls'] != $numberOfCalls);
		
		$this->update($kvp);
	}
	
	private function update($kvp)
	{
		$sql		= "UPDATE credit_data SET max_amount = '{$kvp['max_amount']}', consumed_amount = '{$kvp['consumed_amount']}', 
											number_of_calls = '{$kvp['number_of_calls']}', concurrent_calls = '{$kvp['concurrent_calls']}',
											credit_type_id = '{$kvp['type']}'
											WHERE client_id = '{$kvp['client_id']}'";
		
		$results	= $this->db->query($sql);		
	}
	
	private function insert($kvp)
	{
		$sql		= "INSERT INTO credit_data VALUES (NULL, '{$kvp['max_amount']}', '{$kvp['consumed_amount']}',
									 '{$kvp['number_of_calls']}', '{$kvp['concurrent_calls']}',
									 '{$kvp['type']}', '{$kvp['client_id']}')";
		
		$results	= $this->db->query($sql);
		
	}
	
	public function hasChanged()
	{
		return $this->hasChanged;
	}
	
	private function existsInDatabase($kvp, $numberOfCalls)
	{
		$sql		= "SELECT number_of_calls FROM credit_data WHERE client_id = '{$kvp['client_id']}'";
		$results	= $this->db->query($sql);
		
		if (mysql_num_rows($results) == 0)
			return false;
		
		$row		= mysql_fetch_assoc($results);
		
		$numberOfCalls = $row['number_of_calls'];
		
		return true;
	}
}