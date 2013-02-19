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

namespace Ovh\Xdsl;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Common\Exception\NotImplementedYetException;

use Ovh\Xdsl\Exception\XdslException;


class XdslClient extends AbstractClient
{


    /**
     * Get Properties of Xdsl abo
     *
     * @param string $id
     * @return string json
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getProperties($id)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $r = $this->get('xdsl/' . $id)->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $id
     * @param string $desc
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function setDescription($id, $desc)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$desc)
            throw new BadMethodCallException('Missing parameter $desc.');
        try {
            $this->put('xdsl/' . $id, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode(array('description' => $desc)))->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * @param string $id
     * @return string json
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getPendingAction($id)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $r = $this->get('xdsl/' . $id . '/pendingAction')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * Get tasks
     *
     * @param string $id
     * @return string json
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getTasks($id)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $r = $this->get('xdsl/' . $id . '/tasks')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * @param string $id
     * @param integer $taskId
     * @return string json
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getTask($id, $taskId)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$taskId)
            throw new BadMethodCallException('Missing parameter $taskId.');
        try {
            $r = $this->get('xdsl/' . $id . '/tasks/' . $taskId)->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * Get IP
     *
     * @param string $id
     * @return string json
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getIps($id)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $r = $this->get('xdsl/' . $id . '/ips')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get IP properties
     *
     * @param string $id
     * @param string $ip
     * @return string json
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getIpProperties($id, $ip)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        try {
            $r = $this->get('xdsl/' . $id . '/ips/' . $ip)->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * @param string $id
     * @param string $ip
     * @return string
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function ipGetMonitoringState($id, $ip)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        return json_decode($this->getIpProperties($id, $ip))->monitoring;
    }

    /**
     * Return IP version (ipv4 | ipv6)
     *
     * @param $id
     * @param $ip
     * @return mixed
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function ipGetVersion($id, $ip)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        return json_decode($this->getIpProperties($id, $ip))->version;
    }

    /**
     * Return IP range
     *
     * @param string $id
     * @param string $ip
     * @return integer
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function ipGetRange($id, $ip)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        return json_decode($this->getIpProperties($id, $ip))->range;
    }

    /**
     * Return dnsList
     *
     * @param string $id
     * @param string $ip
     * @return array
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function ipGetDnsList($id, $ip)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        return json_decode($this->getIpProperties($id, $ip))->dnsList;
    }

    /**
     * Return ip reverse (hostname)
     *
     * @param string $id
     * @param string $ip
     * @return string reverse
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function ipGetReverse($id, $ip)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        return json_decode($this->getIpProperties($id, $ip))->reverse;
    }


    /**
     * @param string $id
     * @param string $ip
     * @return string (json encoded)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function ipGetMonitoringNotifications($id, $ip)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        try {
            $r = $this->get('xdsl/' . $id . '/ips/' . $ip . '/monitoringNotifications')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $id
     * @param string $ip
     * @param string $frequency
     * @param string $email
     * @param array $sms
     * @return string json encoded
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function ipSetMonitoringNotifications($id, $ip, $frequency = 'once', $email = '', $sms = array())
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        $frequency = strtolower($frequency);
        if (!in_array($frequency, array('once', '5m', '1h', '6h')))
            throw new BadMethodCallException('Parameter frequency must be "once" or "5m" or "1h" or "6h". ' . $frequency . ' given.');
        if (strlen($email) == 0 && !is_array($sms))
            throw new BadMethodCallException('You mus at least set parameter email or parameter sms');
        if (strlen($email) == 0 && (!array_key_exists('smsAccount', $sms) || !array_key_exists('phone', $sms)))
            throw new BadMethodCallException('sms parameter must have "smsAccount" and "phone" keys set.');
        if (!empty($email)) {
            $payload = array('email' => $email, 'frequency' => $frequency, 'type' => 'mail');
        } else {
            $payload = array('smsAccount' => $sms['smsAccount'], 'phone' => $sms['phone'], 'frequency' => $frequency, 'type' => 'sms');
        }
        try {
            $r = $this->post('xdsl/' . $id . '/ips/' . $ip . '/monitoringNotifications', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * Get IP monitoring notification properties
     *
     * @param string $id
     * @param string $ip
     * @param integer $notificationId
     * @return string (json encoded)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function ipGetMonitoringNotification($id, $ip, $notificationId)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        if (!$notificationId)
            throw new BadMethodCallException('Missing parameter $notificationId.');
        try {
            $r = $this->get('xdsl/' . $id . '/ips/' . $ip . '/monitoringNotifications/' . $notificationId)->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $id
     * @param string $ip
     * @param integer $notificationId
     * @param string $frequency
     * @param string $email
     * @param string $phone
     * @param bool $enable
     * @return string json encoded
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function ipUpdateMonitoringNotification($id, $ip, $notificationId, $frequency = '', $email = '', $phone = '', $enable = true)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        if (!$notificationId)
            throw new BadMethodCallException('Missing parameter $notificationId.');
        $frequency = strtolower($frequency);
        if (!in_array($frequency, array('once', '5m', '1h', '6h')))
            throw new BadMethodCallException('Parameter frequency must be "once" or "5m" or "1h" or "6h". ' . $frequency . ' given.');
        if (!is_bool($enable))
            throw new BadMethodCallException('Parameter $enable must be a boolean.');
        $payload = array(
            'frequency' => $frequency,
            'email' => strtolower($email),
            'phone' => strtolower($phone)
        );
        try {
            $this->put('xdsl/' . $id . '/ips/' . $ip . '/monitoringNotifications/' . $notificationId, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return;
    }

    /**
     * Delete Monitoring IP notification
     *
     * @param  string $id
     * @param string $ip
     * @param integer $notificationId
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function ipDeleteMonitoringNotification($id, $ip, $notificationId)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$ip)
            throw new BadMethodCallException('Missing parameter $ip.');
        if (!$notificationId)
            throw new BadMethodCallException('Missing parameter $notificationId.');
        try {
            $r = $this->delete('xdsl/' . $id . '/ips/' . $ip . '/monitoringNotifications/' . $notificationId)->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return;
    }


    /**
     * Get lines associated with this Xdsl suncription
     *
     * @param string $id
     * @return string (json encoded array)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getLines($id)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $r = $this->get('xdsl/' . $id . '/lines')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get properties of a line
     *
     * @param string $id
     * @param string $line
     * @return string (json encoded properties)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getLineProperties($id, $line)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$line)
            throw new BadMethodCallException('Missing parameter $line.');
        try {
            $r = $this->get('xdsl/' . $id . '/lines/' . $line)->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Reset Dslam Port
     *
     * @param string $id
     * @param string $line
     * @return string (json encoded object task)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function lineResetDslamPort($id, $line)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$line)
            throw new BadMethodCallException('Missing parameter $line.');
        try {
            $r = $this->post('xdsl/' . $id . '/lines/' . $line . '/resetDslamPort')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    public function lineChangeDslamProfile($id, $line)
    {
        return;
    }

    /**
     * Get available profiles for Dslam
     *
     * @param string $id
     * @param string $line
     * @return string (json encoded array of objects DslamLineProfile)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function lineGetAvailableDslamProfiles($id, $line)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$line)
            throw new BadMethodCallException('Missing parameter $line.');
        try {
            $r = $this->get('xdsl/' . $id . '/lines/' . $line . '/availableDslamProfiles')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Get modem properties
     *
     * @param string $id
     * @return string (json encoded object modem)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getModemProperties($id)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $r = $this->get('xdsl/' . $id . '/modem')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Enable IPv6 routing
     *
     * @param string $id
     * @return string (json encoded object task)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function enableIpv6($id)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $r = $this->post('xdsl/' . $id . '/ipv6', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode(array('enabled' => true)))->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $id
     * @return string (json encoded object task)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function disableIpv6($id)
    {
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $r = $this->post('xdsl/' . $id . '/ipv6', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode(array('enabled' => false)))->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Request ppp credentials
     *
     * @param string $id
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getPppLoginByMail($id){
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $this->post('xdsl/' . $id . '/requestPPPLoginMail')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return;
    }

    /**
     * Get available lns
     *
     * @param string $id
     * @return string (json encoded array of Lns object)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function getAvailableLns($id){
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        try {
            $r=$this->get('xdsl/' . $id . '/availableLns')->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Change Lns
     *
     * @param string $id
     * @param string $lnsName
     * @return string (json encoded object task)
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\XdslException
     */
    public function changeLns($id,$lnsName){
        if (!$id)
            throw new BadMethodCallException('Missing parameter $id.');
        if (!$lnsName)
            throw new BadMethodCallException('Missing parameter $lnsname.');
        try {
            $r=$this->post('xdsl/' . $id . '/changeLns',array('Content-Type' => 'application/json;charset=UTF-8'), json_encode(array('lnsName' => $lnsName)))->send();
        } catch (\Exception $e) {
            throw new XdslException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
}
