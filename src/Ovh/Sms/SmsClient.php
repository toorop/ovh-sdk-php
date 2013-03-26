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
use Ovh\Common\Exception\NotImplementedYetByOvhException;
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
     * Create a new job
     *
     * @param string $domain
     * @param array $opt
     * @return string (json encoded object)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function createJob($domain, $opt)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$opt)
            throw new BadMethodCallException('Parameter $opt is missing');
        if (!is_array($opt))
            throw new BadMethodCallException('Parameter $opt must be a array of option. ' . gettype($opt) . ' given.');
        // required opt are :
        //      receivers : array of recievers
        //      message : sms message
        //      sender : a valid sender
        if (!array_key_exists('message', $opt) || !array_key_exists('sender', $opt) || !array_key_exists('receivers', $opt))
            throw new BadMethodCallException('Parameter $opt must have at least  "message" (=> string), "sender" (=> string) and receivers (=> array) keys');

        if (!is_array($opt['receivers']))
            throw new BadMethodCallException('Parameter $opt[receivers] must be a array of option. ' . gettype($opt['receivers']) . ' given.');
        if (count($opt['receivers']) == 0)
            throw new BadMethodCallException('Parameter $opt[receivers] is empty.');

        // clean opt
        $job = array();

        $job['sender'] = $opt['sender'];
        $job['message'] = $opt['message'];
        $job['receivers'] = $opt['receivers'];

        // noStopClause : if true no STOP clause at the end of the SMS. Default to false
        $job['noStopClause'] = (@$opt['noStopClause']) ? $opt['noStopClause'] : false;

        // priority 0 to 3. Default : 3
        if (@$opt['priority']) {
            $opt['priority'] = intval($opt['priority']);
            if ($opt['priority'] < 0 || $opt['priority'] > 3)
                $job['priority'] = 3;
        } else $job['priority'] = 3;

        // validityPeriod : SMS validity in minutes. Default : 2880 (48 Hours)
        $job['validityPeriod'] = (@$opt['validityPeriod']) ? intval($opt['validityPeriod']) : 2880;

        // charset : Charset of sms message. Default : ?
        $job['charset'] = (@$opt['charset']) ? $opt['charset'] : '';

        // coding : the sms coding : 1 for 7 bit or 2 for unicode. Default is 1
        if (@$opt['coding']) {
            $opt['coding'] = intval($opt['coding']);
            if ($opt['coding'] < 0 || $opt['coding'] > 1)
                $job['coding'] = 1;
        } else $job['coding'] = 1;

        // differedPeriod :  Time in minutes to wait before sending the message. Default : 0
        $job['differedPeriod'] = (@$opt['differedPeriod']) ? intval($opt['differedPeriod']) : 0;

        // tag : optionnal tag (string)
        $job['tag'] = (@$opt['tag']) ? $opt['tag'] : '';

        // class :  the sms class: flash(0), phone display(1), SIM(2), toolkit(3). Default : 2
        if (@$opt['class']) {
            $opt['class'] = intval($opt['class']);
            if ($opt['class'] < 0 || $opt['class'] > 3)
                $job['class'] = 2;
            else
                $job['class'] = $opt['class'];
        } else $job['class'] = 2;

        unset($opt); // not - really - usefull...

        try {
            $r = $this->post('sms/' . $domain . '/jobs', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($job))->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $domain
     * @param int $id : job id
     * @return \Guzzle\Http\Message\Response
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function deleteJob($domain, $id)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if ($id !== 0 && !$id)
            throw new BadMethodCallException('Parameter $id is missing.');
        $id = intval($id);
        try {
            $r = $this->delete('sms/' . $domain . '/job/' . $id)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r;
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
     * Purchase SMS credits
     *
     * @param string $domain
     * @param int $quantity
     * @return string (json encoded object)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function purchase($domain, $quantity)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$quantity)
            throw new BadMethodCallException('Parameter $quantity is missing.');
        $quantity = intval($quantity);
        $allowedQ = array(100, 200, 250, 500, 1000, 5000, 2500, 10000, 50000, 100000);
        if (!in_array($quantity, $allowedQ))
            throw new BadMethodCallException('Parameter $quantity must be in array (' . implode(', ', $allowedQ) . '), "' . $quantity . '" given.');
        try {
            $r = $this->post('sms/' . $domain . '/purchase', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($quantity))->send();
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
     *  Create a new sender
     *
     * @param string $domain
     * @param array $sender (keys : sender (requiered) => string, relaunch => string, reason => string)
     * @return void
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function createSender($domain, $sender)
    {
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!$sender)
            throw new BadMethodCallException('Parameter $sender is missing.');
        if (!is_array($sender))
            throw new BadMethodCallException('Parameter $sender must be a array. ' . gettype($sender) . ' given.');
        // required fields (sender)
        if (!array_key_exists('sender', $sender))
            throw new BadMethodCallException('Parameter $sender have key sender (string).');
        // sanitize
        $t = array();
        $t['sender'] = $sender['sender'];
        // relaunch
        ($sender['relaunch']) ? $t['relaunch'] = $sender['relaunch'] : $t['relaunch'] = '';
        // reason
        ($sender['reason']) ? $t['reason'] = $sender['reason'] : $t['reason'] = '';
        unset($sender);
        try {
            $r = $this->post('sms/' . $domain . '/senders', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($t))->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $domain
     * @param array $sender
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function updateSender($domain, $sender)
    {
        throw new NotImplementedYetByOvhException();
    }

    /**
     * @param string $domain
     * @param string $sender
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function deleteSender($domain, $sender)
    {
        throw new NotImplementedYetByOvhException();
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

    /**
     *
     * @param $domain
     * @param $user
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function addUser($domain, $user)
    {
        throw new NotImplementedYetByOvhException();
    }

    /**
     *
     * @param $domain
     * @param $user
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function updateUser($domain, $user)
    {
        throw new NotImplementedYetByOvhException();
    }

    /**
     *
     * @param $domain
     * @param $user
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     */
    public function deleteUser($domain, $user)
    {
        throw new NotImplementedYetByOvhException();
    }

}
