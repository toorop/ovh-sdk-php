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

namespace Ovh\Sms;

use Ovh\Common\Exception\BadConstructorCallException;
use Ovh\Sms\SmsClient;

class Sms
{

    private $domain = null;
    private static $smsClient = null;

    /**
     * @param $domain
     *
     * @throws \Ovh\Common\Exception\BadConstructorCallException;
     */
    public function __construct($domain)
    {
        if (!$domain)
            throw new BadConstructorCallException('$domain parameter is missing.');
        $this->setDomain($domain);
    }

    /**
     * Return SMS client
     *
     * @return SmsClient
     */
    private static function getClient()
    {
        if (self::$smsClient instanceof SmsClient)
            return self::$smsClient;
        return new SmsClient();
    }

    /**
     * Set domain
     *
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Get domain
     *
     * @return null | string domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    ###
    # Mains methods from OVH Rest API
    ##

    /**
     * Get SMS object properties
     *
     * @return object
     */
    public function getProperties()
    {
        return json_decode(self::getClient()->getProperties($this->domain));
    }

    /**
     * Get numbers blacklisted associated to the SMS account
     *
     * @return object
     */
    public function getBlacklists()
    {
        return json_decode(self::getClient()->getBlackLists($this->domain));
    }

    /**
     * Get SMS sent associated to the SMS account
     *
     * @return array
     */
    public function getHistories()
    {
        return json_decode(self::getClient()->getHistories($this->domain));
    }

    /**
     * Get history object properties
     *
     * @param int $id
     * @return object
     */
    public function getHistory($id)
    {
        return json_decode(self::getClient()->getHistory($this->domain, $id));
    }

    /**
     * Get users associated to the SMS account
     *
     * @return array
     */
    public function getJobs()
    {
        return json_decode(self::getClient()->getJobs($this->domain));
    }

    /**
     * Get job object properties
     *
     * @param int $id
     * @return object
     */
    public function getJob($id)
    {
        return json_decode(self::getClient()->getJob($this->domain, $id));
    }

    /**
     * Get SMS offers available
     *
     * @param string $countryDestination country code ISO 3166-2
     * @param string $countryCurrencyPrice country code ISO 3166-2
     * @param integer $quantity
     *
     * @return object
     */

    public function getSeeOffers($countryDestination, $countryCurrencyPrice, $quantity)
    {
        return json_decode(self::getClient()->getSeeOffers($this->domain, $countryDestination, $countryCurrencyPrice, $quantity));
    }

    /**
     * Get senders allowed associated to the SMS account
     *
     * @return array
     */
    public function getSenders()
    {
        return json_decode(self::getClient()->getSenders($this->domain));
    }

    /**
     * Get sender object properties
     *
     * @param string $sender
     * @return object
     */
    public function getSender($sender)
    {
        return json_decode(self::getClient()->getSender($this->domain, $sender));
    }

    /**
     * Get users associated to the SMS account
     *
     * @return array
     */
    public function getUsers()
    {
        return json_decode(self::getClient()->getUsers($this->domain));
    }

    /**
     * Get user object properties
     *
     * @param string $user
     * @return object
     */
    public function getUser($user)
    {
        return json_decode(self::getClient()->getUser($this->domain, $user));
    }

}
