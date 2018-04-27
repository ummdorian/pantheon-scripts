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
		$command = 'ssh -T '.$this->sshUser.'@'.$this->sshUrl.' -p 2222 -o "AddressFamily inet" '.$command;
		
		// run ssh command
		exec($command,$commandResponse);
		
		// return response
		return $commandResponse;
		
	}
	
	
	public function getSites(){
		
		// Get site machine names
		$fullCommand = 'terminus site:list --format=json';
		exec($fullCommand,$commandResponse);
		// Turn response into array
		$sitesJson = implode($commandResponse,"\n");
		$sites = json_decode($sitesJson);
		
		return $sites;
		
	}
	
	
	public function getUpdates(){
		
		// get sites
		$sites = $this->getSites();
		
		//loop over sites
		foreach($sites as $site){
			
		}
		
	}
	
}