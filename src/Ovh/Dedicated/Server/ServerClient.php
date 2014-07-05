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

#use Guzzle\Http\Exception\ClientErrorResponseException;

#use Guzzle\Http\Exception\BadResponseException;
#use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;

#use Ovh\Common\Exception\NotImplementedYetException;

//use Ovh\Vps\Exception\VpsNotFoundException;
use Ovh\Dedicated\Server\Exception\ServerException;


class ServerClient extends AbstractClient
{

    /**
     * Get properties
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\ServerException
     * @throws Exception\ServerNotFoundException
     */
    public function getProperties($domain)
    {
        try {
            $r = $this->get('dedicated/server/' . $domain)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

	/**
	 * Get boot options
	 */

	public function getBoot($domain)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		try {
			$r = $this->get('dedicated/server/' . $domain . '/boot')->send();
		} catch (\Exception $e) {
			throw new ServerException($e->getMessage(), $e->getCode(), $e);
		}
		return $r->getBody(true);
	}

	/**
	 * Get backupFTP
	 * 
	 * @param 	$domain -> Server that this applies to
	 * @return 	object result set
	 *
	 * throws BadMethodCallException if server is not defined
	 * 		usual handling for 400/404 errors
	 */
	/// dedicated/server/{serviceName}/features/backupFTP
	public function getbackupFTP($domain)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		try {
			$r = $this->get('dedicated/server/' . $domain . '/features/backupFTP')->send();
		} catch (\Exception $e) {
			throw new ServerException($e->getMessage(), $e->getCode(), $e);
		}
		return $r->getBody(true);
	}

	/**
	 * Create new backupFTP
	 * 
	 * @param 	$domain -> Server that this applies to
	 * @return 	object result set
	 *
	 * throws BadMethodCallException if server is not defined
	 * 		usual handling for 400/404 errors
	 */
	// /dedicated/server/{serviceName}/features/backupFTP
	public function createBackupFTP($domain)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('dedicated/server/' . $domain . '/features/backupFTP')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	}
	
	/**
	 * delete backupFTP
	 * 
	 * @param 	$domain -> Server that this applies to
	 *
	 *** Throws NotImplementedYetException... not ready to try this out :)
	 *
	 */
	// /dedicated/server/{serviceName}/features/backupFTP
	public function deleteBackupFTPAccess($domain)
	{
		throw new NotImplementedYetException('not yet implemented');
	}
	
	
	/**
	 * Get backupFTP ACL list
	 * 
	 * @param 	$domain -> Server that this applies to
	 * @return 	object result set
	 *
	 * throws BadMethodCallException if server is not defined
	 * 		usual handling for 400/404 errors
	 */
	
	// dedicated/server/{serviceName}/features/backupFTP/access
	public function getBackupFTPAccess($domain)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/features/backupFTP/access')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	}
	
	/**
	 * Creatge backupFTP ACL
	 * 
	 * @param 	$domain -> Server that this applies to
	 * @param	$ipBlock -> ipblock that is being granted access
	 *
	 * this requires that one type of access be set - and since this is called backuipFTP - I chose the FTP as default
	 * a second call to set actual desired ACL will be required
	 *
	 * @return 	object result set
	 *
	 * throws BadMethodCallException if server is not defined
	 * 		usual handling for 400/404 errors
	 */
	public function createBackupFTPAccess($domain, $ipBlock)
	{
	//	$domain = (string)$domain;
	//	$ipBlock= (string)$ipBlock;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		if (!$ipBlock)
			throw new BadMethodCallException('Parameter $ipBlock is missing.');
		$payload = array(
			'ftp' => (1==1), 
			'ipBlock' => $ipBlock,
			'nfs' => (1==0),
			'cifs' => (1==0)
		 );
		try {
            $r = $this->post('dedicated/server/' . $domain . '/features/backupFTP/access', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	}
	
	/**
	 * Get backupFTP Authorizable IPBlocks
	 *
	 * only the IP blocks associated with your server can access yout FTP space - this returns the valid list.
	 * 
	 * @param 	$domain -> Server that this applies to
	 * @return 	object result set
	 *
	 * throws BadMethodCallException if server is not defined
	 * 		usual handling for 400/404 errors
	 */
	public function getBackupFTPAuthorizableBlocks($domain)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/features/backupFTP/authorizableBlocks')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	}

	/**
	 * Get backupFTP ACL for specified ipBlock
	 * 
	 * @param 	$domain -> Server that this applies to
	 * @param 	$ipblock -> ipblock this applies to
	 * @return 	object result set
	 *
	 * throws BadMethodCallException if server or ipblock is not defined
	 * 		usual handling for 400/404 errors
	 */
	public function getBackupFTPaccessBlock($domain,$ipBlock)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		if (!$ipBlock)
			throw new BadMethodCallException('Parameter $ipBlock is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/features/backupFTP/access/'.urlencode($ipBlock))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	}
	
	/**
	 * delete backupFTP ACL for specified IPblock
	 * 
	 * @param 	$domain -> Server that this applies to
	 * @param	$ipblock -> Ipblock that this applies to
	 * @return 	object result set
	 *
	 * throws BadMethodCallException if server or ipblock is not defined
	 * 		usual handling for 400/404 errors
	 */
	public function deleteBackupFTPaccessBlock($domain,$ipBlock)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		if (!$ipBlock)
			throw new BadMethodCallException('Parameter $ipBlock is missing.');
        try {
            $r = $this->delete('dedicated/server/' . $domain . '/features/backupFTP/access/'.urlencode($ipBlock))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	}

	/**
	 * Get Set backupFTP ACL
	 * 
	 * @param 	$domain -> Server that this applies to
	 * @param	$ipblock -> ip block this applies to
	 * @return 	object result set
	 *
	 * throws BadMethodCallException if any param is not defined
	 * 		usual handling for 400/404 errors
	*/
	public function setBackupFTPaccessBlock($domain,$ipBlock, $ftp, $nfs, $cifs)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		if (!$ipBlock)
			throw new BadMethodCallException('Parameter $ipBlock is missing.');
		if (!$ftp)
			throw new BadMethodCallException('Parameter $ftp is missing.');
		if (!$nfs)
			throw new BadMethodCallException('Parameter $nfs is missing.');
		if (!$cifs)
			throw new BadMethodCallException('Parameter $cifs is missing.');

		$payload = array('ftp' => ($ftp=='on') , 'nfs' => ($nfs=='on') , 'cifs' => ($cifs=='on') );
			
        try {
            $r = $this->put('dedicated/server/' . $domain . '/features/backupFTP/access/'.urlencode($ipBlock),array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	}

	/**
	 * Get boot properties
	 *
	 * @param $domain
	 * @param $bootId
	 * @return mixed
	 * @throws \Ovh\Common\Exception\BadMethodCallException
	 * @throws Exception\ServerException
	 */
	public function getBootProperties($domain, $bootId)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		$bootId = (string)$bootId;
		if (!$bootId)
			throw new BadMethodCallException('Parameter $bootId is missing.');
		try {
			$r = $this->get('dedicated/server/' . $domain . '/boot/' . $bootId)->send();
		} catch (\Exception $e) {
			throw new ServerException($e->getMessage(), $e->getCode(), $e);
		}
		return $r->getBody(true);
	}

	/**
	 * Get boot options
	 *
	 * @param $domain
	 * @param $bootId
	 * @return mixed
	 * @throws \Ovh\Common\Exception\BadMethodCallException
	 * @throws Exception\ServerException
	 */
	public function getBootOptions($domain, $bootId)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		$bootId = (string)$bootId;
		if (!$bootId)
			throw new BadMethodCallException('Parameter $bootId is missing.');
		try {
			$r = $this->get('dedicated/server/' . $domain . '/boot/' . $bootId . '/option')->send();
		} catch (\Exception $e) {
			throw new ServerException($e->getMessage(), $e->getCode(), $e);
		}
		return $r->getBody(true);
	}

	/**
	 * Get defintion of BootID options - added but not used... not tested
	 * 
	 * @param 	$domain -> Server that this applies to
	 * @param	$bootid -> ip block this applies to
	 * @param	$option -> option this applies to
	 * @return 	object result set
	 *
	 * throws BadMethodCallException if any param is not defined
	 * 		usual handling for 400/404 errors
	*/
	public function getBootOptionsProperties($domain, $bootId, $option)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		$bootId = (string)$bootId;
		if (!$bootId)
			throw new BadMethodCallException('Parameter $bootId is missing.');
		$option = (string)$option;
		if (!$option)
			throw new BadMethodCallException('Parameter $option is missing.');
		try {
			$r = $this->get('dedicated/server/' . $domain . '/boot/' . $bootId . '/option/' . $option)->send();
		} catch (\Exception $e) {
			throw new ServerException($e->getMessage(), $e->getCode(), $e);
		}
		return $r->getBody(true);
	}

	/**
     * Get Burst
     *
     * @param $domain
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getBurst($domain)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain = (string)$domain;
        try {
           $r = $this->get('dedicated/server/' . $domain . '/burst/' )->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


	/**
     * Set boot device
     *
     * @param $domain
     * @param $bootDevice
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function setBootDevice($domain, $bootDevice)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain = (string)$domain;
        if (!$bootDevice)
            throw new BadMethodCallException('Parameter $bootDeevice is missing.');
        $bootDevice = (string)$bootDevice;
        $payload = array('bootDevice' => $bootDevice);
        try {
            $r = $this->put('dedicated/server/' . $domain, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * (de)activate monitoring
     *
     * @param string $domain
     * @param boolean $enable
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
	/**********************************************
	*
	*	This didmt work passing bools though as $enable - testing logic on bools is a PITA.
	*
	*	if (!var) when var == false => true, and that was tossing missing paramaeter messages.
	*
	*	modified to pass on/off instead
	*
	*/
    public function setMonitoring($domain, $_enable)
    {	
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain = (string)$domain;
		if ($_enable == "on") {
			$enable = true;
		} else {
			$enable = false;
		}

//        if (!$enable)
//            throw new BadMethodCallException('Parameter enable is missing.');
        if (!is_bool($enable)) {
            throw new BadMethodCallException('Parameter $enable must be a boolean');
        }
        $payload = array('monitoring' => $enable);
        try {
            $r = $this->put('dedicated/server/' . $domain, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * Set netboot
     *
     * @param string $domain
     * @param int $bootId
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function setNetboot($domain, $bootId)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain = (string)$domain;
        if (!$bootId)
            throw new BadMethodCallException('Parameter $bootId is missing.');
        $bootId = intval($bootId);
        $payload = array('bootId' => $bootId);
        try {
            $r = $this->put('dedicated/server/' . $domain, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get IPs
     *
     * @param $domain
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getIps($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/ips')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    
    /**
     * Get MRTG
     * Ajout by @Thibautg16 le 11/11/2013
     * 
     * @param string $domain
     * @param string $period
     * @param string $type
     * 
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getMrtg($domain, $period='daily', $type='traffic:download'){

        $domain = (string)$domain;
        $period = (string)$period;
        $type   = (string)$type;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/mrtg?period='.$period.'&type='.$type)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    

    /**
     * Reboot
     *
     * @param string $domain
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function reboot($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $this->post('dedicated/server/'.$domain.'/reboot', array('Content-Type' => 'application/json;charset=UTF-8'))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get secondary DNS
     *
     * @param $domain
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getSecondaryDnsDomains($domain){
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/secondaryDnsDomains')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Add a domain on secondary DNS
     *
     * @param string $domain
     * @param string $domain2add
     * @param string $ip
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function addSecondaryDnsDomains($domain, $domain2add, $ip){
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain2add = (string)$domain2add;
        if (!$domain2add)
            throw new BadMethodCallException('Parameter $domain2add is missing.');
        $ip = (string)$ip;
        if (!$ip)
            throw new BadMethodCallException('Parameter $ip is missing.');
        $payload = array("domain"=>$domain2add, "ip"=>$ip);
        try {
            $r = $this->post('dedicated/server/'.$domain.'/secondaryDnsDomains', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get info about $domain2getInfo
     *
     * @param string $domain
     * @param string $domain2getInfo
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getSecondaryDnsDomainsInfo($domain, $domain2getInfo){
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain2getInfo = (string)$domain2getInfo;
        if (!$domain2getInfo)
            throw new BadMethodCallException('Parameter $domain2getInfo is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/secondaryDnsDomains/'.$domain2getInfo)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Delete a domain on secondary DNS server
     *
     * @param string $domain
     * @param string $domain2delete
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function deleteSecondaryDnsDomains($domain, $domain2delete){
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain2delete = (string)$domain2delete;
        if (!$domain2delete)
            throw new BadMethodCallException('Parameter $domain2getInfo is missing.');
        try {
            $r = $this->delete('dedicated/server/' . $domain . '/secondaryDnsDomains/'.$domain2delete)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get info about secondary DNS server of $domain2getInfo
     *
     * @param string $domain
     * @param string $domain2getInfo
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getSecondaryDnsServerInfo($domain, $domain2getInfo){
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain2getInfo = (string)$domain2getInfo;
        if (!$domain2getInfo)
            throw new BadMethodCallException('Parameter $domain2getInfo is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/secondaryDnsDomains/'.$domain2getInfo.'/server')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    
    /**
     * Get Service Infos
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\ServerException
     * @throws Exception\ServerNotFoundException
     */
    public function getServiceInfos($domain)
    {
        try {
            $r = $this->get('dedicated/server/' . $domain . '/serviceInfos')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


	/**
     * Get Network Specs - internal/external speeds and limits
     *
     * @param string $domain
     * @return mixed
     * normal handling of 400/404s
     */
	public function getNetworkSpecifications($domain)
	{
        try {
            $r = $this->get('dedicated/server/' . $domain . '/specifications/network')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    /**
     * Tasks associated to this server
     *
     * @param string $domain
     * @return mixed
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\ServerException
     */
    public function getTasks($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/task')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get task properties
     *
     * @param $domain
     * @param $taskId
     * @return mixed
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\ServerException
     */
    public function getTaskProperties($domain, $taskId)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $taskId = (string)$taskId;
        if (!$taskId)
            throw new BadMethodCallException('Parameter $taskId is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/task/' . $taskId)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Retourne les interventions associés au serveur dédié (en cours et passé)
    * Ajout par @Thibautg16 le 22/11/2013
    *
    * @param string $domain
    * @throws \Ovh\Common\Exception\BadMethodCallException
    * @throws Exception\ServerException
    * @return int
    */
    public function getInterventions($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/intervention')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
    /**
    * Retourne les informations d'une intervention suivant son identifiant
    * Ajout par @Thibautg16 le 22/11/2013
    *
    * @param $domain
    * @param $interventionId
    * @throws \Ovh\Common\Exception\BadMethodCallException
    * @throws Exception\ServerException
    * @return array(date,type,id)
    */
    public function getInterventionProperties($domain, $interventionId)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $interventionId = (string)$interventionId;
        if (!$interventionId)
            throw new BadMethodCallException('Parameter $interventionId is missing.');
        try {
            $r = $this->get('dedicated/server/' . $domain . '/intervention/' . $interventionId)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /*********** Statistics (RTM) ***********/
    /**
    * Get Statistics (RTM)
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatistics($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Statistics Chart Values
    * Ajout by @Thibautg16 le 01/06/2014
    * 
    * @param string $domain
    * @param string $period
    * @param string $type
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsChart($domain, $period='daily', $type='cpu'){
        $domain = (string)$domain;
        $period = (string)$period;
        $type   = (string)$type;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/chart?period='.$period.'&type='.$type)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Statistics Connection
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsConnection($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/connection')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Statistics Cpu
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsCpu($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/cpu')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Serveur Disks
    * GET /dedicated/server/{serviceName}/statistics/disk
    * Liste des disques
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsDisk($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/disk')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Disk Properties
    * GET /dedicated/server/{serviceName}/statistics/disk/{disk}
    * Informtation sur le disque
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    * 
    * @param string $domain
    * @param string $disk
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsDiskProperties($domain, $disk='disk-0'){
        $domain = (string)$domain;
        $disk   = (string)$disk;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/disk/'.$disk)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Disk Smart
    * GET /dedicated/server/{serviceName}/statistics/disk/{disk}/smart
    * Informtation SMART sur le disque
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    * 
    * @param string $domain
    * @param string $disk
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsDiskSmart($domain, $disk='disk-0'){
        $domain = (string)$domain;
        $disk   = (string)$disk;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/disk/'.$disk.'/smart')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Load
    * GET /dedicated/server/{serviceName}/statistics/load
    * Information sur la charge du serveur
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsLoad($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/load')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Memory Information
    * GET /dedicated/server/{serviceName}/statistics/memory
    * Information sur la memoire 
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsMemory($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/memory')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Motherboard Information
    * GET /dedicated/server/{serviceName}/statistics/motherboard
    * Information sur la carte-mère
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsMotherboard($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/motherboard')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Os Informations
    * GET /dedicated/server/{serviceName}/statistics/os
    * Information sur la carte-mère
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsOs($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/os')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Partition
    * GET /dedicated/server/{serviceName}/statistics/partition
    * Information sur les partitions
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsPartition($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/partition')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Partition Properties
    * GET/dedicated/server/{serviceName}/statistics/partition/{partition}
    * Informtation sur la partition
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    * 
    * @param string $domain
    * @param string $partition
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsPartitionProperties($domain, $partition){
        $domain    = (string)$domain;
        $partition = (string)$partition;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/partition/'.$partition)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Partition Properties
    * GET/dedicated/server/{serviceName}/statistics/partition/{partition}/chart
    * Informtation graphique sur la partition
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    * 
    * @param string $domain
    * @param string $partition
    * @param string $period
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsPartitionChart($domain, $partition, $period='daily'){
        $domain    = (string)$domain;
        $partition = (string)$partition;
        $period    = (string)$period;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/partition/'.$partition.'/chart?period='.$period)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server PCI Devices Informations
    * GET /dedicated/server/{serviceName}/statistics/pci
    * Informations sur périphériques PCI
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsPci($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/pci')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Process
    * GET /dedicated/server/{serviceName}/statistics/process
    * Informations sur les process du serveur
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsProcess($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/process')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Raid
    * GET /dedicated/server/{serviceName}/statistics/raid
    * Informations sur les volumes RAID
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsRaid($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/raid')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Raid Properties
    * GET /dedicated/server/{serviceName}/statistics/raid/{unit}
    * Propriétés d'un RAID
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    * @param string $unit
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsRaidProperties($domain,$unit){
        $domain = (string)$domain;
        $unit   = (string)$unit;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/raid/'.$unit)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Raid Volumes
    * GET /dedicated/server/{serviceName}/statistics/raid/{unit}/volume
    * Liste des volumes présent sur le RAID
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    * @param string $unit
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsRaidVolume($domain,$unit){
        $domain = (string)$domain;
        $unit   = (string)$unit;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/raid/'.$unit.'/volume')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Raid Volume Properties
    * GET /dedicated/server/{serviceName}/statistics/raid/{unit}/volume/{volume}
    * Propriétes du volume RAID
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    * @param string $unit
    * @param string $volume
    *
    * @return Object
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsRaidVolumeProperties($domain,$unit,$volume){
        $domain = (string)$domain;
        $unit   = (string)$unit;
        $volume = (string)$volume;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/raid/'.$unit.'/volume/'.$volume)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Raid Volume Ports
    * GET /dedicated/server/{serviceName}/statistics/raid/{unit}/volume/{volume}/port
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    * @param string $unit
    * @param string $volume
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsRaidVolumePort($domain,$unit,$volume){
        $domain = (string)$domain;
        $unit   = (string)$unit;
        $volume = (string)$volume;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/raid/'.$unit.'/volume/'.$volume.'/port')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Get Server Raid Volume Ports Properties
    * GET /dedicated/server/{serviceName}/statistics/raid/{unit}/volume/{volume}/port/{port}
    * 
    * Ajout by @Thibautg16 le 01/06/2014
    *
    * @param string $domain
    * @param string $unit
    * @param string $volume
    * @param string $port
    *
    * @return Array
    *
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getStatisticsRaidVolumePortProperties($domain,$unit,$volume,$port){
        $domain = (string)$domain;
        $unit   = (string)$unit;
        $volume = (string)$volume;
        $port   = (string)$port;

        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/statistics/raid/'.$unit.'/volume/'.$volume.'/port/'.$port)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
	/**
    * Get Hardware Specifications 
	* GET /dedicated/server/{serviceName}/specifications/hardware
	* Informations sur les spécifications hardware du serveur
	* 
    * Ajout by @Thibautg16 le 01/06/2014
    *
	* @param string $domain
	*
	* @return Object
	*
    * @throws Exception\ServerException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getSpecificationsHardware($domain){
        $domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/specifications/hardware')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
	/* 
	 * Gets list of the sizes of backupFTP that can be ordered for the server
	 *
	 * @param 	$domain -> server
	 * @returns json list of valid backup sizes for this server
	 *
	 * @throws BadMedhodCallException if no domain
	 * 			normal handling of 400/404's
	*/
	public function getOrderableBackupFTP($domain) {
		$domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/orderable/backupStorage')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
		
	//var_dump($r->getBody(true));
        return $r->getBody(true);

	}
	
	/* 
	 * Gets list of the sizes of USBKeys that can be ordered for the server
	 *
	 * @param 	$domain -> server
	 * @returns json list of USBKey sizes for this server
	 *
	 * @throws BadMedhodCallException if no domain
	 * 			normal handling of 400/404's
	*/
	public function getOrderableUSB($domain) {
		$domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/orderable/usbKey')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);

	}
	
	/* 
	 * Gets list of the sizes of USBKeys that can be ordered for the server
	 *
	 * @param 	$domain -> server
	 * @returns list of USBKey sizes for this server
	 *
	 * @throws BadMedhodCallException if no domain
	 * 			normal handling of 400/404's
	*/
	public function getOrderableProfessionalUse($domain) {
		$domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/orderable/professionalUse')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);

	}
	
	/* 
	 * Gets list of templates that can be installed onto server
	 *
	 * @param 	$domain -> server
	 * @returns object list of ovh and personal templates valid for this server
	 *
	 * @throws BadMedhodCallException if no domain
	 * 			normal handling of 400/404's
	*/
	public function getCompatibleTemplates($domain) {
		$domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/install/compatibleTemplates')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);

	}
	
	/* 
	 * Gets list of partitioning schemes that can be installed onto server -- untested
	 *
	 * @param 	$domain -> server
	 * @returns object list partitioning schemes valid for this server
	 *
	 * @throws BadMedhodCallException if no domain
	 * 			normal handling of 400/404's
	*/
	public function getCompatibleTemplatePartitionSchemes($domain) {
		$domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/compatibleTemplatePartitionSchemes')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);

	}

/********* may be duplicated functionality *********/
	/* 
	 * Gets list of assopciated with server
	 *
	 * @param 	$domain -> server
	 * @returns object list IPs (Ipv4 and IPv6) valid for this server
	 *
	 * @throws BadMedhodCallException if no domain
	 * 			normal handling of 400/404's
	*/
	public function getServerIPs($domain) {
		$domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/ips')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);

	}
	
	/* 
	 * Gets list of vMacs associated with server
	 *
	 * @param 	$domain -> server
	 * @returns object list vMacs valid for this server
	 *
	 * @throws BadMedhodCallException if no domain
	 * 			normal handling of 400/404's
	*/
	//	get /dedicated/server/{serviceName}/virtualMac
	public function getVmacs($domain) {
		$domain = (string)$domain;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('dedicated/server/' . $domain . '/virtualMac')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);

	}
	
	/* 
	 * Set vMacs associated with a server and IP
	 *
	 * @param 	$domain -> server
	 * @returns object list vMacs valid for this server
	 *
	 * @throws BadMedhodCallException if no domain
	 * 			normal handling of 400/404's
	*/
	// POST	/dedicated/server/{serviceName}/virtualMac
	public function createVmac($domain,$ipaddress,$type,$vmname) {
		$domain 	=(string)$domain;
		$ipaddress	=(string)$ipaddress;
		$type		=(string)$type;
		$vmname		=(string)$vmname;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$ipaddress)
            throw new BadMethodCallException('Parameter $ipaddress is missing.');
        if (!$type)
            throw new BadMethodCallException('Parameter $type is missing.');
		if (($type != "ovh") && ($type != "vmware")) 
			throw new BadMethodCallException('Parameter $type must be "ovh" or "vmware"');
        if (!$vmname)
            throw new BadMethodCallException('Parameter $vmname is missing.');
        
		$payload=array(
			"ipAddress"=>$ipaddress,
			"virtualMachineName"=>$vmname
  		);
		
        try {
            $r = $this->get('dedicated/server/' . $domain . '/virtualMac/' . $ip , array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);

	}
	
	/* 
	 * Gets list of vMacs associated with server
	 *
	 * @param 	$domain -> server
	 * @returns object list vMacs valid for this server
	 *
	 * @throws BadMedhodCallException if no domain
	 * 			normal handling of 400/404's
	*/
	// GET	/dedicated/server/{serviceName}/virtualMac/{virtualmac}
	public function getVmacProperties($domain,$vmac) {
		$domain = (string)$domain;
        $vmac 	= (string)$vmac;
		 
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$vmac)
            throw new BadMethodCallException('Parameter $vmac is missing.');
			
        try {
            $r = $this->get('dedicated/server/' . $domain . '/virtualMac/'. $vmac)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	}
	
	/*
	* Get list of IPs addresses assigned to vMAC 
	*
	* @param $domain ->server
	* @param $vmac -> vmac
	*
	* @returns array of IPv4s assigned 
	*/
	//	/dedicated/server/{serviceName}/virtualMac/{virtualmac}/virtualAddress
	public function getVmacIPAddresses($domain,$vmac) {
		$domain = (string)$domain;
        $vmac 	= (string)$vmac;
		
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$vmac)
            throw new BadMethodCallException('Parameter $vmac is missing.');
			
        try {
            $r = $this->get('dedicated/server/' . $domain . '/virtualMac/'. $vmac. '/virtualAddress')->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	}
	
	/*
	* add an IP addresses to vMAC 
	*
	* @param $domain -> server to add to
	* @param $vmac	 -> vmac being added to
	* @param $ip 		-> IPv4 being added
	* @param $vmname	-> vmname to associate
	*
	* @returns task
	*/
//	POST /dedicated/server/{serviceName}/virtualMac/{virtualmac}/virtualAddress
	public function setVmacIPAddresses($domain, $vmac, $ip, $vmname) {
		$domain 	=(string)$domain;
		$vmac		=(string)$vmac;
		$ip			=(string)$ip;
		$vmname		=(string)$vmname;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$ip)
            throw new BadMethodCallException('Parameter $ip is missing.');
        if (!$vmac)
            throw new BadMethodCallException('Parameter $vmac is missing.');
        if (!$vmname)
            throw new BadMethodCallException('Parameter $vmname is missing.');

		$payload=array(
			"ipAddress"=>$ip,
			"virtualMachineName"=>$vmname
  		);
		
        try {
            $r = $this->post('dedicated/server/' . $domain . '/virtualMac/' . $vmac . '/virtualAddress' , array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	
	}
	
	/*
	* delete an IP addresses from vMAC 
	*
	* @param $domain -> server to add to
	* @param $vmac	 -> vmac being added to
	* @param $ip 		-> IPv4 being added
	* @param $vmname	-> vmname to associate
	*
	* @returns task
	*/
//	DELETE /dedicated/server/{serviceName}/virtualMac/{macAddress}/virtualAddress/{ipAddress}
	public function deleteVmacIPAddress($domain, $vmac, $ip) {
		$domain =(string)$domain;
		$vmac	=(string)$vmac;
		$ip		=(string)$ip;
        
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$ip)
            throw new BadMethodCallException('Parameter $ip is missing.');
        if (!$vmac)
            throw new BadMethodCallException('Parameter $vmac is missing.');

		
        try {
            $r = $this->delete('dedicated/server/' . $domain . '/virtualMac/' . $vmac . '/virtualAddress/' . $ip )->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
	
	}
	
}