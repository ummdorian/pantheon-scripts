<?php

//@todo use pscp to store fingerprint for all ssh endpoints before commands are run
//@todo figure out why drush fails sometimes

class PantheonWrapper{

	// ssh info
	private $sshUser;
	private $sshUrl;
	// If terminus needs to be bypassed at some point
	private $pantheonApiUrl = 'https://terminus.pantheon.io/api';
	private $siteInfo = array();
	
	
	
	public function __construct(){
	}
	

	public function sshCommand($siteID, $command){

		// create ssh command
		$sshUser = 'dev.'.$siteID;
		$sshUrl =  'appserver.dev.'.$siteID.'.drush.in';
		$command = '.\bin\ssh.exe -T '.$sshUser.'@'.$sshUrl.' -p 2222 -o "AddressFamily inet" '.$command;
		
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
		$results = array();
		foreach($sites as $siteKey => $siteInfo){
			
			$sshUser = 'dev.'.$siteKey;
			$sshUrl =  'appserver.dev.'.$siteKey.'.drush.in';
			
			$command = 'echo y|.\bin\plink.exe -P 2222 -pw "'.$password.'" '.$sshUser.'@'.$sshUrl.'  "drush ups --security-only --format=list"';
			// $commandResponse doesn't get overwritten
			$commandResponse = '';
			// run ssh command
			exec($command,$commandResponse);
			// add site to response
			if(count($commandResponse)){
				$results[$siteInfo->name] = $commandResponse;
			}
			
		}
		return $results;
		
	}

	function getSitesWithModule($moduleName,$password){
	    // get sites
		$sites = $this->getSites();

		//loop over sites
		$results = array();
		foreach($sites as $siteKey => $siteInfo){

			$sshUser = 'live.'.$siteKey;
			$sshUrl =  'appserver.live.'.$siteKey.'.drush.in';

			$command = 'echo y|.\bin\plink.exe -P 2222 -pw "'.$password.'" '.$sshUser.'@'.$sshUrl.'  "drush pm-info '.$moduleName.' --format=json"';
			// $commandResponse doesn't get overwritten
			$commandResponse = '';
			// run ssh command
			exec($command,$commandResponse);

			// add site to response
			if(
			    count($commandResponse)
			    && $commandResponse[0] !=  $moduleName.' was not found.'
			){
			    $info = json_decode(implode($commandResponse,"\n"));
			    if(
			        isset($info->$moduleName)
			        && isset($info->$moduleName->version)
			    ){
				    $results[$siteInfo->name] = $info->$moduleName->version.' ('.$info->$moduleName->status.')';
                }
		    }

		    // if there was no response, or response was malformed add site to unknown status array
		    if(
		        !count($commandResponse)
		        || (
		            $commandResponse[0] !=  $moduleName.' was not found.'
		            && !isset($info->$moduleName)
                )
		    ){
		        $results['_ unable to check _'][] = $siteInfo->name;
            }
        }
        return $results;
    }
	
}