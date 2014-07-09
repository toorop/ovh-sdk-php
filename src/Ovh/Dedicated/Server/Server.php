<?php
/**
 * Copyright 2013 Stéphane Depierrepont (aka Toorop)
 *
 * Authors :
 *  - Stéphane Depierrepont (aka Toorop)
 *  - Florian Jensen (aka flosoft) : https://github.com/flosoft
 *  - Gillardeau Thibaut (aka Thibautg16)
 *  - Scott Brown (aka Slartibardfast)
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
	
/** BackupFTP - added in 0.1.2 **/

	/*
	* Get backup FTP assigned to server
	*
	* @return mixed
	*/
	public function getbackupFTP()
	{
		return json_decode(self::getClient()->getBackupFTP($this->getDomain()));
	}
	
	/*
	* Create Backup FTP on server
	*
	* @return mixed
	*/
	public function createBackupFTP()
	{
		return json_decode(self::getClient()->createBackupFTP($this->getDomain()));
	}
	
	/*
	* Delete backup FTP   ** NOT IMPLEMENTED IN CLIENT ** Shell only
	*
	*/
	public function deleteBackupFTPAccess()
	{
		return json_decode(self::getClient()->deleteBackupFTPAccess($this->getDomain()));
	}
	
	/* 
	* Get backup FTP ACL list
	*
	* @returns object (array of ACL)
	*/
	public function getBackupFTPAccess()
	{
		return json_decode(self::getClient()->getBackupFTPAccess($this->getDomain()));
	}

	/*
	* Create backup FTP ACL for IPBlock (creates with default ACL)
	*
	* @returns mixed
	*/
	public function createBackupFTPAccess($ipBlock)
	{
		return json_decode(self::getClient()->createBackupFTPAccess($this->getDomain(), $ipBlock));
	}

	/*
	* Get Authorizable IPblocks on this backup FTP space (IPblocks assoc with the server)
	*
	* @returns object (array of IPblocks)
	*/
	public function getBackupFTPAuthorizableBlocks()
	{
		return json_decode(self::getClient()->getBackupFTPAuthorizableBlocks($this->getDomain()));
	}
	
	/*
	* Get BackupFTPAccessBlock - Get ACL for specific IPBlock
	*
	* @returns object (ACL information)
	*/
	public function getBackupFTPaccessBlock($ipBlock)
	{
		return json_decode(self::getClient()->getBackupFTPaccessBlock($this->getDomain(),$ipBlock));
	}
	
	/*
	* delete backup FTP ACL for IPBlock
	*
	* @returns object
	*/
	public function deleteBackupFTPaccessBlock($ipBlock)
	{
		return json_decode(self::getClient()->deleteBackupFTPaccessBlock($this->getDomain(),$ipBlock));
	}
	
	/*
	* Set backup FTP ACL for IPblock
	*
	* returns object (null??)
	*/
	public function setBackupFTPaccessBlock($ipBlock, $ftp, $nfs, $cifs)
	{
		return json_decode(self::getClient()->setBackupFTPaccessBlock($this->getDomain(),$ipBlock, $ftp, $nfs, $cifs));
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

// Network additions in v.1.0.2
	/*
	* Get Network Configuration details 
	* 
	* @ returns object containing network params
	*/
	public function  getNetworkSpecifications()
	{
		return json_decode(self::getClient()->getNetworkSpecifications($this->getDomain()));
	}
	
	/*
	* Get Network Burst setting
	*
	* @return mixed
	 */
	public function getBurst()
	{
		return json_decode(self::getClient()->getBurst($this->getDomain()));
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
	
// Orderables -- added in v1.0.2
	/*
	* Get list of orderable FTP Backup sizes for the specific server
	*
	* @return Object (Array of valid sizes)
	*/
	public function getOrderableBackupFTP() {
        return json_decode(self::getClient()->getOrderableBackupFTP($this->getDomain()));
    }
	
	/*
	* Get list of orderable USB keys for the specific server
	*
	* @return Object (array of valid key sizes)
	*/
	public function getOrderableUSB(){
        return json_decode(self::getClient()->getOrderableUSB($this->getDomain()));
    }
	
	/*
	* Determins if "professionalUse" is available for order on specific server
	*
	* @returns object (contains boolean value)
	*/
	public function getOrderableProfessionalUse(){
        return json_decode(self::getClient()->getOrderableProfessionalUse($this->getDomain()));
    }

// Installation information -- added in v0.1.2
	/* 
	* Get list of installation templates compatible with server
	*
	* @returns object (multi-d-array of templates)
	*/
	public function getCompatibleTemplates() {
		return json_decode(self::getClient()->getCompatibleTemplates($this->getDomain()));
	}
	
	/*
	* Get list of partition schemes available with specific template
	*
	* @returns object (array of partition schemes)
	*/
	public function getCompatibleTemplatePartitionSchemes($domain) {
		return json_decode(self::getClient()->getCompatibleTemplatePartitionSchemes($this->getDomain()));

	}

// IPs list -- overlaps with /ips/ heirarchy, but this is available in the /dedicated/server heirarchy

	/*
	* Get list of Ips assined to server
	*
	* @returns array of IPs assigned (IPv4 and IPv6)
	*/
	public function getServerIPs() {
		return json_decode(self::getClient()->getServerIPs($this->getDomain()));

	}

// virtual MAC API
	
	/*
	* Get list of vMac addresses assigned to server
	*
	* @returns array of vMacs assigned 
	*/
//	/dedicated/server/{serviceName}/virtualMac
	public function getVmacs() {
		return json_decode(self::getClient()->getVmacs($this->getDomain()));
	}
	
	/*
	* assign vMac to an IP
	*
	* @returns task (array of mixed) :
	*
	*	{
	*		"taskId": 123456,
	*		"function": "virtualMacAdd",
	*		"lastUpdate": "2014-07-04T19:28:52-04:00",
	*		"comment": "Create a virtual mac for ip a.b.c.d",
	*  		"status": "init",
	*  		"startDate": "2014-07-04T19:28:52-04:00",
	*  		"doneDate": null
	*	}
	*
	*/
// POST	/dedicated/server/{serviceName}/virtualMac
	public function createVmac($ip,$type,$vmname) {
		return json_decode(self::getClient()->assignVmac($this->getDomain(),$ip, $type, $vmname));
	}

	/*
	* Get list of vMac addresses assigned to server
	*
	* @returns array of vMacs assigned 
	*/
// GET	/dedicated/server/{serviceName}/virtualMac/{virtualmac}
	public function getVmacProperties($vmac) {
		return json_decode(self::getClient()->getVmacProperties($this->getDomain(),$vmac));
	}
	
	/*
	* Get list of IPs addresses assigned to vMAC 
	*
	* @returns array of vMacs assigned 
	*/
//	/dedicated/server/{serviceName}/virtualMac/{virtualmac}/virtualAddress
	public function getVmacIPAddresses($vmac) {
		return json_decode(self::getClient()->getVmacIPAddresses($this->getDomain(),$vmac));
	}
	
	/*
	* add an IP addresses to vMAC 
	*
	* @returns array of vMacs assigned 
	*/
//	/dedicated/server/{serviceName}/virtualMac/{virtualmac}/virtualAddress
	public function setVmacIPAddresses($vmac, $ip, $vmname) {
		return json_decode(self::getClient()->getVmacIPAddress($this->getDomain(),$vmac, $ip, $vmname));
	}
	
	/*
	* add an IP addresses to vMAC 
	*
	* @returns array of vMacs assigned 
	*/
//	/dedicated/server/{serviceName}/virtualMac/{virtualmac}/virtualAddress
	public function deleteVmacIPAddress($vmac, $ip) {
		return json_decode(self::getClient()->deleteVmacIPAddress($this->getDomain(),$vmac, $ip));
	}
	
	/*
	* function to lookup a vMac based on an IP address - why dont they include this in the API?
	*
	*@param $ipv4 - IPv4 to lookup
	*
	*@returns vmac or null string if not found
	*/
	public function findVmac($ipv4) {
		$vmacs = json_decode(self::getClient()->getVmacs($this->getDomain()));
		foreach($vmacs as $vmac) {
			$test_ip = json_decode(self::getClient()->getVmacIPAddresses($this->getDomain(),$vmac));
			foreach($test_ip as $ip) {
				if ($ip == $ipv4)
					return $vmac;
			}
		}
		return "";
	}
	
	
}