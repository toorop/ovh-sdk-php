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

namespace Ovh\Common;

use Ovh\Common\Auth\Keyring;

use Guzzle\Http\Client;

use Ovh\Common\OvhClient;

class Ovh
{
    // Version
    const VERSION = '0.1.1';


    // Clients
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
        return new \Ovh\Vps\Vps($domain);
    }


    /**
     * Common client (for no specific task)
     *
     * @return null|OvhClient
     */
    private static function getOvhClient(){
        if(self::$ovhClient instanceof OvhClient)
            return self::$ovhClient;
        else return new OvhClient();
    }
}