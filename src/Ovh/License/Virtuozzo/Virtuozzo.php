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

namespace Ovh\Vrack;


use Ovh\Common\Exception\NotImplementedYetException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;

use Ovh\Common\Ovh;
use Ovh\Vrack\VrackClient;
use Ovh\Common\Task;


class Vrack
{
	private $domain = null;
	private static $vrackClient = null;

	/**
	 * @param string $domain
	 */
	public function __construct($domain)
	{
		$this->setDomain($domain);
	}


	/**
	 * Return Vrack client
	 *
	 * @return null|vrackClient
	 */
	private static function getClient()
	{
		if (!self::$vrackClient instanceof VrackClient){
			self::$vrackClient=new VrackClient();
		};
		return self::$vrackClient;
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
	 *  Get Dedicated Vrack properties
	 *
	 *  @return object
	 *
	*/
	public function getProperties(){
        return json_decode(self::getClient()->getProperties($this->getDomain()));
    }
	    
    /*********** DedicatedServer ***********/
	
    /**
     * Get dedicatedServer
	 * Retourne les serveurs dédiés actuellement dans le vrack
     * Ajout by @Thibautg16 le 24/06/2014
     *
     * @throws Exception\VrackException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @return array of strings
     */
    public function getdedicatedServer()
    {
        return json_decode(self::getClient()->getdedicatedServer($this->getDomain()));
    }	
	
    /**
     * Get MRTG
	 * Retourne les valeurs du graphique de trafic du vRack pour un serveur
     * Ajout by @Thibautg16 le 26/03/2014
     *
     * @throws Exception\VrackException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @return array of strings
     */
    public function getMrtg($serveur,$period,$type)
    {
        return json_decode(self::getClient()->getMrtg($this->getDomain(),$serveur,$period,$type));
    }
	
	/*********** dedicatedCloud ***********/
	
    /**
     * Get dedicatedCloud
	 * Retourne les Cloud dédié actuellement dans le vrack
     * Ajout by @Thibautg16 le 24/06/2014
     *
     * @throws Exception\VrackException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @return array of strings
     */
    public function getdedicatedCloud()
    {
        return json_decode(self::getClient()->getdedicatedCloud($this->getDomain()));
    }	
	
	/*********** Ip ***********/
	
    /**
     * Get ip
	 * Retourne les blocs IP actuellement dans le vrack
     * Ajout by @Thibautg16 le 24/06/2014
     *
     * @throws Exception\VrackException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @return array of strings
     */
    public function getIp()
    {
        return json_decode(self::getClient()->getIp($this->getDomain()));
    }
}