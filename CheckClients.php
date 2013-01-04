<?php
require_once 'XMLRPCClient.class.php';
require_once 'Database.class.php';

class CheckClients
{	
	const QUERY_STRING = 'cnxcc.check_client';
	  
	private $url;
	private $db;
	private $hasChanged;
	private $clientID;
	
	public function __construct($url, $dbParams)
	{
		$this->url	= $url;
		$this->db	= new Database($dbParams['host'], $dbParams['user'], 
									$dbParams['password'], $dbParams['database']);
	}
	
	public function retrieve($clientID)
	{
		if (!$this->db)
			throw new Exception("No database connection");
		
		$this->clientID	= $clientID;
		$rpcClient		= new XMLRPCClient($this->url, true);
		$resp 			= $rpcClient->call(self::QUERY_STRING, array($clientID));
		
		if ($resp == '')
		{
			$this->dropAll();
			return;
		}
		
		if (is_array($resp) && array_key_exists('faultCode', $resp))
			throw new Exception("Error on RPC request: {$resp['faultString']}");
		
		$rows 			= explode(';', $resp);
		
		$callIDArray	= array();
		
		print_r($rows);
		
		
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
			
			array_push($callIDArray, "'{$kvp['call_id']}'");	
		}
		
		$this->dropAbsent($callIDArray);
	}
	
	private function dropAll()
	{
		$sql = "DELETE FROM `call` WHERE client_id = '{$this->clientID}'";
		$this->db->query($sql);
	}
	
	private function dropAbsent($callIDArray)
	{
		$callIDs = implode(',', $callIDArray);
		
		$sql = "DELETE FROM `call` WHERE call_id NOT IN ($callIDs)";
		
		$this->db->query($sql);
	}
	
	private function register($kvp)
	{
		if (!$this->existsInDatabase($kvp))
		{
			$this->insert($kvp);
			return;
		}				
		
		$this->update($kvp);
	}
	
	private function update($kvp)
	{
		$sql		= "UPDATE `call` SET confirmed = '{$kvp['confirmed']}', max_amount = '{$kvp['local_max_amount']}',
									 consumed_amount = '{$kvp['local_consumed_amount']}', start_timestamp = FROM_UNIXTIME({$kvp['start_timestamp']})
									 WHERE call_id = '{$kvp['call_id']}'";
		
		$results	= $this->db->query($sql);		
	}
	
	private function insert($kvp)
	{
		$sql		= "INSERT INTO `call` VALUES ('{$kvp['call_id']}', '{$kvp['confirmed']}', '{$kvp['local_max_amount']}',
									 '{$kvp['local_consumed_amount']}', FROM_UNIXTIME({$kvp['start_timestamp']}),
									 '{$this->clientID}')";
		
		$results	= $this->db->query($sql);
		
	}
	
	private function existsInDatabase($kvp)
	{
		$sql		= "SELECT * FROM `call` WHERE call_id = '{$kvp['call_id']}'";
		$results	= $this->db->query($sql);
		
		return mysql_num_rows($results) > 0;		
	}
}
