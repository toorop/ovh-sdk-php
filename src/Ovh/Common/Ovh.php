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
 *
 * 2014-06-30 - Slartibardfast - extend for /dedicated/nasha and /ip
 */

namespace Ovh\Common;

use Guzzle\Http\Client;

use Ovh\Dedicated\Server\Server;		// extended with 0.1.2
use Ovh\Cdn\Cdn;
use Ovh\Common\Auth\Keyring;
use Ovh\Common\OvhClient;
use Ovh\Sms\Sms;
use Ovh\Telephony\Telephony;
use Ovh\Vps\Vps;
use Ovh\Vrack\Vrack;
use Ovh\Xdsl\Xdsl;
use Ovh\Cloud\Cloud;

// New with 0.1.2 -- tentative numbering
use Ovh\Ip\Ip;
use Ovh\Dedicated\Nasha\Nasha;
use Ovh\License\Cpanel;
use Ovh\License\Directadmin;
use Ovh\License\Plesk;
use Ovh\License\Virtuozzo;
use Ovh\License\Windows;
Use Ovh\License\Worklight;

class Ovh
{
    // Version
    const VERSION = '0.1.2';

    // Client
    private static $ovhClient = null;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        // Populate keyring
        Keyring::setAppKey($config['AK']); // Application Key
        Keyring::setAppSecret($config['AS']); // Application Secret
        Keyring::setConsumerKey($config['CK']); // Consumer Key
        // Backward compatibility
        if (array_key_exists('RG', $config)) {
            keyring::setAppUrlRegion($config['RG']); // Region
        } else {
            keyring::setAppUrlRegion("FR");
        }
    }

    /**
     *
     *          Common
     *
     */

    /**
     * Common client (for no specific task)
     *
     * @return null|OvhClient
     */
    private static function getOvhClient()
    {
        if (!self::$ovhClient instanceof OvhClient) {
            self::$ovhClient = new OvhClient();
        };
        return self::$ovhClient;
    }


    /**
     *
     *          Dedicated Server
     *
     */


    /**
     * Return list of Dedicated Servers owned by user
     *
     * @return mixed
     */
    public function getDedicatedServerList()
    {
        return json_decode(self::getOvhClient()->getDedicatedServerList());
    }

    /**
     * Return a Dedicated Server object
     *
     * @param $domain
     * @return \Ovh\Dedicated\Server\Server
     */
    public function getDedicatedServer($domain)
    {
        return new Server($domain);
    }


    /**
     * Return list of Vrack owned by user
     *
     * @return mixed
     */
    public function getVrackList()
    {
        return json_decode(self::getOvhClient()->getVrackList());
    }


    /**
     * Return a Vrack object
     *
     * @param $domain
     * @return \Ovh\Vrack\Vrack
     */
    public function getVrack($domain)
    {
        return new Vrack($domain);
    }


    /**
     *
     *          VPS
     *
     */


    /**
     * Return list of VPS owned by user
     *
     * @return mixed
     */
    public function getVpsList()
    {
        return json_decode(self::getOvhClient()->getVpsList());
    }

    /**
     * Return a VPS object
     *
     * @param $domain
     * @return \Ovh\Vps\Vps
     */
    public function getVps($domain)
    {
        return new Vps($domain);
    }


    /**
     *
     *          XDSL
     *
     */

    /**
     * Return xdsl subscription/service
     *
     * @return array
     */
    public function getXdslServices()
    {
        return json_decode(self::getOvhClient()->getXdslServices());
    }

    /**
     * @param $serviceId
     * @return \Ovh\Xdsl\Xdsl
     */
    public function getXdsl($serviceId)
    {
        return new Xdsl($serviceId);
    }

    /**
     *
     *      SMS
     *
     */
    /**
     * Return SMS subscriptions/Service (list of 'domains')
     * @return array
     */
    public function getSmsServices()
    {
        return json_decode(self::getOvhClient()->getSmsServices());
    }

    /**
     * Get new SMS object
     *
     * @param $domain
     * @return Sms
     */
    public function getSms($domain)
    {
        return new Sms($domain);
    }

    /**
     *
     *      Telephony
     *
     */
    /**
     * Return Tekphony subscriptions/Service (list of 'domains')
     * @return array
     */
    public function getTelephonyServices()
    {
        return json_decode(self::getOvhClient()->getTelephonyServices());
    }

    /**
     * Get new Telephony object
     * @param  $domain
     * @return Telephony
     */
    public function getTelephony($domain)
    {
        return new Telephony($domain);
    }


    /**
     *
     *      CDN
     *
     */

    /**
     * Get CDN services
     *
     * @return array
     */
    public function getCdnServices()
    {
        return json_decode(self::getOvhClient()->getCdnServices());
    }

    /**
     * Get available POPs
     *
     * @return array
     */
    public function getCdnPops()
    {
        return json_decode(self::getOvhClient()->getCdnPops());
    }

    /**
     * get CDN pop details
     *
     * @param string $pop (as returned by getCdnPops)
     * @return object
     */
    public function getCdnPopDetails($pop)
    {
        return json_decode(self::getOvhClient()->getCdnPopDetails($pop));
    }

    /**
     * Return a CDN object
     *
     * @param $serviceName
     * @return object Cdn
     */
    public function getCdn($serviceName)
    {
        return new Cdn($serviceName);
    }


    /**
     *
     *      CLOUD
     * PCA : public cloud archive : http://www.ovh.com/fr/cloud/archives/
     * PCS : Public cloud storage : http://www.ovh.com/fr/cloud/stockage/
     *
     */

    /**
     * Return Cloud Services (cloud passport)
     *
     * @return array of services (passport)
     */
    public function getCloudPassports()
    {
        return json_decode(self::getOvhClient()->getCloudPassports());
    }

    /**
     * Return a cloud instance
     *
     * @param string $passport (OVH cloud passport)
     * @return Cloud instance
     */
    public function getCloud($passport)
    {
        return new Cloud($passport);
    }

// IP heirarchy
	/*
     * Return list of IP blocks owned by user
	 * Optional Args (positional)
	 * 1. specific server
	 * 2. specific ipblock
	 * 3. specific type of IP (type validated inside client)
     *
     * @return mixed
     */
    public function getIPsList($domain="", $ipblock="", $type="")
    {
        return json_decode(self::getOvhClient()->getIPsList($domain, $ipblock, $type));
    }

	/*
     * Instaniate new IP Client, and Return structure of IP block owned by user
     *
     * @return mixed
     */
	public function getIp($domain)
    {
        return new Ip($domain);
    }
	
	
// Dedicated/Nasha heirarchy
	/*
     * Return list of high availability NAS owned by user
     *
     * @return mixed
     */
    public function getNashaList()
    {
        return json_decode(self::getOvhClient()->getnashaList());
    }
	
	/*
     * Instantiate new nasha object and return structure of specific hanas owned by user
     *
     * @return mixed
     */
	public function getnasha($domain)
    {
        return new Nasha($domain);
    }
	
// License/Cpanel heirarchy

	/*
	 * returns list of Cpanel licenses associatd with user account
	 *
	 * @return json string[]
	*/
	public function getCpanelList()
	{
		return json_decode(self::getOvhClient()->getCpanelList());
	}
	

	/*
	 * Instantiates a mew cPanel licence object and returns an object of the specific id
	 *
	 * returns Object
	*/
	public function getCpanelLicence($domain)
	{
		return new Cpanel($domain);
	}

	// License/Directadmin heirarchy

	/*
	 * returns list of Directadmin licenses associatd with user account
	 *
	 * @return json string[]
	*/
	public function getDirectadminList()
	{
		return json_decode(self::getOvhClient()->getDirectadminList());
	}
	
	/*
	 * Instantiates a new DirectAdmin licence object and returns an object of the specific id
	 *
	 * returns Object
	*/
	public function getDirectadminLicence($domain)
	{
		return new Cpanel($domain);
	}
	
	// License/Plesk heirarchy

	/*
	 * returns list of Plesk licenses associatd with user account
	 *
	 * @return json string[]
	*/
	public function getPleskList()
	{
		return json_decode(self::getOvhClient()->getPleskList());
	}
	
	/*
	 * Instantiates a new Plesk licence object and returns an object of the specific id
	 *
	 * returns Object
	*/
	public function getPleskLicence($domain)
	{
		return new Plesk($domain);
	}
	
	// License/Virtuozzo heirarchy

	/*
	 * returns list of Virtuozzo licenses associatd with user account
	 *
	 * @return json string[]
	*/
	public function getVirtuozzoList()
	{
		return json_decode(self::getOvhClient()->getVirtuozzoList());
	}
	
	/*
	 * Instantiates a new Virtuozzo licence object and returns an object of the specific id
	 *
	 * returns Object
	*/
	public function getVirtuozzoLicence($domain)
	{
		return new Virtuozzo($domain);
	}

	// License/Windows heirarchy

	/*
	 * returns list of Windows licenses associatd with user account
	 *
	 * @return json string[]
	*/
	public function getWindowsList()
	{
		return json_decode(self::getOvhClient()->getWindowsList());
	}
	
	/*
	 * Instantiates a new Windows licence object and returns an object of the specific id
	 *
	 * returns Object
	*/
	public function getWindowsLicence($domain)
	{
		return new Windows($domain);
	}

	// License/Worklight heirarchy

	/*
	 * returns list of Worklight licenses associatd with user account
	 *
	 * @return json string[]
	*/
	public function getWorklightList()
	{
		return json_decode(self::getOvhClient()->getWorklightList());
	}
	
	/*
	 * Instantiates a new Worklight licence object and returns an object of the specific id
	 *
	 * returns Object
	*/
	public function getWorklightLicence($domain)
	{
		return new Worklight($domain);
	}

}
