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

#use Guzzle\Http\Exception\ClientErrorResponseException;
#use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Url;
use Guzzle\Http\Message\Request;


class AbstractClient extends Client
{

    public function __construct()
    {
        parent::__construct('https://api.ovh.com/1.0/');
    }


    /**
     *  Override ti add OVH auth
     *
     * @param $method
     * @param null $uri
     * @param null $headers
     * @param null $body
     * @return \Guzzle\Http\Message\Request
     */
    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null)
    {
        #$request=new Request();


        $request = parent::createRequest($method, $uri, $headers, $body);
        #$r=$request->getPath();
        #print($r);
        #die();
        // Add OVH auth headers
        $hTimestamp = $this->getTimestamp();
        # SIG = "$1$" + sha1.hex(AS+"+"+CK+"+"+METHOD+"+"+QUERY+"+"+BODY +"+"+TSTAMP)
        #var_dump($body);
        #print $body;
        #die();
        if ($method == "POST")
            $baseSig = Keyring::getAppSecret() . '+' . Keyring::getConsumerKey() . '+' . $method . '+' . $request->getUrl() . '+' . '' . '+' . $hTimestamp;
        else
            $baseSig = Keyring::getAppSecret() . '+' . Keyring::getConsumerKey() . '+' . $method . '+' . $request->getUrl() . '+' . $body . '+' . $hTimestamp;
        #
        #print $baseSig . "\n";
        $sig = '$1$' . sha1($baseSig);
        #print $sig . "\n";
        $request->addHeader('X-Ovh-Application', Keyring::getAppKey());
        $request->addHeader('X-Ovh-Timestamp', $hTimestamp);
        $request->addHeader('X-Ovh-Consumer', Keyring::getConsumerKey());
        $request->addHeader('X-Ovh-Signature', $sig);
        return $request;
    }


    /**
     * @todo May be usefull to implement for time derive beetween OVH and us
     *
     *
     * @return int
     */
    protected function getTimestamp()
    {
        return time();
    }


}
