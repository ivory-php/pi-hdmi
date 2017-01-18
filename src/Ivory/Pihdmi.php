<?php 

namespace Ivory;

class Pihdmi {

	/**
	 * Checks to see if the CEC Client is installed on the machine
	 * @return boolean [description]
	 */
	public function isCecClientInstalled()
	{
		$output = system('cec-client -h');

		// If the package isn't instaled the response will be '-bash: cec-client: command not found'
		// So we'll do a quick check to see if part of that string exists. If it does 
		// then CEC Client is not installed
		if( strstr( $output, "-bash:") ) return false;

		return true;
	}

	/**
	 * Installs the CEC-Client. NOTE: Must have sudo access
	 */
	public function installCecClient()
	{
		if( $this->isCecClientInstalled() ) return;

		echo "Installing cec-utils...\n";
		system('sudo apt-get install -y cec-utils');

		if( $this->isCecClientInstalled() ) {
			echo "cec-utils Installed Succesfully!\n";
			return;
		}

		echo "There was a problem installing CEC-utils, please try again.\n";
		return;
	}

	/**
	 * Determine the Status of the TV 
	 * @return string 'on', 'off', 'unknown' (for those that are supported)
	 */
	public function powerStatus()
	{
		$response = system("echo pow 0 | /usr/local/bin/cec-client -s -d 1 | grep 'power status:'");
		$response = explode(":", $response);

		return trim($response[1]);
	}

	/**
	 * Send a "Turn On" Signal to the device
	 *
	 * The command takes 2-3 seconds to execute. $sync allows the script to halt for that 
	 * time and wait for the command to complete before continuing. async (false) allows the 
	 * script to continue while the task is perfomring in the background. 
	 * 
	 * @param booelan $sync Perform the command syncrounously
	 * @return void
	 */
	public function on($sync = true)
	{
		if( $sync )	system('echo on 0 | /usr/local/bin/cec-client -s -d 1');
		else system('echo on 0 | /usr/local/bin/cec-client -s -d 1 > /dev/null 2>&1');
	}

	/**
	 * Send a "Turn Off" Signal to the device
	 *
	 * The command takes 2-3 seconds to execute. $sync allows the script to halt for that 
	 * time and wait for the command to complete before continuing. async (false) allows the 
	 * script to continue while the task is perfomring in the background. 
	 * 
	 * @param booelan $sync Perform the command syncrounously
	 * @return void
	 */
	public function off($sync = true)
	{
		if( $sync )	system('echo standby 0 | /usr/local/bin/cec-client -s -d 1');
		else system('echo standby 0 | /usr/local/bin/cec-client -s -d 1 > /dev/null 2>&1');
	}


}
