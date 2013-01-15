<?php

require_once 'dbconfig.include.php';
require_once 'XMLRPCClient.class.php';
require_once 'ActiveClient.class.php';

function main()
{
	global $argv, $mysql_params;

	if (count($argv) != 3)
		show_usage();
		
	$serverIP	= $argv[1];
	$serverPort	= $argv[2];
	$url		= "http://$serverIP:$serverPort";
	
	$clients	= new ActiveClients($url, $mysql_params);
	
	$clients->retrieve();
}

function show_usage()
{
	die("Invalid parameters:\n\tphp cnxcc-db-sync.php <sip-server-ip> <sip-server-xmlrpc-port>\n");
}

main();
