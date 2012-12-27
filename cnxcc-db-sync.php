<?php

require_once 'dbconfig.include.php';
require_once 'XMLRPCClient.class.php';
require_once 'ActiveClient.class.php';

function main()
{
	global $argv, $mysql_params;
	
	$serverIP	= $argv[1];
	$serverPort	= $argv[2];
	$url		= "http://$serverIP:$serverPort";
	
	$clients	= new ActiveClients($url, $mysql_params);
	
	$clients->retrieve();
}

main();