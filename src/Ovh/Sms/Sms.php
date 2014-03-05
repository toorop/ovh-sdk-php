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
use Ovh\Common\Exception\NotImplementedYetByOvhException;
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
        if (!self::$smsClient instanceof SmsClient){
            self::$smsClient=new SmsClient();
        };
        return self::$smsClient;
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
     * @param array $props (avalaibles keys are callBack and templates)
     * @return boolean
     */
    public function setProperties($props)
    {
        return self::getClient()->setProperties($this->domain, $props);
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
     * Delete a number from blacklist
     *
     * @param string $number
     * @return bool
     */
    public function deleteBlacklist($number)
    {
        self::getClient()->deleteBlacklist($this->domain, $number);
        return true;
    }

    /**
     * Get SMS sent associated to the SMS account
     * Wrapper on $this->getOutgoings() for backward compatibility
     *
     * @return array
     * @deprecated
     */
    public function getHistories()
    {
        trigger_error('Deprecated method. Use $this->getOutgoings instead.', E_USER_DEPRECATED);
        return $this->getOutgoings();
    }

    /**
     * Get SMS sent associated to the SMS account
     *
     * @return array
     */
    public function getOutgoings()
    {
        return json_decode(self::getClient()->getOutgoings($this->domain));
    }

    /**
     * Get history object properties
     * Wrapper on $this->getOutgoing() for backward compatibility
     *
     * @return array
     * @deprecated
     */
    public function getHistory($id)
    {
        trigger_error('Deprecated method. Use $this->getOutgoing instead.', E_USER_DEPRECATED);
        return $this->getOutgoing($id);
    }

    /**
     * Get history object properties
     *
     * @param int $id
     * @return object
     */
    public function getOutgoing($id)
    {
        return json_decode(self::getClient()->getOutgoing($this->domain, $id));
    }

    /**
     * Delete the sms history given
     * Wrapper on $this->deleteOutgoing() for backward compatibility
     *
     * @return array
     * @deprecated
     */
    public function deleteHistory($id)
    {
        trigger_error('Deprecated method. Use $this->deleteOutgoing instead.', E_USER_DEPRECATED);
        return $this->deleteOutgoing($id);
    }

    /**
     * Delete the sms history given
     *
     * @param $id
     */
    public function deleteOutgoing($id)
    {
        self::getClient()->deleteOutgoing($this->domain, $id);
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
     * Create a new job
     *
     * @param array $opt
     * @return object
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function createJob($opt)
    {
        return json_decode(self::getClient()->createJob($this->domain, $opt));
    }

    /**
     * @param int $id : job id
     * @return void
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function deleteJob($id)
    {
        self::getClient()->deleteJob($this->domain, $id);
    }

    /**
     * Helper to send SMS
     *
     * @param string $from => sender
     * @param string $to => receiver
     * @param string $msg => message
     * @return object
     */
    public function send($from, $to, $msg)
    {
        $opt = array(
            'sender' => $from,
            'receivers' => array($to),
            'message' => $msg
        );
        return $this->createJob($opt);
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
     * Purchase SMS credits
     *
     * @param int $quantity
     * @return string (json encoded object)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function purchase($quantity)
    {
        return json_decode(self::getClient()->purchase($this->domain, $quantity));
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
     *  Create a new sender
     *
     * @param array $sender (keys : sender (requiered) => string, relaunch => string, reason => string)
     * @return void
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function createSender($sender)
    {
        self::getClient()->createSender($this->domain, $sender);
    }

    /**
     * @param array $sender
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function updateSender($sender){
        throw new NotImplementedYetByOvhException();
    }

    /**
     * @param string $sender
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function deleteSender($sender){
        throw new NotImplementedYetByOvhException();
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

    /**
     *
     * @param $user
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function addUser($user){
        throw new NotImplementedYetByOvhException();
    }

    /**
     *
     * @param $user
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function updateUser($user){
        throw new NotImplementedYetByOvhException();
    }

    /**
     *
     * @param $user
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function deleteUser($user){
        throw new NotImplementedYetByOvhException();
    }

}
