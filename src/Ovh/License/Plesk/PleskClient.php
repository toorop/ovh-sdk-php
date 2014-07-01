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


namespace Ovh\Vrack;

#use Guzzle\Http\Exception\ClientErrorResponseException;

#use Guzzle\Http\Exception\BadResponseException;
#use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;

#use Ovh\Common\Exception\NotImplementedYetException;

//use Ovh\Vps\Exception\VpsNotFoundException;
use Ovh\Vrack\Exception\VrackException;


class VrackClient extends AbstractClient
{

    /**
     * Get properties
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\VrackException
     * @throws Exception\VrackNotFoundException
     */
    public function getProperties($domain)
    {
        try {
            $r = $this->get('vrack/' . $domain)->send();
        } catch (\Exception $e) {
            throw new VrackException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
	/*********** DedicatedServer ***********/
	
    /**
     * Get dedicatedServer
     * Ajout by @Thibautg16 le 24/06/2014
     * 
     * @return array of strings
	 *
     * @throws Exception\VrackException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getdedicatedServer($domain){
        $domain  = (string)$domain;
	
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('vrack/'.$domain.'/dedicatedServer')->send();
        } catch (\Exception $e) {
            throw new VrackException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
    /**
     * Get MRTG
     * Ajout by @Thibautg16 le 26/06/2014
     * 
     * @param string $domain
	 * @param string $serveur
     * @param string $period
     * @param string $type
     * 
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\VrackException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getMrtg($domain, $serveur, $period='daily', $type='traffic:download'){
        $domain  = (string)$domain;
		$serveur = (string)$serveur;
        $period  = (string)$period;
        $type    = (string)$type;
		
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('vrack/'.$domain.'/dedicatedServer/'.$serveur.'/mrtg?period='.$period.'&type='.$type)->send();
        } catch (\Exception $e) {
            throw new VrackException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
	/*********** DedicatedCloud ***********/
	/**
     * Get dedicatedCloud
     * Ajout by @Thibautg16 le 24/06/2014
     * 
     * @return array of strings
	 *
     * @throws Exception\VrackException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getdedicatedCloud($domain){
        $domain  = (string)$domain;
	
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('vrack/'.$domain.'/dedicatedCloud')->send();
        } catch (\Exception $e) {
            throw new VrackException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
	/*********** Ip ***********/
	/**
     * Get ip
     * Ajout by @Thibautg16 le 24/06/2014
     * 
     * @return array of strings
	 *
     * @throws Exception\VrackException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getIp($domain){
        $domain  = (string)$domain;
	
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        
        try {
            $r = $this->get('vrack/'.$domain.'/ip')->send();
        } catch (\Exception $e) {
            throw new VrackException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
}