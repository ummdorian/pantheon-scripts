<?php

class PantheonWrapper{

	// ssh info
	private $sshUser;
	private $sshUrl;
	// If terminus needs to be bypassed at some point
	private $pantheonApiUrl = 'https://terminus.pantheon.io/api';
	private $siteInfo = array();
	
	
	
	public function __construct($sshUser = '', $sshUrl = ''){
		$this->sshUser = $sshUser;
		$this->sshUrl = $sshUrl;
	}
	
	
	public function sshCommand($command){

		// create ssh command
		$command = '.\bin\ssh.exe -T '.$this->sshUser.'@'.$this->sshUrl.' -p 2222 -o "AddressFamily inet" '.$command;
		
		// run ssh command
		exec($command,$commandResponse);
		
		// return response
		return $commandResponse;
		
	}
	
	
	public function getSites(){
		
		// Get site machine names
		$command = 'terminus site:list --format=json';
		exec($command,$commandResponse);
		// Turn response into array
		$sitesJson = implode($commandResponse,"\n");
		$sites = json_decode($sitesJson);
		
		return $sites;
		
	}
	
	
	public function getUpdates($password){
		
		// get sites
		$sites = $this->getSites();
		
		//loop over sites
		$securityUpdates = array();
		foreach($sites as $siteKey => $siteInfo){
			
			$sshUser = 'dev.'.$siteKey;
			$sshUrl =  'appserver.dev.'.$siteKey.'.drush.in';
			
			$command = 'echo y|.\bin\plink.exe -P 2222 -pw "'.$password.'" '.$sshUser.'@'.$sshUrl.'  "drush ups --security-only --format=list"';
			// $commandResponse doesn't get overwritten
			$commandResponse = '';
			// run ssh command
			exec($command,$commandResponse);
			
			if(count($commandResponse)){
				$securityUpdates[$siteInfo->name] = $commandResponse;
			}
			
		}
		return $securityUpdates;
		
	}
	
}