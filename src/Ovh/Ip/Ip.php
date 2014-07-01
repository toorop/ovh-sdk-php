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
	 * Return Vrack client
	 *
	 * @return null|vrackClient
	 */
	private static function getClient()
	{
	//echo "called Ip->getClient";
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
	 *  Get /ip/.. properties
	 *
	 *  @return object
	 *
	*/
	public function getIPBlockProperties(){
        return json_decode(self::getClient()->getIPBlockProperties($this->getIP()));
    }
	    
	public function setIPBlockProperties($description) {
		return json_decode(self::getClient()->setIPBlockProperties($this->getIP(),$description));
	}
	
	public function getIPBlockArp() {
		return json_decode(self::getClient()->getIPBlockArp($this->getIP()));
	}
	
	public function getIPBlockedInfo($domain) {
		return json_decode(self::getClient()->getIPBlockArp($this->getIP(),$domain));
	}
	
}