<?php
/**
 * Copyright 2013 Stéphane Depierrepont (aka Toorop)
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

namespace Ovh\Dedicated\Server;


use Ovh\Common\Exception\NotImplementedYetException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;

use Ovh\Common\Ovh;
use Ovh\Dedicated\Server\ServerClient;
use Ovh\Common\Task;


class Server
{
	private $domain = null;
	private static $serverClient = null;

	/**
	 * @param string $domain
	 */
	public function __construct($domain)
	{
		$this->setDomain($domain);
	}


	/**
	 * Return VPS client
	 *
	 * @return null|serverClient
	 */
	private static function getClient()
	{
		if (!self::$serverClient instanceof ServerClient){
			self::$serverClient=new ServerClient();
		};
		return self::$serverClient;
	}

	/**
	 * Set domain
	 *
	 * @param string $domain
	 */
	public function setDomain($domain)
	{
		$this->domain = $domain;
	}

	/**
	 * Get domain
	 *
	 * @return null | string domain
	 */
	public function getDomain()
	{
		return $this->domain;
	}



	###
	# Mains methods from OVH Rest API
	##

	/**
	 *  Get VPS properties
	 *
	 *  @return object
	 *
	 */
	public function  getProperties()
	{
		return json_decode(self::getClient()->getProperties($this->getDomain()));
	}

}
