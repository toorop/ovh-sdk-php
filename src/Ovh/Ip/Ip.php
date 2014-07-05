<?php
/**
 * Copyright 2013 Stéphane Depierrepont (aka Toorop)
 *
 * Authors :
 *  - Stéphane Depierrepont (aka Toorop)
 *  - Florian Jensen (aka flosoft) : https://github.com/flosoft
 *  - Gillardeau Thibaut (aka Thibautg16) 
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://www.apache.org/licenses/LICENSE-2.0.txt
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

// cloned from VPS // Slartibardfast / 2014-06-30

namespace Ovh\Ip;

use Ovh\Common\Exception\NotImplementedYetException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;
use Ovh\Common\Ovh;
use Ovh\Ip\IpClient;
use Ovh\Common\Task;

/* 
 *
 * Class  for the /ip/.. heirarchy
*/
class Ip
{
	private $domain = null;
	private static $IPClient = null;

	/**
	 * @param string $domain
	 */
	public function __construct($domain)
	{
		$this->setIP($domain);
	}


	/**
	 * Return IP client
	 *
	 * @return null|IPClient
	 */
	private static function getClient()
	{
		if (!self::$IPClient instanceof IPClient){
			self::$IPClient=new IPClient();
		};
		return self::$IPClient;
	}

	/**
	 * Set domain
	 *
	 * @param string $domain
	 */
	public function setIP($domain)
	{
		$this->domain = $domain;
	}

	/**
	 * Get domain
	 *
	 * @return null | string domain
	 */
	public function getIP()
	{
		return $this->domain;
	}

	###
	# Mains methods from OVH Rest API
	##

	/**
	 *  Get ipblock properties
	 *
	 *  @return object
	 *
	*/
	public function getIPBlockProperties(){
        return json_decode(self::getClient()->getIPBlockProperties($this->getIP()));
    }
	    
	/**
	 *  set ipblock properties (only description allowed at this time)
	 *
	 *  @return NULL ??
	 *
	*/
	public function setIPBlockProperties($description) {
		return json_decode(self::getClient()->setIPBlockProperties($this->getIP(),$description));
	}
	
	
	/**
	 *  get ips in the current block which are blocked (for spam, etc...)
	 *
	 *  @return list of IPv4
	 *
	*/
	public function getIPBlockArp() {
		return json_decode(self::getClient()->getIPBlockArp($this->getIP()));
	}
	
	/*
	 * get details about the specific IP that is blocked
	 *
	 * return mixed detail 
	*/
	public function getIPBlockedInfo($domain) {
		return json_decode(self::getClient()->getIPBlockArp($this->getIP(),$domain));
	}

	/*
	 * get ReverseIP
	 *
	 * return mixed detail 
	*/
	public function getReverse() {
		return json_decode(self::getClient()->getReverse($this->getIP()));
	}

	/*
	 * get details about the ReverseIP
	 *
	 * return mixed detail 
	*/
	public function getReverseProperties($ipv4) {
		return json_decode(self::getClient()->getReverseProperties($this->getIP(),$ipv4));
	}

	/*
	 * get details about the ReverseIP
	 *
	 * return mixed detail 
	*/
	public function setReverseProperties($ipv4,$reverse) {
		return json_decode(self::getClient()->setReverseProperties($this->getIP(),$ipv4,$reverse));
	}

	/*
	 * get IPs in block on SPAM
	 *
	 * return array IPv4 
	*/
	public function getSpam($spamstate="blockedForSpam") {
		return json_decode(self::getClient()->getSpam($this->getIP(),$spamstate));
	}
	
	/*
	 * get properties of IP on SPAM
	 *
	 * return mixed
	*/
	public function getSpamProperties($ipv4) {
		return json_decode(self::getClient()->getSpamProperties($this->getIP(),$ipv4));
	}
	
	/*
	 * get stats of IP on SPAM
	 *
	 * return mixed
	*/
	public function getSpamStats($ipv4, $fromdate="2000-01-01 00:00:00", $todate="") {
		if ($todate=="") $todate=date("Y-m-d 23:59:59",time());
		return json_decode(self::getClient()->getSpamStats($this->getIP(),$ipv4, $fromdate, $todate));
	}
	
	/*
	 * get stats of IP on SPAM
	 *
	 * return mixed
	*/
	public function setUnblockSpam($ipv4) {
		return json_decode(self::getClient()->setUnblockSpam($this->getIP(),$ipv4));
	}
	
	
}