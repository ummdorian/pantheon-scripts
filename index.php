<?php

// todo run terminus connection:info site.env on all sites
//connection:info

// define ssh credentials and command
$sshUser = '';
$sshUrl = '';
$command = 'drush ups';
// run ssh command
exec('ssh -T '.$sshUser.'@'.$sshUrl.' -p 2222 -o "AddressFamily inet" '.$command, $commandResponse);

print_r($commandResponse);

?>
