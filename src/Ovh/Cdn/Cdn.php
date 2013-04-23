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

use Ovh\Common\Exception\BadConstructorCallException;
use CdnClient;


class Cdn
{
    private $sn = null; // service name
    private static $cdnClient = null;


    /**
     * Constructor
     *
     * @param $serviceName
     * @throws \Ovh\Common\Exception\BadConstructorCallException;
     */
    public function __construct($serviceName)
    {
        if (!$serviceName)
            throw new BadConstructorCallException('$serviceName parameter is missing.');
        $this->sn = $serviceName;
    }

    /**
     * Return CDN client
     *
     * @return CdnClient
     */
    private static function getClient()
    {
        if (!self::$cdnClient instanceof CdnClient) {
            self::$cdnClient = new CdnClient();
        }
        ;
        return self::$cdnClient;
    }


    /**
     * Get properties of CDN object
     * @return object properties
     */
    public function getProperties()
    {
        return json_decode(self::getClient()->getProperties($this->sn));
    }

    /**
     * Return the authorized quota
     *
     * @return int quota (in byte)
     */
    public function getAuthorizedQuota()
    {
        return $this->getProperties()->quota;
    }

    /**
     * Return the number of backends used
     *
     * @return int backend used
     */
    public function getUsedBackend()
    {
        return $this->getProperties()->backendUse;
    }

    /**
     * Return the date of the last quota order
     *
     * @return \DateTime of last quota order
     */
    public function getlastQuotaOrder()
    {
        return new \DateTime($this->getProperties()->lastQuotaOrder);
    }

    /**
     * Return offer ref
     *
     * @return string offer reference
     */
    public function getOfferRef()
    {
        return $this->getProperties()->offer;
    }

    /**
     * Return Ip anycast associated with this CDN
     *
     * @return string IP anycast associate to this CDN
     */
    public function getAnycastIp()
    {
        return $this->getProperties()->anycast;
    }

    /**
     * Return the number of autorized backends
     *
     * @return integer
     */
    public function getBackendLimit()
    {
        return $this->getProperties()->backendLimit;
    }

    /**
     * Return number of cache rules autorized by domain
     *
     * @return integer
     */
    public function getcacheRuleLimitPerDomain()
    {
        return $this->getProperties()->cacheRuleLimitPerDomain;
    }

    /**
     * Return domains configurated on this CDN
     *
     * @return array of domains
     */
    public function getDomains()
    {
        return json_decode(self::getClient()->getDomains($this->sn));
    }

    /**
     * Get domain properties
     *
     * @param string $domain
     * @return object properties
     */
    public function getDomainProperties($domain)
    {
        return json_decode(self::getClient()->getDomainProperties($this->sn, $domain));
    }

    /**
     * Get number of cache rules used for a domain
     *
     * @param string $domain
     * @return integer
     */
    public function getDomainCacheRulesUsed($domain)
    {
        return $this->getDomainProperties($domain)->cacheRuleUse;
    }

    /**
     * Return the status of the domain (on | off | error)
     *
     * @param string $domain
     * @return string status
     */
    public function getDomainStatus($domain)
    {
        return $this->getDomainProperties($domain)->status;
    }

    /**
     * Return the Type propertie of the domain $domain
     *
     * @param  string $domain
     * @return string type
     */
    public function getDomainType($domain)
    {
        return $this->getDomainProperties($domain)->type;
    }

    /**
     * Return CNAME associated to domain $domain
     *
     * @param string $domain
     * @return string CNAME
     */
    public function getDomainCname($domain)
    {
        return $this->getDomainProperties($domain)->cname;
    }

    /**
     * Update domain properties
     *
     * @param string $domain
     * @param array $properties
     */
    public function updateDomainProperties($domain, $properties)
    {
        self::getClient()->updateDomainProperties($this->sn, $domain, $properties);
    }

    /**
     * Update domain status
     *
     * @param string $domain
     * @param string $status (on|off|error)
     */
    public function updateDomainStatus($domain, $status)
    {
        $this->updateDomainProperties($domain, array('status' => $status));
    }

    /**
     * Add a domain to CDN
     *
     * @param $domain
     * @return object response
     */
    public function addDomain($domain)
    {
        return json_decode(self::getClient()->addDomain($this->sn, $domain));
    }

    /**
     * Delete a domain from CDN
     *
     * @param string $domain
     * @return string domain
     */
    public function deleteDomain($domain)
    {
        return json_decode(self::getClient()->deleteDomain($this->sn, $domain));
    }

    /**
     * Get IP backends associated with domain $domain
     *
     * @param string $domain
     * @return array of string backend
     */
    public function getDomainBackends($domain)
    {
        return json_decode(self::getClient()->getDomainBackends($this->sn, $domain));
    }

    /**
     * Add backend (IPv4) to domain $domain
     *
     * @param string $domain
     * @param string $backend (IPv4)
     * @return string IPv4
     */
    public function addDomainBackend($domain, $backend)
    {
        return json_decode(self::getClient()->addDomainBackend($this->sn, $domain, $backend));
    }

    /**
     * Return backend properties (for the moment only IP ?? wtf)
     *
     * @param string $domain
     * @param string $backend (IPv4)
     * @return object
     */
    public function getDomainBackendProperties($domain, $backend)
    {
        return json_decode(self::getClient()->getDomainBackendProperties($this->sn, $domain, $backend));
    }

    /**
     * Delete backend for domain $domain
     *
     * @param string $domain
     * @param string $backend (IPv4)
     * @return string IPv4
     */
    public function deleteDomainBackend($domain, $backend)
    {
        return json_decode(self::getClient()->delteDomainBackend($this->sn, $domain, $backend));
    }

    /**
     * Return cache rules for domain $domain
     *
     * @param string $domain
     * @return array og int (rule id)
     */
    public function getDomainCacheRules($domain)
    {
        return json_decode(self::getClient()->getDomainCacheRules($this->sn, $domain));
    }

    /**
     * Add a cache rule to domain $domain
     *
     * @param string $domain
     * @param array $rule (required keys are 'cacheType','ttl','fileMatch','fileType' see official doc at https://api.ovh.com/console/#/cdn/%7BserviceName%7D/domains/%7Bdomain%7D/cacheRules#POST)
     * @return mixed
     */
    public function addDomainCacheRule($domain, $rule)
    {
        return json_decode(self::getClient()->addDomainCacheRule($this->sn, $domain, $rule));
    }


    /**
     * Get proprieties of cache rule $ruleId og domain $domain
     *
     * @param string $domain
     * @param int $ruleId
     * @return object
     */
    public function getDomainCacheRuleProperties($domain, $ruleId)
    {
        return json_decode(self::getClient()->getDomainCacheRuleProperties($this->sn, $domain, $ruleId));
    }

    /**
     * Update CacheRule $ruleId of domain $domain
     *
     * @param string $domain
     * @param int $ruleId
     * @param array $rule . Requiered keys 'status': 'on' | 'off', 'ttl' : integer
     */
    public function updateDomainCacheRule($domain, $ruleId, $rule)
    {
        self::getClient()->updateDomainCacheRule($this->sn, $domain, $ruleId, $rule);
    }

    /**
     * Delete cache rule $ruleId of domain $domain
     *
     * @param string $domain
     * @param  integer $ruleId
     * @return object Task
     */
    public function deleteDomainCacheRule($domain, $ruleId)
    {
        return json_decode(self::getClient()->deleteDomainCacheRule($this->sn, $domain, $ruleId));
    }

    /**
     * Flush cache rule $ruleId of domain $domain
     *
     * @param string $domain
     * @param integer $ruleId
     * @return object Task
     */
    public function flushDomainCacheRule($domain, $ruleId)
    {
        return json_decode(self::getClient()->flushDomainCacheRule($this->sn, $domain, $ruleId));
    }

    /**
     * Flush all cache for domain $domain
     *
     * @param string $domain
     * @return object Task
     */
    public function flushDomainCache($domain)
    {
        return json_decode(self::getClient()->flushDomainCache($this->sn, $domain));
    }

    /**
     * Return array of taskId associated with rule $ruleId of domain $domain
     *
     * @param string $domain
     * @param int $ruleId
     * @return array of task id (int)
     */
    public function getDomainCacheRuleTasks($domain, $ruleId)
    {
        return json_decode(self::getClient()->getDomainCacheRuleTasks($this->sn, $domain, $ruleId));
    }

    /**
     * Return properties of task $taskId associated with rule $ruleId of domain $domain
     *
     * @param $domain
     * @param $ruleId
     * @param $taskId
     * @return mixed
     */
    public function getDomainCacheRuleTaskProperties($domain, $ruleId, $taskId)
    {
        return json_decode(self::getClient()->getDomainCacheRuleTaskProperties($this->sn, $domain, $ruleId, $taskId));
    }

    /**
     * Return CDN statistics for domain $domain
     *
     * @param string $domain
     * @param string $period ("day" | "month" | "week")
     * @param string $value ("bandwidth" | "request")
     * @param $type ("backend" | "cdn")
     * @return array of object statistics
     */
    public function getDomainStatistics($domain, $period, $value, $type)
    {
        return json_decode(self::getClient()->getDomainStatistics($this->sn, $domain, $period, $value, $type));
    }

    /**
     * Return domain tasks
     *
     * @param string $domain
     * @return array of tasks
     */
    public function getDomainTasks($domain)
    {
        return json_decode(self::getClient()->getDomainTasks($this->sn, $domain));
    }

    /**
     * Return properties of task $taskId associated with domain $domain
     *
     * @param string $domain
     * @param integer $taskId
     * @return object property
     */
    public function hetDomainTaskProperties($domain, $taskId)
    {
        return json_decode(self::getClient()->getDomainTaskProperties($this->sn, $domain, $taskId));
    }

    /**
     * Order backend and return a billing order object
     *
     * @param int $nbBackend : number of backend needed
     * @param int $duration : durtaion (in month)
     * @return object billing order
     */
    public function orderBackend($nbBackend, $duration)
    {
        return json_decode(self::getClient()->orderBackend($this->sn, $nbBackend, $duration));
    }

    /**
     * Order cache rules and return a billing order object
     *
     * @param int $nbCacheRule : number of backend needed
     * @param int $duration : durtaion (in month)
     * @return object billing order
     */
    public function orderCacheRule($nbCacheRule, $duration)
    {
        return json_decode(self::getClient()->orderCacheRule($this->sn, $nbCacheRule, $duration));
    }

    /**
     * Order quota and return a billing order object
     *
     * @param $quota
     * @return object billing order
     */
    public function orderQuota($quota){
        return json_decode(self::getClient()->orderQuota($this->sn, $quota));
    }

    /**
     * Return array of quota object
     *
     * @param string $period ("day" or "week" or "month")
     * @return array of object quota
     */
    public function getQuota($period){
        return json_decode(self::getClient()->getQuota($this->sn, $period));
    }


}
