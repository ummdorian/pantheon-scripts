<?php

// todo run terminus connection:info site.env on all sites
//connection:info

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
		// define ssh credentials and command
		$sshUser = $argv[3];
		$sshUrl = $argv[4];
		//$command = 'drush ups';
		$remoteCommand = $argv[2];
		// run ssh command
		$fullCommand = 'ssh -T '.$sshUser.'@'.$sshUrl.' -p 2222 -o "AddressFamily inet" '.$remoteCommand;
		exec($fullCommand,$commandResponse);

		print_r($commandResponse);
	}
	// List command
	elseif($command == 'list'){
		
		// Get list
		$fullCommand = 'terminus site:list';
		exec($fullCommand,$commandResponse);
		
		// Parse list
		$sites = array();
		for($i=3; $i<count($commandResponse); $i++){
			$siteInfo = explode(' ',$commandResponse[$i]);
			//trim up the info
			foreach($siteInfo as $columnIndex => $info){
				$siteInfo[$columnIndex] = trim($info);
			}
			$sites[] = $siteInfo;
		}
		
		print_r($sites);
	}

	
	
}

?>
