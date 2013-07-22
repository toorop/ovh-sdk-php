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

namespace Ovh\Vps;


use Ovh\Common\Exception\NotImplementedYetException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;

use Ovh\Common\Ovh;
use Ovh\Vps\VpsClient;
use Ovh\Common\Task;


class Vps
{
    private $domain = null;
    private static $vpsClient = null;

    /**
     * @param string $domain
     */
    public function __construct($domain)
    {
        $this->setDomain($domain);
    }


    /**
     * Return VPS client
     *
     * @return null|VpsClient
     */
    private static function getClient()
    {
        if (!self::$vpsClient instanceof VpsClient){
            self::$vpsClient=new VpsClient();
        };
        return self::$vpsClient;
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
     *  Get VPS properties
     *
     *  @return object
     *
     */
    public function  getProperties()
    {
        return json_decode(self::getClient()->getProperties($this->getDomain()));
    }


    /**
     * Get VPS status
     *
     * @return object
     */
    public function getStatus()
    {
        return json_decode(self::getClient()->getStatus($this->getDomain()));
    }


    /**
     *  Get monitoring
     *
     * @param string $period "lastday" or "lastmonth" or "lastweek" or "lastyear" or "today"
     * @param string $type "cpu:max" or "cpu:used" or "mem:max" or "mem:used" or "net:rx" or "net:tx"
     * @return object
     */
    public function getMonitoring($period, $type)
    {
        return json_decode(self::getClient()->getMonitoring($this->getDomain(), $period, $type));
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getCurrentMonitoring($type)
    {
        return json_decode(self::getClient()->getCurrentMonitoring($this->getDomain(), $type));
    }


    /**
     * Start VPS
     *
     * @return Task
     */
    public function start()
    {
        return new Task(self::getClient()->start($this->getDomain()));
    }


    /**
     * Stop VPS
     *
     * @return Task
     */
    public function stop()
    {
        return new Task(self::getClient()->stop($this->getDomain()));
    }


    /**
     * Reboot VPS
     *
     * @return Task
     */
    public function reboot()
    {
        return new Task(self::getClient()->reboot($this->getDomain()));
    }

    /**
     * @param $password
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function setRootPassword($password)
    {
        throw new NotImplementedYetException;
        #$r = self::getClient()->setRootPassword($this->getDomain(), $password);
        #return;
    }


    /**
     * Get availables Upgrades
     *
     * @return array
     */
    public function getAvailableUpgrades()
    {
        return json_decode(self::getClient()->getAvailableUpgrades($this->getDomain()));
    }

    /**
     * Get available options
     *
     * @return array
     */
    public function getAvailableOptions()
    {
        return json_decode(self::getClient()->getAvailableOptions($this->getDomain()));
    }


    /**
     * Get available models
     *
     * @return array
     */
    public function getModels()
    {
        return json_decode(self::getClient()->getModels($this->getDomain()));
    }

    /**
     * @todo : Waiting for OVH  : 500 ftpbackup option is not orderable
     *
     * @return mixed
     */
    public function orderCpanelLicense()
    {
        throw new NotImplementedYetByOvhException;
        return self::getClient()->orderCpanelLicense($this->getDomain());
    }

    /**
     * @todo : Waiting for OVH
     *
     * @param $qte
     * @return mixed
     */
    public function orderPleskLicence($qte)
    {
        throw new NotImplementedYetByOvhException;
        return  self::getClient()->orderPleskLicense($this->getDomain(), $qte);
    }


    /**
     * @todo : Only Cloud model are able to order a snapshot
     * @return mixed
     */
    public function orderFtpBackup()
    {
        throw new NotImplementedYetException;
        return self::getClient()->orderFtpBackup($this->getDomain());
    }

    # Disk methods

    /**
     * Return disks ID
     *
     * @return array  of disk id
     */
    public function getDisks()
    {
        return json_decode(self::getClient()->getDisks($this->getDomain()));

    }

    /**
     * Return disk properties
     *
     * @param $diskId
     * @return object disk (@todo object disk)
     */
    public function getDiskProperties($diskId)
    {
       return json_decode(self::getClient()->getDiskProperties($this->getDomain(), $diskId));
    }

    /**
     * Get disk usage
     *
     * @param $diskId
     * @param $type
     * @return object
     */
    public function getDiskUsage($diskId, $type)
    {
        return json_decode(self::getClient()->getDiskUsage($this->getDomain(), $diskId, $type));
    }

    /**
     * @param $diskId
     * @param $period
     * @param $type
     * @return mixed
     */
    public function getDiskMonitoring($diskId, $period, $type)
    {
        return json_decode(self::getClient()->getDiskMonitoring($this->getDomain(), $diskId, $period, $type));
    }


    #Task
    /**
     * Return tasks associated with this VPS (current and past)
     *
     * @return array of int
     */
    public function getTasks()
    {
        return json_decode(self::getClient()->getTasks($this->getDomain()));
    }

    /**
     * @param $taskId
     * @return Task
     */
    public function getTaskProperties($taskId)
    {
        return new Task(self::getClient()->getTaskProperties($this->getDomain(), $taskId));
    }

    # IP
    /**
     * Get availables IP of this VPS
     *
     * @return array(IPV4,IPV6)
     */
    public function getIps()
    {
        return json_decode(self::getClient()->getIps($this->getDomain()));

    }

    /**
     * @param $ip
     * @return object (@todo object IP)
     */
    public function getIpProperties($ip)
    {
        return json_decode(self::getClient()->getIpProperties($this->getDomain(), $ip));
    }

    /**
     * @todo : error handling & bad reponse (nullNULL)
     * Set IP properties
     * @param string $ip
     * @param array $properties
     * @return mixed
     */
    public function setIpProperties($ip, $properties)
    {
        return json_decode(self::getClient()->setIpProperties($this->getDomain(), $ip, $properties));
    }


    # Snapshots

    /**
     *
     *
     * @return array
     * @throw VpsSnapshotNotExistsException
     */
    public function getSnapshotProperties()
    {
        return  json_decode(self::getClient()->getSnapshotProperties($this->getDomain()));
    }


    /**
     *
     * @todo no snapshot available yet
     * @param array $properties
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function setSnapshotProperties(array $properties)
    {
        throw new NotImplementedYetException;
        self::getClient()->setSnapshotProperties($this->getDomain(),$properties);

    }

    /**
     * @todo no snapshot available yet
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function deleteSnapshot()
    {
        throw new NotImplementedYetException;
        return self::getClient()->deleteSnapshot($this->getDomain());
    }


    /**
     *  @todo no snapshot available yet
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function revertToLastSnapshot()
    {
        throw new NotImplementedYetException;
        return self::getClient()->revertToLastSnapshot($this->getDomain());
    }

    /**
     * @todo need a cloud VPS
     * @return mixed
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function orderSnapshot()
    {
        throw new NotImplementedYetException;
        return self::getClient()->orderSnapshot($this->getDomain());
    }

    /**
     * @todo need a cloud
     * @return mixed
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function createSnapshot()
    {
        throw new NotImplementedYetException;
        return self::getClient()->createSnapshot($this->getDomain());
    }


    # Templates
    /**
     * Get available template
     *
     * @return array of int
     */
    public function getAvailableTemplates()
    {
        return json_decode(self::getClient()->getAvailableTemplates($this->getDomain()));
    }

    /**
     * @param $templateId
     * @return object template (@todo object template)
     */
    public function getTemplateProperties($templateId)
    {
        return json_decode(self::getClient()->getTemplateProperties($this->getDomain(),$templateId));
    }


    /**
     *
     *
     *  @todo : Sub methods
     *  Ex : getMemoryLimit from getProperties
     *
     *
     *
     */

    public function setIpReverse($ip, $reverse)
    {
        $r = self::getClient()->setIpProperties($this->getDomain(), $ip, $reverse);
        return;
    }


}
