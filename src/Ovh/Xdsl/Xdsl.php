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

/**
 * @todo setDescription
 */


namespace Ovh\Xdsl;

use Ovh\Common\Exception\BadConstructorCallException;
use Ovh\Common\Exception\NotImplementedYetException;


use Ovh\Xdsl\XdslClient;

/**
 *
 */
class Xdsl
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var  \Ovh\Xdsl\XdslClient;
     */
    private static $client;

    /**
     * @var
     */
    private $properties;

    /**
     *
     * @param string $id
     *
     * @throws \Ovh\Common\Exception\BadConstructorCallException;
     */
    public function __construct($id)
    {
        if (!$id)
            throw new BadConstructorCallException("$id parameter is missing.");
        $this->id = (string)$id;
    }

    /**
     * @return XdslClient
     */
    private static function getClient()
    {
        if (!self::$client instanceof XdslClient) {
            self::$client = new XdslClient();
        }
        return self::$client;
    }

    /**
     * Get Raw properties
     *
     * @return object
     */
    public function getProperties()
    {
        $this->properties = json_decode(self::getClient()->getProperties($this->id));
        return $this->properties;
    }


    /**
     * Is IPV6 Enabled
     *
     * @param bool $forceReload
     * @return bool
     */
    public function isIpv6Enabled($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->ipv6Enabled;
    }


    /**
     * Get Status
     *
     * @param bool $forceReload
     * @return string
     */
    public function getStatus($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->status;
    }

    /**
     * Return nb pairs used
     *
     * @param bool $forceReload
     * @return integer
     */
    public function getPairsNumber($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->pairsNumber;
    }

    /**
     * Get description (writtable -> setDescription)
     *
     * @param bool $forceReload
     * @return string
     */
    public function getDescription($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->description;
    }

    /**
     * Get access Type (eg adsl, sdsl,..)
     *
     * @param bool $forceReload
     * @return string
     */
    public function getAccessType($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->accessType;
    }

    /**
     *  Get capabilities
     *
     * @param bool $forceReload
     * @return object
     */
    public function getCapabilities($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->capabilities;
    }

    /**
     * Can we change DSLAM Profile  ?
     *
     * @param bool $forceReload
     * @return boolean
     */
    public function canChangeDslamProfile($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->capabilities->canChangeDslamProfile;
    }

    /**
     * Can we change DSLAM port ?
     *
     * @param bool $forceReload
     * @return boolean
     */
    public function canResetDslamPort($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->capabilities->canResetDslamPort;
    }

    /**
     * Can we change Lns ?
     *
     * @param bool $forceReload
     * @return boolean
     */
    public function canChangeLns($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->capabilities->canChangeLns;
    }

    /**
     * Get address
     *
     * @param bool $forceReload
     * @return object
     */
    public function getAddress($forceReload = false)
    {
        if (!$this->properties || $forceReload)
            $this->getProperties();
        return $this->properties->address;
    }

    /**
     * Set description
     *
     * @param $desc
     */
    public function setDescription($desc)
    {
        self::getClient()->setDescription($this->id, (string)$desc);
    }

    /**
     * Get Pending Action
     *
     * @return object
     */
    public function getPendingAction()
    {
        return json_decode(self::getClient()->getPendingAction($this->id));
    }


    /**
     * Get all tasks
     *
     * @return array
     */
    public function getTasks()
    {
        return json_decode(self::getClient()->getTasks($this->id));
    }

    /**
     * Get task info
     *
     * @param $taskId
     * @return mixed
     */
    public function getTask($taskId)
    {
        return json_decode(self::getClient()->getTask($this->id, $taskId));
    }

    /**
     * Get IP
     *
     * @return array of ips
     */
    public function getIps()
    {
        return json_decode(self::getClient()->getIps($this->id));
    }

    /**
     * Get IP properties
     *
     * @param string $ip
     * @return mixed
     */
    public function getIpProperties($ip)
    {
        return json_decode(self::getClient()->getIpProperties($this->id, $ip));
    }

    /**
     * Get IP monitoring state (enable | disable)
     *
     * @param string $ip
     * @return string enable | disable
     */
    public function ipGetMonitoringState($ip)
    {
        return self::getClient()->ipGetMonitoringState($this->id, $ip);
    }

    /**
     * Get IP version (ipv4 or ipv6)
     *
     * @param  string $ip
     * @return string ipv4 | ipv6
     */
    public function ipGetVersion($ip)
    {
        return self::getClient()->ipGetVersion($this->id, $ip);
    }

    /**
     * Get IP range
     *
     * @param string $ip
     * @return int
     */
    public function ipGetRange($ip)
    {
        return self::getClient()->ipGetRange($this->id, $ip);
    }

    /**
     * Get DNS List for IP
     *
     * @param string $ip
     * @return array of IP
     */
    public function ipGetDnsList($ip)
    {
        return self::getClient()->ipGetDnsList($this->id, $ip);
    }

    /**
     * Get IP reverse
     *
     * @param string $ip
     * @return string
     */
    public function ipGetReverse($ip)
    {
        return self::getClient()->ipGetReverse($this->id, $ip);
    }

    /**
     * Get monitoring notifications for IP
     *
     * @param string $ip
     * @return array
     */
    public function ipGetMonitoringNotifications($ip)
    {
        return json_decode(self::getClient()->ipGetMonitoringNotifications($this->id, $ip));
    }

    /**
     * Set a monitoring notification on IP
     *
     * @param string $ip
     * @param string $frequency
     * @param string $email
     * @param array $sms
     * @return object
     */
    public function ipSetMonitoringNotifications($ip, $frequency = 'once', $email = '', $sms = array())
    {
        return json_decode(self::getClient()->ipSetMonitoringNotifications($this->id, $ip, $frequency, $email, $sms));
    }

    /**
     * Get monitoring notification properties for IP
     *
     * @param  string $ip
     * @param integer $notificationId
     * @return object
     */
    public function ipGetMonitoringNotification($ip, $notificationId)
    {
        return json_decode(self::getClient()->ipGetMonitoringNotification($this->id, $ip, $notificationId));
    }

    /**
     * Update monitoring notification for IP $ip
     *
     * @param string $ip
     * @param integer $notificationId
     * @param string $frequency
     * @param string $email
     * @param string $phone
     * @param bool $enable
     */
    public function ipUpdateMonitoringNotification($ip, $notificationId, $frequency, $email, $phone, $enable)
    {
        self::getClient()->ipUpdateMonitoringNotification($this->id, $ip, $notificationId, $frequency, $email, $phone, $enable);
    }

    /**
     * Delete notification for IP $ip
     *
     * @param string $ip
     * @param string $notificationId
     */
    public function ipDeleteMonitoringNotification($ip, $notificationId)
    {
        self::getClient()->ipDeleteMonitoringNotification($this->id, $ip, $notificationId);
    }

    /**
     * Get lines
     *
     * @return array of string
     */
    public function getLines()
    {
        return json_decode(self::getClient()->getLines($this->id));
    }


    /**
     * Get line properties
     *
     * @todo : dispacth properties (eg getLineDeconsolidation, ...)
     *
     * @param string $line
     * @return object
     */
    public function getLineProperties($line)
    {
        return json_decode(self::getClient()->getLineProperties($this->id, $line));
    }

    /**
     * Reset Dslam port
     * @todo object task
     *
     * @param string $line
     * @return object Task
     */

    public function lineResetDslamPort($line)
    {
        return json_decode(self::getClient()->lineResetDslamPort($this->id, $line));
    }


    /**
     * @todo "Operator sfr not supported"
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function lineChangeDslamProfile()
    {
        throw new NotImplementedYetException;
    }

    /**
     * Get available Dslam profile for line $line
     *
     * @param $line
     * @return array of object DslamLineProfile
     */
    public function lineGetAvailableDslamProfiles($line)
    {
        return json_decode(self::getClient()->lineGetAvailableDslamProfiles($this->id, $line));
    }

    /**
     * returm modem properties
     *
     * @return object modem
     */
    public function getModemProperties()
    {
        return json_decode(self::getClient()->getModemProperties($this->id));
    }

    /**
     * Enable IPv6 routing
     *
     * @return object task
     */
    public function enableIpv6()
    {
        return json_decode(self::getClient()->enableIpv6($this->id));
    }

    /**
     * Disable IPv6 routing
     *
     * @return object task
     */
    public function disableIpv6()
    {
        return json_decode(self::getClient()->disableIpv6($this->id));
    }

    /**
     * Resquest ppp credentials (will be sent by mail)
     */
    public function getPppLoginByMail()
    {
        self::getClient()->getPppLoginByMail($this->id);
    }

    /**
     * @todo waiting for sandbox...
     *
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function requestTotalDeconsolidation()
    {
        throw new NotImplementedYetException;
    }

    /**
     * Get available Lns
     *
     * @return array of Lns Object (@todo lns object)
     */
    public function getAvailableLns()
    {
        return json_decode(self::getClient()->getAvailableLns($this->id));
    }


    /**
     * Change Lns
     *
     * @param  string $lnsName
     * @return object task
     */
    public function changeLns($lnsName)
    {
        return json_decode(self::getClient()->changeLns($this->id, $lnsName));
    }


}
