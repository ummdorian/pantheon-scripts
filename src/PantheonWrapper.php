<?php

class PantheonWrapper{
	
	private $sshUser;
	private $sshUrl;
	
	public function __construct($sshUser, $sshUrl){
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
	
}