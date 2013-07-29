<?php
/**
 * Copyright 2013 Stéphane Depierrepont (aka Toorop)
 *
 * Authors :
 *  - Stéphane Depierrepont (aka Toorop)
 *  - Florian Jensen (aka flosoft) : https://github.com/flosoft
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
	 * Return Dedicated Server client
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
	 *  Get Dedicated Server properties
	 *
	 *  @return object
	 *
	 */
	public function  getProperties()
	{
		return json_decode(self::getClient()->getProperties($this->getDomain()));
	}

    /**
     * @param $bootDevice
     * @return bool true
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function setBootdevice($bootDevice){
        self::getClient()->setBootDevice($this->getDomain(),$bootDevice);
        return true;
    }

    /**
     * @param $enable
     * @return bool true
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function setMonitorin($enable){
        self::getClient()->setMonitoring($this->getDomain(),$enable);
        return true;
    }

    /**
     * Set netboot
     *
     * @param $netbootId
     * @return bool true
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function setNetBoot($netbootId){
        self::getClient()->setBootDevice($this->getDomain(),$netbootId);
        return true;
    }

    /**
     * Get IPS
     *
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @return array of strings
     */
    public function getIps()
    {
        return json_decode(self::getClient()->getIps($this->getDomain()));
    }

    /**
     * Reboot server
     *
     * @return bool true
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function reboot()
    {
        self::getClient()->reboot($this->getDomain());
        return true;
    }

    /**
     *  Get secondary DNS
     *
     * @return array
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getSecondaryDnsDomains()
    {
        return json_decode(self::getClient()->getSecondaryDnsDomains($this->getDomain()));
    }

    /**
     * Add domain to secondary DNS
     *
     * @param string $domain2add
     * @param string ipv4 $ip
     * @return bool true
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function addSecondaryDnsDomains($domain2add, $ip){
        self::getClient()->addSecondaryDnsDomains($this->getDomain(),$domain2add,$ip);
        return true;
    }

    /**
     * Get info about $domain2getInfo
     *
     * @param $domain2getInfo
     * @return array
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getSecondaryDnsDomainsInfo($domain2getInfo){
        return json_decode(self::getClient()->getSecondaryDnsDomainsInfo($this->getDomain(), $domain2getInfo));
    }

    /**
     * Delete a domain on secondayr DNS server
     *
     * @param $domain2delete
     * @return bool true
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function deleteSecondaryDnsDomains($domain2delete){
        json_decode(self::getClient()->deleteSecondaryDnsDomains($this->getDomain(), $domain2delete));
        return true;
    }

    /**
     * Get info about secondary DNS server of $domain2getInfo
     *
     * @param $domain2getInfo
     * @return array
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getSecondaryDnsServerInfo($domain2getInfo){
        return json_decode(self::getClient()->getSecondaryDnsServerInfo($this->getDomain(), $domain2getInfo));
    }


	/**
	 *  Get Dedicated Server Service Infos
	 *
	 *  @return object
	 *
	 */

	public function  getServiceInfos()
	{
		return json_decode(self::getClient()->getServiceInfos($this->getDomain()));
	}



	#Task
	/**
	 * Return tasks associated with this Dedicated Server (current and past)
	 *
	 * @return array of int
	 */
	public function getTasks()
	{
		return json_decode(self::getClient()->getTasks($this->getDomain()));
	}

	/**
	 * @param $taskId
	 * @return Task
	 */
	public function getTaskProperties($taskId)
	{
		return new Task(self::getClient()->getTaskProperties($this->getDomain(), $taskId));
	}

}
