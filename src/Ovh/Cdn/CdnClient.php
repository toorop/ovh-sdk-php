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

namespace Ovh\Cdn;

use Ovh\Cdn\Exception\CdnException;
use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Xdsl\Exception\XdslBaseException;

class CdnClient extends AbstractClient
{

    /**
     * @param string $sn service name
     * @return string (json encoded)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getProperties($sn)
    {
        if (!$sn)
            throw new BadMethodCallException('Missing parameter $sn.');
        try {
            $r = $this->get('cdn/' . $sn)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name
     * @return string json encoded
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomains($sn)
    {
        if (!$sn)
            throw new BadMethodCallException('Missing parameter $sn.');
        try {
            $r = $this->get('cdn/' . $sn . '/domains')->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn (CDN service name)
     * @param string $domain
     * @return string (json encoded object)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainProperties($sn, $domain)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Add a domain to CDN
     *
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @return string (json encoded object response)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function addDomain($sn, $domain)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $payload = array('domain' => $domain);
        try {
            $r = $this->post('cdn/' . $sn . '/domains', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param array $properties
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function updateDomainProperties($sn, $domain, $properties)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$properties)
            throw new BadMethodCallException('Parameter $properties is missing.');
        if (!is_array($properties))
            throw new BadMethodCallException('Parameter $properties must be an array.');
        // clean array properties
        $validKeys = array('status');
        foreach ($properties as $key => $val) {
            if (!in_array($key, $validKeys)) {
                unset($properties[$key]);
            }
        }
        if (count($properties) == 0)
            throw new BadMethodCallException("Parameter $properties doesn't have any valid properties.");

        // check status
        if (array_key_exists('status', $properties)) {
            if (!in_array($properties['status'], array('on', 'off', 'error')))
                throw new BadMethodCallException("Properties status must be : 'on' or 'off' or 'error'. '" . $properties['status'] . "' given.");
        }
        try {
            $this->put('cdn/' . $sn . '/domains/' . $domain, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($properties))->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain to delete
     * @return string
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function deleteDomain($sn, $domain)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->delete('cdn/' . $sn . '/domains/' . $domain)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @return string (json encoded)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainbackends($sn, $domain)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain . '/backends')->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param string $backend IPv4 of the backend
     * @return string (json encoded)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function addDomainBackend($sn, $domain, $backend)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$backend)
            throw new BadMethodCallException('Parameter $backend is missing.');
        $payload = array('ip' => $backend);
        try {
            $r = $this->post('cdn/' . $sn . '/domains/' . $domain . '/backends', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param string $backend IPv4 of the backend
     * @return string (json encoded)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainBackendProperties($sn, $domain, $backend)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$backend)
            throw new BadMethodCallException('Parameter $backend is missing.');
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain . '/backends/' . $backend)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param string $backend IPv4 of the backend
     * @return string (json encoded)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function delteDomainBackend($sn, $domain, $backend)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$backend)
            throw new BadMethodCallException('Parameter $backend is missing.');
        try {
            $r = $this->delete('cdn/' . $sn . '/domains/' . $domain . '/backends/' . $backend)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @return string (json encoded array)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainCacheRules($sn, $domain)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain . '/cacheRules')->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param  string $domain
     * @param array $rule
     * @return string (json encoded object rule)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function addDomainCacheRule($sn, $domain, $rule)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$rule)
            throw new BadMethodCallException('Parameter $rule is missing.');
        if (!is_array($rule))
            throw new BadMethodCallException('Parameter $rule must be an array.');
        // clean array
        $requiredKeys = array('cacheType', 'ttl', 'fileMatch', 'fileType');
        foreach ($rule as $key => $val) {
            if (!in_array($key, $requiredKeys)) {
                unset($rule[$key]);
            }
        }
        if (count($rule) != 4)
            throw new BadMethodCallException('Parameter $rule must be a array with 4 keys : "cacheType", "ttl", "fileMatch", "fileType"');

        // cacheType
        if (!in_array($rule['cacheType'], array('forceCache', 'noCache')))
            throw new BadMethodCallException('Parameter $rule[\'cacheType\'] must be "forceCache" or "noCcahe" ' . $rule['cacheType'] . ' given');

        // ttl
        $rule['ttl'] = intval($rule['ttl']);

        // fileMatch
        $rule['fileMatch'] = trim($rule['fileMatch']);

        // filtype
        if (!in_array($rule['fileType'], array('folder', 'file', 'extension')))
            throw new BadMethodCallException('Parameter $rule[\'fileType\'] must be "folder" or "file" or "extension" ' . $rule['filtype'] . ' given');

        try {
            $r = $this->post('cdn/' . $sn . '/domains/' . $domain . '/cacheRules', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($rule))->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param integer $ruleId
     * @return string (json encoded object)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainCacheRuleProperties($sn, $domain, $ruleId)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$ruleId)
            throw new BadMethodCallException('Parameter $ruleId is missing.');
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain . '/cacheRules/' . $ruleId)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param integer $ruleId
     * @param array $rule (keys : status, rule ; status : on | off)
     * @return void
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function updateDomainCacheRule($sn, $domain, $ruleId, $rule)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$ruleId)
            throw new BadMethodCallException('Parameter $ruleId is missing.');
        $ruleId = intval($ruleId);
        if (!$rule)
            throw new BadMethodCallException('Parameter $rule is missing.');
        if (!is_array($rule))
            throw new BadMethodCallException('Parameter $rule must be an array.');
        // clean rule API requiered ttl and status to be set
        foreach ($rule as $key => $val) {
            if (!in_array($key, array('ttl', 'status'))) {
                unset($rule[$key]);
            }
        }
        if (count($rule) != 2)
            throw new BadMethodCallException('Parameter $rule must be an array with keys status AND ttl.');
        // ttl
        $rule['ttl'] = intval($rule['ttl']);
        // status
        if (!in_array($rule['status'], array('on', 'off'))) {
            throw new BadMethodCallException('Parameter $rule[\'status\'] must be "on" or "off".');
        }

        try {
            $this->put('cdn/' . $sn . '/domains/' . $domain . '/cacheRules/' . $ruleId, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($rule))->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param integer $ruleId
     * @return string (json encoded object task)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function deleteDomainCacheRule($sn, $domain, $ruleId)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$ruleId)
            throw new BadMethodCallException('Parameter $ruleId is missing.');
        $ruleId = intval($ruleId);
        try {
            $r = $this->delete('cdn/' . $sn . '/domains/' . $domain . '/cacheRules/' . $ruleId)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param int $ruleId
     * @return string (json encoded)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function flushDomainCacheRule($sn, $domain, $ruleId)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$ruleId)
            throw new BadMethodCallException('Parameter $ruleId is missing.');
        $ruleId = intval($ruleId);
        try {
            $r = $this->post('cdn/' . $sn . '/domains/' . $domain . '/cacheRules/' . $ruleId . '/flush')->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @return string
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function flushDomainCache($sn, $domain)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('cdn/' . $sn . '/domains/' . $domain . '/flush')->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param int $ruleId
     * @return string (json encoded array)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainCacheRuleTasks($sn, $domain, $ruleId)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$ruleId)
            throw new BadMethodCallException('Parameter $ruleId is missing.');
        $ruleId = intval($ruleId);
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain . '/cacheRules/' . $ruleId . '/tasks')->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param int $ruleId
     * @param int $taskId
     * @return string (json encoded object)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainCacheRuleTaskProperties($sn, $domain, $ruleId, $taskId)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$ruleId)
            throw new BadMethodCallException('Parameter $ruleId is missing.');
        $ruleId = intval($ruleId);
        if (!$taskId)
            throw new BadMethodCallException('Parameter $taskId is missing.');
        $taskId = intval($taskId);
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain . '/cacheRules/' . $ruleId . '/tasks/' . $taskId)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param string $period ("day" | "month" | "week")
     * @param string $value ("bandwidth" | "request")
     * @param $type ("backend" | "cdn")
     * @return string (json encoded array)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainStatistics($sn, $domain, $period, $value, $type)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$period)
            throw new BadMethodCallException('Parameter $period is missing.');
        if (!in_array($period, array('day', 'week', 'month')))
            throw new BadMethodCallException('Parameter $period must be "day" or "month" or  "week".');
        if (!$value)
            throw new BadMethodCallException('Parameter $value is missing.');
        if (!in_array($value, array('bandwidth', 'request')))
            throw new BadMethodCallException('Parameter $value must be "bandwidth" or "request".');
        if (!$type)
            throw new BadMethodCallException('Parameter $type is missing.');
        if (!in_array($type, array('backend', 'cdn')))
            throw new BadMethodCallException('Parameter $type must be "backend" or "cdn".');
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain . '/statistics?period=' . $period . '&value=' . $value . '&type=' . $type)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @return string (json encoded array)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainTasks($sn, $domain)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain . '/tasks')->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $domain
     * @param int $taskId
     * @return string (json encoded object)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getDomainTaskProperties($sn, $domain, $taskId)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$taskId)
            throw new BadMethodCallException('Parameter $taskId is missing.');
        $taskId = intval($taskId);
        try {
            $r = $this->get('cdn/' . $sn . '/domains/' . $domain . '/tasks/' . $taskId)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param int $nbBackend
     * @param int $duration
     * @return string json object billing order
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function orderBackend($sn, $nbBackend, $duration)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$nbBackend)
            throw new BadMethodCallException('Parameter $nbBackend is missing');
        $nbBackend = intval($nbBackend);
        if (!$duration)
            throw new BadMethodCallException('Parameter $duration is missing');
        $duration = intval($duration);
        try {
            $r = $this->post('cdn/' . $sn . '/orderBackend', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode(array("backend" => $nbBackend, 'duration' => $duration)))->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);

    }


    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param int $nbCacheRule
     * @param int $duration
     * @return string json object billing order
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function orderCacheRule($sn, $nbCacheRule, $duration)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$nbCacheRule)
            throw new BadMethodCallException('Parameter $nbCacheRule is missing');
        $nbCacheRule = intval($nbCacheRule);
        if (!in_array($nbCacheRule, array(100, 1000)))
            throw new BadMethodCallException('Parameter $nbCacheRule must be 100 or 1000');
        if (!$duration)
            throw new BadMethodCallException('Parameter $duration is missing');
        $duration = intval($duration);
        try {
            $r = $this->post('cdn/' . $sn . '/orderCacheRule', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode(array("cacheRule" => $nbCacheRule, 'duration' => $duration)))->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);

    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param int $quota
     * @return string json object billing order
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function orderQuota($sn, $quota)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$quota)
            throw new BadMethodCallException('Parameter $quota is missing');
        $quota = intval($quota);
        try {
            $r = $this->post('cdn/' . $sn . '/orderQuota', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode(array("quota" => $quota)))->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $sn service name (eg cdn-46.105.198.115-97)
     * @param string $period ("day" or "week" or "month")
     * @return string json encoded array of object stat
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\CdnException
     */
    public function getQuota($sn,$period)
    {
        if (!$sn)
            throw new BadMethodCallException('Parameter $sn (service name) is missing');
        if (!$period)
            throw new BadMethodCallException('Parameter $period is missing');
        if(!in_array($period,array('day','week','month')))
            throw new BadMethodCallException('Parameter $period must be "day" oy "week" or "month');
        try {
            $r = $this->get('cdn/' . $sn . '/quota?period='.$period)->send();
        } catch (\Exception $e) {
            throw new CdnException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

}
