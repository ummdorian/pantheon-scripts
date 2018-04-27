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
		'updates' => 'updates',
	);
	// Decide which command we're running
	$command = $commandMapping[$argv[1]];

	// Help command
	if($command == 'help'){
		print 'php index.php ssh [site id] "[ssh command]"'."\n";
		print 'php index.php list'."\n";
		print 'php index.php updates [dashboard password]'."\n";
	}
	// SSH command 
	elseif($command == 'ssh'){
		
		$pantheonWrapper = new PantheonWrapper();
		$commandResponse = $pantheonWrapper->sshCommand($argv[2],$argv[3]);

		print_r($commandResponse);
	}
	// List command
	elseif($command == 'list'){
		
		$pantheonWrapper = new PantheonWrapper();
		$sites = $pantheonWrapper->getSites();
		
		print_r($sites);
	}
	// Get Updates
	elseif($command == 'updates'){
		
		$pantheonWrapper = new PantheonWrapper();
		$updates = $pantheonWrapper->getUpdates($argv[2]);
		
		print_r($updates);
	}

	
	
}else{
	print 'This is a command line utility intended to be run with the php command.';
}

?>
