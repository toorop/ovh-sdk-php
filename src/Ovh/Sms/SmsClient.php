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
     * @param integer $i
     * @return string Json
     * @throws Exception\SmsException
     * @throws Exception\SmsNotFoundException
     */
    public function getHistory($domain, $id)
    {
        try {
            $r = $this->get('sms/' . $domain . '/histories/' . $id)->send();
        } catch (\Exception $e) {
            throw new SmsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
}