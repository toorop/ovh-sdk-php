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

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Sms\Exception\SmsException;

class smsClient extends AbstractClient
{

    /**
     * Get SMS properties
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\SmsException
     * @throws BadMethodCallException
     */
    public function getProperties($domain)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('sms/' . $domain)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * Set Properties of SMS account
     *
     * @param string $domain
     * @param array $properties (available keys are callBack & templates)
     * @return \Guzzle\Http\Message\Response
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function setProperties($domain, $properties)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$properties)
            throw new BadMethodCallException('Parameter $properties is missing.');
        if (!is_array($properties))
            throw new BadMethodCallException('Parameter $properties must be a array.');
        // clean : only callback and templates are allowed
        $t = array();
        if (array_key_exists('callBack', $properties))
            $t['callBack'] = $properties['callBack'];
        if (array_key_exists('templates', $properties))
            $t['templates'] = $properties['templates'];
        $properties = $t;
        unset($t);
        if (count($properties) == 0)
            throw new BadMethodCallException('Parameter $properties does not contain valid key. valid keys are "templates" and "callBack"');
        try {
            $r = $this->put('sms/' . $domain, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($properties))->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return true;
    }


    /**
     * Get Numbers blacklisted associated to the sms account
     *
     * @param string $domain
     * @return string Json
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getBlacklists($domain)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('sms/' . $domain . '/blacklists')->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Delete a number from blacklist
     *
     * @param string $domain
     * @param string $number
     * @return \Guzzle\Http\Message\Response
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function deleteBlacklist($domain, $number)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$number)
            throw new BadMethodCallException('Parameter $number is missing.');
        try {
            $r = $this->delete('sms/' . $domain . '/blacklists/' . $number)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r;
    }

    /**
     * Get Sms sent associated to the sms account
     *
     * @param string $domain
     * @return string Json
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getHistories($domain)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
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
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getHistory($domain, $id)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if ($id !== 0 && !$id)
            throw new BadMethodCallException('Parameter $id is missing.');
        $id = intval($id);
        try {
            $r = $this->get('sms/' . $domain . '/histories/' . $id)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }

        return $r->getBody(true);
    }

    /**
     * Delete the sms history given
     *
     * @param string $domain
     * @param int $id
     * @return bool true‡
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function deleteHistory($domain, $id)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$id)
            throw new BadMethodCallException('Parameter $id is missing.');
        try {
            $this->delete('sms/' . $domain . '/histories/' . $id)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return true;
    }

    /**
     * Get users associated to the sms account
     *
     * @param string $domain
     * @return string Json
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getJobs($domain)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
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
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getJob($domain, $id)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if ($id !== 0 && !$id)
            throw new BadMethodCallException('Parameter $id is missing.');
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
     * @return string Json
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getSeeOffers($domain, $countryDestination, $countryCurrencyPrice, $quantity)
    {
        $countriesDestination = array('all', 'ai', 'an', 'ar', 'at', 'au', 'aw', 'ba', 'bb', 'be', 'bg', 'bh', 'bm', 'bo', 'br', 'bz', 'ch', 'cl', 'cn', 'co', 'cr', 'cu', 'cy', 'cz', 'de', 'dk', 'dm', 'dz', 'ec', 'ee', 'eg', 'es', 'fi', 'fr', 'gb', 'gd', 'gp', 'gr', 'gy', 'hk', 'hn', 'hr', 'ht', 'hu', 'id', 'ie', 'il', 'in', 'is', 'it', 'jm', 'jo', 'jp', 'kr', 'kw', 'ky', 'lb', 'lc', 'lt', 'lu', 'lv', 'ma', 'me', 'mq', 'ms', 'mt', 'my', 'nc', 'ng', 'nl', 'no', 'nz', 'pa', 'pe', 'pf', 'ph', 'pk', 'pl', 'pt', 'py', 're', 'ro', 'rs', 'ru', 'sa', 'se', 'sg', 'si', 'sk', 'sr', 'tc', 'th', 'tn', 'tr', 'tt', 'tw', 'ua', 'uy', 'vc', 've', 'vg', 'vn', 'za');
        $countryDestination = strtolower($countryDestination);

        $countriesCurrencyPrice = array('all', 'ca', 'cz', 'de', 'en', 'es', 'fi', 'fr', 'gb', 'ie', 'it', 'lt', 'ma', 'nl', 'pl', 'pp', 'pt', 'qc', 'ru', 'sk', 'sn', 'tn', 'we');
        $countryCurrencyPrice = strtolower($countryCurrencyPrice);

        $quantities = array(100, 1000, 10000, 100000, 1000000, 200, 250, 2500, 25000, 500, 5000, 50000);
        $quantity = intval($quantity);

        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');

        if (!in_array($countryDestination, $countriesDestination)) {
            throw new BadMethodCallException('Parameter $countryDestination must be in array (' . implode(', ', $countriesDestination) . '), "' . $countryDestination . '" given.');
        }
        if (!in_array($countryCurrencyPrice, $countriesCurrencyPrice)) {
            throw new BadMethodCallException('Parameter $countryCurrencyPrice must be in array (' . implode(', ', $countriesCurrencyPrice) . '), "' . $countryCurrencyPrice . '" given.');
        }
        if (!in_array($quantity, $quantities)) {
            throw new BadMethodCallException('Parameter $quantity must be in array (' . implode(', ', $quantities) . '), "' . $quantity . '" given.');
        }

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
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getSenders($domain)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
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
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getSender($domain, $sender)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$sender)
            throw new BadMethodCallException('Parameter $sender is missing.');
        $sender = urlencode($sender);
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
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getUsers($domain)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
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
     * @throws BadMethodCallException
     * @throws Exception\SmsException
     */
    public function getUser($domain, $user)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$user)
            throw new BadMethodCallException('Parameter $user is missing.');
        try {
            $r = $this->get('sms/' . $domain . '/users/' . $user)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

}
