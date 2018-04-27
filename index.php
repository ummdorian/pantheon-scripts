<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

// Determine if script is run from the command line
$isCommandLine = false;
if(count($argv)){
	$isCommandLine = true;
}

if($isCommandLine){

	// Define our commands
	$commandMapping = array(
		'' => 'help',
		'help' => 'help',
		'-help' => 'help',
		'--help' => 'help',
		'ssh' => 'ssh',
		'list' => 'list',
	);
	// Decide which command we're running
	$command = $commandMapping[$argv[1]];

	// Help command
	if($command == 'help'){
		print 'Useage: php index.php ssh "[ssh command]" [ssh user] [ssh url]';
	}
	// SSH command 
	elseif($command == 'ssh'){
		
		$pantheonWrapper = new PantheonWrapper($argv[3],$argv[4]);
		$commandResponse = $pantheonWrapper->sshCommand($argv[2]);

		print_r($commandResponse);
	}
	// List command
	elseif($command == 'list'){
		
		$pantheonWrapper = new PantheonWrapper();
		$sites = $pantheonWrapper->getSites();
		
		print_r($sites);
	}

	
	
}else{
	print 'This is a command line utility intended to be run with the php command.';
}

?>
