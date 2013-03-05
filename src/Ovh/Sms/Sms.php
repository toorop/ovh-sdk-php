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


use \Ovh\Common\Exception\NotImplementedYetException;
use \Ovh\Common\Exception\NotImplementedYetByOvhException;

use Ovh\Sms\SmsClient;
use Ovh\Common\Task;


class Sms
{

    private $domain = null;
    private static $smsClient = null;

    /**
     * @param string $domain
     */
    public function __construct($domain)
    {
        $this->setDomain($domain);
    }


    /**
     * Return Sms client
     *
     * @return null|SmsClient
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
     *  Get SMS object properties
     *
     *  @return object
     *
     */
    public function  getProperties()
    {
        return json_decode(self::getClient()->getProperties($this->getDomain()));
    }

    /**
     * Get Numbers blacklisted associated to the sms account
     *
     * @return object
     */
    public function getBlacklists()
    {
        return json_decode(self::getClient()->getBlackLists($this->getDomain()));
    }

    /**
     * Get Numbers blacklisted associated to the sms account
     *
     * @return object
     */
    public function getHistories()
    {
        return json_decode(self::getClient()->getHistories($this->getDomain()));
    }

    /**
     * Get history object properties
     *
     * @return object
     */
    public function getHistory($id)
    {
        return json_decode(self::getClient()->getHistory($this->getDomain(), $id));
    }

    /**
     * Get users associated to the sms account
     *
     * @return object
     */
    public function getJobs()
    {
        return json_decode(self::getClient()->getJobs($this->getDomain()));
    }

    /**
     * Get job object properties
     *
     * @return object
     */
    public function getJob($id)
    {
        return json_decode(self::getClient()->getJob($this->getDomain(), $id));
    }

    /**
     * Get job object properties
     *
     * @return object
     */
    public function getSeeOffers($countryDestination, $countryCurrencyPrice, $quantity)
    {
        return json_decode(self::getClient()->getSeeOffers($this->getDomain(), $countryDestination, $countryCurrencyPrice, $quantity));
    }

    /**
     * Get senders allowed associated to the sms account
     *
     * @return object
     */
    public function getSenders()
    {
        return json_decode(self::getClient()->getSenders($this->getDomain()));
    }

    /**
     * Get sender object properties
     *
     * @return object
     */
    public function getSender($sender)
    {
        return json_decode(self::getClient()->getSender($this->getDomain(), $sender));
    }

    /**
     * Get users associated to the sms account
     *
     * @return object
     */
    public function getUsers()
    {
        return json_decode(self::getClient()->getUsers($this->getDomain()));
    }

    /**
     * Get users associated to the sms account
     *
     * @return object
     */
    public function getUser($user)
    {
        return json_decode(self::getClient()->getUser($this->getDomain(), $user));
    }

}
