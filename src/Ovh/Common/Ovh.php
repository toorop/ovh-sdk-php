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
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        // Populate keyring
        Keyring::setAppKey($config['AK']);
        Keyring::setAppSecret($config['AS']);
        Keyring::setConsumerKey($config['CK']);
        // Get credential
        //$this->getCredential();

        // Get VPS
        #$this->getVps();
    }

    private function getCredential()
    {
        $client = new Client('https://api.ovh.com');
        $headers = array('X-Ovh-Application' => Keyring::getAppKey(), 'Content-type' => 'application/json');
        $request = $client->post('/1.0/auth/credential', $headers, '{"accessRules":[{"method":"GET","path":"/*"},{"method":"POST","path":"/*"},{"method":"PUT","path":"/*"},{"method":"DELETE","path":"/*"}]}');

        $response = $request->send();

        #echo $response->getBody();

        #echo $response->getRawHeaders();

        $data = $response->json();
        var_dump($data);

        $validationUrl=$data['validationUrl'];
        #$this->config['CK']=$data['consumerKey'];

    }


    /**
     * Return list of VPS owned by user
     *
     * @return mixed
     */
    public function getVpsList()
    {
        return self::getOvhClient()->getVpsList();
    }


    public function getVps($domain){
        return new \Ovh\Vps\Vps($domain);
    }



    # Get clients

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