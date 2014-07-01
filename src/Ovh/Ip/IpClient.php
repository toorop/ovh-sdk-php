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

/*
 * cloned from VPS - Slartibardfast - 2014-06-30
*/

namespace Ovh\Ip;

use Guzzle\Http\Message\Response;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;

use Ovh\Ip\Exception\IpException;

class IPClient extends AbstractClient
{

    /**
     * Get IPBlockProperties
     *
     * @param string $ipBlock
     * @return string Json
     * @throws Exception\IpException
     * @throws Exception\IpNotFoundException
     */ 
	public function getIPBlockProperties($ipblock)
    {
        try {
            $r = $this->get('ip/' . urlencode($ipblock))->send();
        } catch (\Exception $e) {
            throw new IpException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
    /**
     * set IPBlockProperties
     *
     * @param string $description
     * @return string Json
     * @throws Exception\IpException
     * @throws Exception\IpNotFoundException

only seems to return null on success

     */ 
    public function setIPBlockProperties($ipblock,$description)
    {
		$payload = array(
			'description' => $description
		 );
	
        try {
            $r = $this->put('ip/' . urlencode($ipblock), array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new IpException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
	
    /**
     * Get IPBlockArp
     *
     * @param string $ipBlock
     * @return string Json
     * @throws Exception\IpException
     * @throws Exception\IpNotFoundException
     */ 
	public function getIPBlockArp($ipblock)
    {
        try {
            $r = $this->get('ip/' . urlencode($ipblock) . '/arp')->send();
        } catch (\Exception $e) {
            throw new IpException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
    /**
     * Get IPBlockedInfo - returns information about the specific IP in the IPBlock
     *
     * @param string $ipBlock
	 * @param string $ip 
     * @return string Json
     * @throws Exception\IpException
     * @throws Exception\IpNotFoundException
     */ 
	public function getIPBlockedInfo($ipblock, $ip)
    {
        try {
            $r = $this->get('ip/' . urlencode($ipblock) . '/arp/' . $ip)->send();
        } catch (\Exception $e) {
            throw new IpException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
}