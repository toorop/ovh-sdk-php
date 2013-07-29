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


class serverClient extends AbstractClient
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
     * (des)activate monitoring
     *
     * @param string $domain
     * @param boolean $enable
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function setMonitoring($domain, $enable)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain = (string)$domain;
        if (!$enable)
            throw new BadMethodCallException('Parameter enable is missing.');
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
     * @param int $netbootId
     * @return \Guzzle\Http\EntityBodyInterface|string
     * @throws Exception\ServerException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function setNetboot($domain, $netbootId)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $domain = (string)$domain;
        if (!$netbootId)
            throw new BadMethodCallException('Parameter $netbootId is missing.');
        $netbootId = intval($netbootId);
        $payload = array('netbootId' => $netbootId);
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
            $r = $this->get('server/dedicated/' . $domain . '/task/' . $taskId)->send();
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

}