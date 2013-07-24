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

namespace Ovh\Common;

use Guzzle\Http\Client;

use Ovh\Dedicated\Server\Server;
use Ovh\Cdn\Cdn;
use Ovh\Common\Auth\Keyring;
use Ovh\Common\OvhClient;
use Ovh\Sms\Sms;
use Ovh\Vps\Vps;
use Ovh\Xdsl\Xdsl;
use Ovh\Cloud\Cloud;


class Ovh
{
    // Version
    const VERSION = '0.1.1';

    // Client
    private static $ovhClient=null;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        // Populate keyring
        Keyring::setAppKey($config['AK']);
        Keyring::setAppSecret($config['AS']);
        Keyring::setConsumerKey($config['CK']);
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
    private static function getOvhClient(){
        if (!self::$ovhClient instanceof OvhClient){
            self::$ovhClient=new OvhClient();
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
	public function getDedicatedServer($domain){
		return new Server($domain);
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
    public function getVps($domain){
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
    public function getXdslServices(){
        return json_decode(self::getOvhClient()->getXdslServices());
    }

    /**
     * @param $serviceId
     * @return \Ovh\Xdsl\Xdsl
     */
    public function getXdsl($serviceId){
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
    public function getSmsServices(){
        return json_decode(self::getOvhClient()->getSmsServices());
    }

    /**
     * Get new SMS object
     *
     * @param $domain
     * @return Sms
     */
    public function getSms($domain){
        return new Sms($domain);
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
    public function getCdnServices(){
        return json_decode(self::getOvhClient()->getCdnServices());
    }

    /**
     * Get available POPs
     *
     * @return array
     */
    public function getCdnPops(){
        return json_decode(self::getOvhClient()->getCdnPops());
    }

    /**
     * get CDN pop details
     *
     * @param string $pop (as returned by getCdnPops)
     * @return object
     */
    public function getCdnPopDetails($pop){
        return json_decode(self::getOvhClient()->getCdnPopDetails($pop));
    }

    /**
     * Return a CDN object
     *
     * @param $serviceName
     * @return object Cdn
     */
    public function getCdn($serviceName){
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
    public function getCloud($passport){
        return new Cloud($passport);
    }


}