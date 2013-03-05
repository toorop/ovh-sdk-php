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

use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\InvalidArgumentException;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Common\Exception\NotImplementedYetException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;
use Ovh\Sms\Exception\SmsNotFoundException;
use Ovh\Sms\Exception\SmsException;


class smsClient extends AbstractClient
{

    /**
     * Get SMS properties
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getProperties($domain)
    {
        try {
            $r = $this->get('sms/' . $domain)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get Numbers blacklisted associated to the sms account
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getBlacklists($domain)
    {
        try {
            $r = $this->get('sms/' . $domain . '/blacklists')->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get Sms sent associated to the sms account
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getHistories($domain)
    {
        try {
            $r = $this->get('sms/' . $domain . '/histories')->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get history object properties
     *
     * @param string $domain
     * @param integer $id
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getHistory($domain, $id)
    {
        $id = intval($id);
        try {
            $r = $this->get('sms/' . $domain . '/histories/' . $id)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get users associated to the sms account
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getJobs($domain)
    {
        try {
            $r = $this->get('sms/' . $domain . '/jobs')->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get job object properties
     *
     * @param string $domain
     * @param string $id
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getJob($domain, $id)
    {
        $id = intval($id);
        try {
            $r = $this->get('sms/' . $domain . '/jobs/' . $id)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Describe SMS offers available.
     *
     * @param string $countryDestination country code ISO 3166-2
     * @param string $countryCurrencyPrice country code ISO 3166-2
     * @param integer $quantity
     * @param string $domain
     *
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getSeeOffers($domain, $countryDestination, $countryCurrencyPrice, $quantity)
    {
        $countriesDestination = array('all', 'ai', 'an', 'ar', 'at', 'au', 'aw', 'ba', 'bb', 'be', 'bg', 'bh', 'bm', 'bo', 'br', 'bz', 'ch', 'cl', 'cn', 'co', 'cr', 'cu', 'cy', 'cz', 'de', 'dk', 'dm', 'dz', 'ec', 'ee', 'eg', 'es', 'fi', 'fr', 'gb', 'gd', 'gp', 'gr', 'gy', 'hk', 'hn', 'hr', 'ht', 'hu', 'id', 'ie', 'il', 'in', 'is', 'it', 'jm', 'jo', 'jp', 'kr', 'kw', 'ky', 'lb', 'lc', 'lt', 'lu', 'lv', 'ma', 'me', 'mq', 'ms', 'mt', 'my', 'nc', 'ng', 'nl', 'no', 'nz', 'pa', 'pe', 'pf', 'ph', 'pk', 'pl', 'pt', 'py', 're', 'ro', 'rs', 'ru', 'sa', 'se', 'sg', 'si', 'sk', 'sr', 'tc', 'th', 'tn', 'tr', 'tt', 'tw', 'ua', 'uy', 'vc', 've', 'vg', 'vn', 'za');
        $countryDestination = strtolower($countryDestination);

        $countriesCurrencyPrice = array('all', 'ca', 'cz', 'de', 'en', 'es', 'fi', 'fr', 'gb', 'ie', 'it', 'lt', 'ma', 'nl', 'pl', 'pp', 'pt', 'qc', 'ru', 'sk', 'sn', 'tn', 'we');
        $countryCurrencyPrice = strtolower($countryCurrencyPrice);

        $quantities = array(100, 1000, 10000, 100000, 1000000, 200, 250, 2500, 25000, 500, 5000, 50000);
        $quantity = intval($quantity);

        if (!in_array($countryDestination, $countriesDestination))
            throw new BadMethodCallException('Parameter $countryDestination must be in array ('.implode(', ', $countriesDestination).'), "' . $countryDestination . '" given.');
        if (!in_array($countryCurrencyPrice, $countriesCurrencyPrice))
            throw new BadMethodCallException('Parameter $countryCurrencyPrice must be in array ('.implode(', ', $countriesCurrencyPrice).'), "' . $countryCurrencyPrice . '" given.');
        if (!in_array($quantity, $quantities))
            throw new BadMethodCallException('Parameter $quantity must be in array ('.implode(', ', $quantities).'), "' . $quantity . '" given.');

        try {

            $r = $this->get('sms/' . $domain . '/seeOffers?countryDestination=' . $countryDestination . '&countryCurrencyPrice=' . $countryCurrencyPrice . '&quantity=' . $quantity)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get senders allowed associated to the sms account
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getSenders($domain)
    {
        try {
            $r = $this->get('sms/' . $domain . '/senders')->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get sender object properties
     *
     * @param string $domain
     * @param string $sender
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getSender($domain, $sender)
    {
        try {
            $r = $this->get('sms/' . $domain . '/senders/' . $sender)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get users associated to the sms account
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getUsers($domain)
    {
        try {
            $r = $this->get('sms/' . $domain . '/users')->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get users associated to the sms account
     *
     * @param string $domain
     * @param string $user
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getUser($domain, $user)
    {
        try {
            $r = $this->get('sms/' . $domain . '/users/' . $user)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
}


