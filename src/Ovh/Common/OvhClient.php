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
 *
 * 2014-06-30 - Slartibardfast - extend for /dedicated/nasha and /ip
 */

namespace Ovh\Common;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;

class OvhClient extends AbstractClient {

	/**
	 * Return Dedicated Server list
	 *
	 * @return mixed
	 */
	public function getDedicatedServerList(){
		$request = $this->get('dedicated/server');
		$response=$request->send();
		return $response->getBody(true);
	}
	
	/**
	 * Return Vrack list
	 *
	 * @return mixed
	 */
	public function getVrackList(){
		return $this->get('vrack')->send()->getBody(true);
	}
	

    /**
     * Return VPS list
     *
     * @return mixed
     */
    public function getVpsList(){
        $request = $this->get('vps');
        $response=$request->send();
        return $response->getBody(true);
    }

    /**
     * Get XDSL Services
     *
     * @return json encoded string
     */
    public function getXdslServices(){
        return $this->get('xdsl')->send()->getBody(true);
    }

    /**
     * Get SMS Services
     *
     * @return string (json encoded)
     */
    public function getSmsServices(){
        return $this->get('sms')->send()->getBody(true);
    }

    /**
     * Get Telephony Services
     *
     * @return string (json encoded)
     */
    public function getTelephonyServices(){
        return $this->get('telephony')->send()->getBody(true);
    }


    /**
     * Get CDN services
     *
     * @return string (json encoded)
     */
    public function getCdnServices(){
        return $this->get('cdn')->send()->getBody(true);
    }

    /**
     * Get available POP
     * @return string (json econded)
     */
    public function getCdnPops(){
        return $this->get('cdn/pops')->send()->getBody(true);
    }

    /**
     * Get POP details/info
     *
     * @param string $pop : pop name
     * @return string (json encoded)
     * @throws Exception\BadMethodCallException
     */
    public function getCdnPopDetails($pop){
        if (!$pop)
            throw new BadMethodCallException('Parameter $pop is missing.');
        return $this->get('cdn/pops/'.$pop)->send()->getBody(true);
    }

    /**
     * Get Cloud passports
     *
     * @return string passports (json encoded)
     */
    public function getCloudPassports()
    {
       return $this->get('cloud')->send()->getBody(true);
    }

	/* 
	* Get list of nasha devices
	*
	* returns list of nasha assoc with the account
	*/
	public function getNashaList()
	{
		return $this->get('dedicated/nasha')->send()->getBody(true);
	}
	
	/*
	 * get list of IPs, subject to restricitons
	 * 1. associated with specific server
	 * 2. a specific IPblock
	 * 3. a specific type of IP.
	 *
	 * arguments are positional, but cumulatie
	 *
	 * returns list of IPs meeting criteria
	*/
	public function getIPsList($serverdomain="", $ipblock="", $type="")
	{
		if ($type!= "") {
			switch ($type) {
				case "cdn":
				case "dedicated":
				case "failover":
				case "hosted_ssl":
				case "loadBalancing":
				case "mail":
				case "pcc":
				case "pci":
				case "private":
				case "vpn":
				case "vps":
				case "vrack":
				case "xdsl":
					break;
				default:
					throw new InvalidArgumentException('Parameter $type is invalid.');
			}
		}

		$qualifier = "";
		if ($serverdomain!="") {
			$qualifier="routedTo.serviceName=$serverdomain";
		}
		if ($ipblock!="") {
			if (isset($qualifier)) {
				$qualifier .= "&";
			}
			$qualifier .= "ip=".urlencode($ipblock);
		}
		if ($type!="")
		{
			if (isset($qualifier)) {
				$qualifier .= "&";
			}
			$qualifier .= "type=$type";
		}
		if ($qualifier != "") {
			$qualifier = "?$qualifier";
		}
		
		return $this->get("ip$qualifier")->send()->getBody(true);
	}
	
	
	/*
	 * GetCpanelList - retrieves list of CPanel licenses
	 *
	 * @return json string[]
	*/
	public function getCpanelList()
	{
		return $this->get('license/cpanel')->send()->getBody(true);
	}

	/*
	 * getPleskList - retrieves list of Plesk licenses
	 *
	 * @return json string[]
	*/
	public function getPleskList()
	{
		return $this->get('license/plesk')->send()->getBody(true);
	}
	
	/*
	 * getDirectadminList - retrieves list of DirectAdmin licenses
	 *
	 * @return json string[]
	*/
	public function getDirectadminList()
	{
		return $this->get('license/directadmin')->send()->getBody(true);
	}
	
	/*
	 * getVirtuozzoList - retrieves list of Virtuozzo licenses
	 *
	 * @return json string[]
	*/
	public function getVirtuozzoList()
	{
		return $this->get('license/virtuozzo')->send()->getBody(true);
	}
	
	/*
	 * GetWindowsList - retrieves list of Windows licenses
	 *
	 * @return json string[]
	*/
	public function getWindowsList()
	{
		return $this->get('license/windows')->send()->getBody(true);
	}
	
	/*
	 * GetWorklightList - retrieves list of Worklight licenses
	 *
	 * @return json string[]
	*/
	public function GetWorklightList()
	{
		return $this->get('license/worklight')->send()->getBody(true);
	}	
}


