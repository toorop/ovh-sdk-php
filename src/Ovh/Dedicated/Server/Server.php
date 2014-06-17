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
	 * @return mixed
	 */
	public function getBoot()
	{
		return json_decode(self::getClient()->getBoot($this->getDomain()));
	}

	/**
	 * @param $bootId
	 */
	public function getBootProperties($bootId)
	{
		return json_decode(self::getClient()->getBootProperties($this->getDomain(), $bootId));
	}

	/**
	 * @param $bootId
	 */
	public function getBootOptions($bootId)
	{
		return json_decode(self::getClient()->getBootOptions($this->getDomain(), $bootId));
	}

	/**
	 * @param $bootId
	 */
	public function getBootOptionsProperties($bootId, $option)
	{
		return json_decode(self::getClient()->getBootOptionsProperties($this->getDomain(), $bootId, $option));
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
    public function setMonitoring($enable){
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
        self::getClient()->setNetboot($this->getDomain(),$netbootId);
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
     * Get MRTG
     * Ajout by @Thibautg16 le 11/11/2013
     *
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @return array of strings
     */
    public function getMrtg($period,$type)
    {
        return json_decode(self::getClient()->getMrtg($this->getDomain(),$period,$type));
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
		return json_decode(self::getClient()->getTaskProperties($this->getDomain(), $taskId));
	}
	
	/*********** Interventions ***********/
        /**
        * Get Interventions
        * Retourne les interventions associés au serveur dédié (en cours et passé)
        * Ajout par @Thibautg16 le 22/11/2013
        *
        * @throws Exception\ServerException
        * @throws \Ovh\Common\Exception\BadMethodCallException
        * @return array of int
        */
        public function getInterventions(){
                return json_decode(self::getClient()->getInterventions($this->getDomain()));
        }
        
        /**
        * Get InterventionsProperties
        * Retourne les informations d'une intervention suivant son identifiant
        * Ajout by @Thibautg16 le 22/11/2013
        *
        * @throws Exception\ServerException
        * @throws \Ovh\Common\Exception\BadMethodCallException
        * @return array(date,type,id)
        */
        public function getInterventionProperties($interventionId){
                return json_decode(self::getClient()->getInterventionProperties($this->getDomain(), $interventionId));
        }

    /*********** Statistics (RTM) ***********/
		/**
		* Get Statistics (RTM)
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return object
		*/
		public function getStatistics(){
				return json_decode(self::getClient()->getStatistics($this->getDomain()));
			}
		
		/**
		* Get Statistics Chart Values
		* Ajout by @Thibautg16 le 11/11/2013
		*
		* @param $period
		* @param $type
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return object
		*/
		public function getStatisticsChart($period,$type){
			return json_decode(self::getClient()->getStatisticsChart($this->getDomain(),$period,$type));
		}
		
		/**
		* Get Statistics Connection
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return array
		*/
		public function getStatisticsConnection(){
			return json_decode(self::getClient()->getStatisticsConnection($this->getDomain()));
		}
		
		/**
		* Get Statistics Cpu
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsCpu(){
			return json_decode(self::getClient()->getStatisticsCpu($this->getDomain()));
		}
		
		/**
		* Get Statistics Disk
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Array
		*/
		public function getStatisticsDisk(){
			return json_decode(self::getClient()->getStatisticsDisk($this->getDomain()));
		}
		
		/**
		* Get Disk Properties
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @param $disk
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsDiskProperties($disk){
			return json_decode(self::getClient()->getStatisticsDiskProperties($this->getDomain(),$disk));
		}
		
		/**
		* Get SMART Disk Informations
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @param $disk
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsDiskSmart($disk){
			return json_decode(self::getClient()->getStatisticsDiskSmart($this->getDomain(),$disk));
		}
		
		/**
		* Get Statistics Load
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsLoad(){
			return json_decode(self::getClient()->getStatisticsLoad($this->getDomain()));
		}
		
		/**
		* Get Statistics Memory
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Array
		*/
		public function getStatisticsMemory(){
				return json_decode(self::getClient()->getStatisticsMemory($this->getDomain()));
		}
		
		/**
		* Get Motherboard Informations
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsMotherboard(){
			return json_decode(self::getClient()->getStatisticsMotherboard($this->getDomain()));
		}

		/**
		* Get Os Informations
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsOs(){
			return json_decode(self::getClient()->getStatisticsOs($this->getDomain()));
		}

		/**
		* Get Partitions Informations
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Array
		*/
		public function getStatisticsPartition(){
			return json_decode(self::getClient()->getStatisticsPartition($this->getDomain()));
		}
		
		/**
		* Get Partition Properties
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @param $partition
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsPartitionProperties($partition){
			return json_decode(self::getClient()->getStatisticsPartitionProperties($this->getDomain(),$partition));
		}
		
		/**
		* Get Disk Properties
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @param $partition
		* @param $period
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsPartitionChart($partition, $period){
			return json_decode(self::getClient()->getStatisticsPartitionChart($this->getDomain(),$partition, $period));
		}
		
		/**
		* Get PCI Devices Informations
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsPci(){
			return json_decode(self::getClient()->getStatisticsPci($this->getDomain()));
		}
		
		/**
		* Get Process
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Array
		*/
		public function getStatisticsProcess(){
			return json_decode(self::getClient()->getStatisticsProcess($this->getDomain()));
		}
		
		/**
		* Get Server Raid Informations
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Array
		*/
		public function getStatisticsRaid(){
			return json_decode(self::getClient()->getStatisticsRaid($this->getDomain()));
		}
		
		/**
		* Get Raid Properties
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsRaidProperties($unit){
			return json_decode(self::getClient()->getStatisticsRaidProperties($this->getDomain(),$unit));
		}
		
		/**
		* Get Raid Volumes
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Array
		*/
		public function getStatisticsRaidVolume($unit){
			return json_decode(self::getClient()->getStatisticsRaidVolume($this->getDomain(),$unit));
		}
		
		/**
		* Get Raid Volume Properties
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Object
		*/
		public function getStatisticsRaidVolumeProperties($unit,$volume){
			return json_decode(self::getClient()->getStatisticsRaidVolumeProperties($this->getDomain(),$unit,$volume));
		}
		
		/**
		* Get Raid Volume Ports
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Array
		*/
		public function getStatisticsRaidVolumePort($unit,$volume){
			return json_decode(self::getClient()->getStatisticsRaidVolumePort($this->getDomain(),$unit,$volume));
		}
		
		/**
		* Get Raid Volume Ports Properties
		* Ajout by @Thibautg16 le 01/06/2014
		*
		* @throws Exception\ServerException
		* @throws \Ovh\Common\Exception\BadMethodCallException
		*
		* @return Array
		*/
		public function getStatisticsRaidVolumePortProperties($unit,$volume,$port){
			return json_decode(self::getClient()->getStatisticsRaidVolumePortProperties($this->getDomain(),$unit,$volume,$port));
		}
		
	/**
    * Get Hardware Specifications
	* Ajout by @Thibautg16 le 01/06/2014
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
	*
    * @return Object
    */
	public function getSpecificationsHardware(){
        return json_decode(self::getClient()->getSpecificationsHardware($this->getDomain()));
    }
}