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

use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\InvalidArgumentException;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Common\Exception\NotImplementedYetException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;
use Ovh\Vps\Exception\VpsNotFoundException;
use Ovh\Vps\Exception\VpsException;


class vpsClient extends AbstractClient
{

    /**
     * Get properties
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\VpsException
     * @throws Exception\VpsNotFoundException
     */
    public function getProperties($domain)
    {
        try {
            $r = $this->get('vps/' . $domain)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }

    /**
     * Get VPS status
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getStatus($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/status')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }


    /**
     *
     *
     * @param string $domain
     * @param string $period "lastday" or "lastmonth" or "lastweek" or "lastyear" or "today"
     * @param string $type "cpu:max" or "cpu:used" or "mem:max" or "mem:used" or "net:rx" or "net:tx"
     * @return mixed
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function getMonitoring($domain, $period, $type)
    {
        throw new NotImplementedYetException();
        $domain = (string)$domain;
        $period = strtolower($period);
        $type = strtolower($type);
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        if (!in_array($period, array('lastday', 'lastmonth', 'lastweek', 'lastyear', 'today')))
            throw new BadMethodCallException('Parameter $period must be "lastday" or "lastmonth" or "lastweek" or "lastyear" or "today". "' . $period . '" given.');
        if (!in_array($type, array('cpu:max', 'cpu:used', 'mem:max', 'mem:used', 'net:rx', 'net:tx')))
            throw new BadMethodCallException('Parameter $type must be "cpu:max" or "cpu:used" or "mem:max" or "mem:used" or "net:rx" or "net:tx". "' . $type . '" given.');
        try {
            $r = $this->get('vps/' . $domain . '/monitoring?period=' . $period . '&type=' . $type)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r->getBody();
    }


    /**
     * Get current monitoring by type
     *
     * @param string $domain
     * @param string $type "cpu:max" or "cpu:used" or "mem:max" or "mem:used" or "net:rx" or "net:tx"
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getCurrentMonitoring($domain, $type)
    {
        $domain = (string)$domain;
        $type = strtolower($type);
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        if (!in_array($type, array('cpu:max', 'cpu:used', 'mem:max', 'mem:used', 'net:rx', 'net:tx')))
            throw new \BadMethodCallException('Parameter $type must be "cpu:max" or "cpu:used" or "mem:max" or "mem:used" or "net:rx" or "net:tx". "' . $type . '" given.');
        try {
            $r = $this->get('vps/' . $domain . '/use?type=' . $type)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r;
    }


    /**
     * Start VPS
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function start($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('vps/' . $domain . '/start')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }


    /**
     * Stop VPS
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function stop($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('vps/' . $domain . '/stop')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }

    /**
     * Reboot VPS
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function reboot($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('vps/' . $domain . '/reboot')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }

    # @todo
    public function setRootPassword($domain, $passwd)
    {
        throw new NotImplementedYetException();
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        $passwd = (string)$passwd;
        if (!$passwd)
            throw new \BadMethodCallException('Parameter $passwd is missing.');

        return false;
    }


    /**
     * Get available upgrade for VPS
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getAvailableUpgrades($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/availableUpgrade')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }


    /**
     * Get available Options
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getAvailableOptions($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/availableOptions')->send();
        } catch (ClientErrorResponseException $e) {
            if ($e->getResponse()->getStatusCode() == '404' && json_decode($e->getResponse()->getBody())->message == "No options found")
                return "[]";
            else
                throw new VpsException($e->getMessage(), $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }


    /**
     * Return all models for the range of the virtual server
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getModels($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/models')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }


    /**
     * @param $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws \Ovh\Common\Exception\NotImplementedYetException
     */
    public function orderCpanelLicense($domain)
    {
        throw new NotImplementedYetByOvhException(); // bad reponse ftpbackucp
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('vps/' . $domain . '/orderCpanelLicense')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r;
    }


    /**
     * @param string $domain
     * @param string $qty enum 1 5 10 100 300 unlimited
     * @return mixed
     * @throws Exception\VpsException
     * @throws BadMethodCallException
     * @throws Exception\VpsNotFoundException
     * @throws NotImplementedYetByOvhException
     */
    public function orderPleskLicense($domain, $qty)
    {
        throw new NotImplementedYetByOvhException(); // Item parameter invalid"
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $qty = (string)$qty;
        if (!in_array($qty, array('1', '5', '10', '100', '300', 'unlimited')))
            throw new BadMethodCallException('Parameter $qty must be "1","5","10","100","300","unlimited". ' . $qty . ' given');
        $payload = array('item' => (string)$qty);
        try {
            $r = $this->post('vps/' . $domain . '/orderPleskLicense', null, $payload)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r;
    }


    #

    /**
     * @param $domain
     * @return mixed
     * @throws \BadMethodCallException
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     * @throws Exception\VpsException
     */
    public function orderFtpBackup($domain)
    {
        throw new NotImplementedYetByOvhException(); // Only Cloud model are able to order a snapshot
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('vps/' . $domain . '/orderFtpBackup')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r;
    }


    /**
     * Disks associated to this VPS
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getDisks($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/disks')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }

    /**
     * @param $domain
     * @param $diskId
     * @return mixed
     * @throws \BadMethodCallException
     * @throws Exception\VpsException
     */
    public function getDiskProperties($domain, $diskId)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        $diskId = (string)$diskId;
        if (!$diskId)
            throw new \BadMethodCallException('Parameter $diskId is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/disks/' . $diskId)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }

    /**
     * Return many statistics about the disk at that time
     *
     * @param $domain
     * @param $diskId
     * @param $type
     * @return mixed
     * @throws \BadMethodCallException
     * @throws \Ovh\Common\Exception\NotImplementedYetByOvhException
     * @throws Exception\VpsException
     */
    public function getDiskUsage($domain, $diskId, $type)
    {
        throw new NotImplementedYetByOvhException();
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        $diskId = (string)$diskId;
        if (!$diskId)
            throw new \BadMethodCallException('Parameter $diskId is missing.');
        $type = strtolower($type);
        if (!$type)
            throw new \BadMethodCallException('Parameter $type is missing.');
        if (!in_array($type, array('max', 'used')))
            throw new \BadMethodCallException('Parameter $type must be "max" or "used". ' . $type . ' given.');
        try {
            $r = $this->get('vps/' . $domain . '/disks/' . $diskId . '/use?type=' . $type)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r;
    }


    /**
     * @todo badsig
     * @param string $domain
     * @param int|string $diskId (will be casted ti string)
     * @param string $period
     * @param string $type
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getDiskMonitoring($domain, $diskId, $period, $type)
    {
        throw new NotImplementedYetByOvhException();
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        $diskId = (string)$diskId;
        if (!$diskId)
            throw new \BadMethodCallException('Parameter $diskId is missing.');
        $period = strtolower($period);
        if (!in_array($period, array('lastday', 'lastmonth', 'lastweek', 'lastyear', 'today')))
            throw new \BadMethodCallException('Parameter $period must be "lastday" or "lastmonth" or "lastweek" or "lastyear" or "today". ' . $period . ' given.');
        $type = strtolower($type);
        if (!$type)
            throw new \BadMethodCallException('Parameter $type is missing.');
        if (!in_array($type, array('max', 'used')))
            throw new \BadMethodCallException('Parameter $type must be "max" or "used". ' . $type . ' given.');
        try {
            $r = $this->get('vps/' . $domain . '/disks/' . $diskId . '/monitoring?type=' . $type . '&period=' . $period)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r;
    }


    /**
     * Tasks associated to this virtual server
     *
     * @param $domain
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getTasks($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/tasks')->send();
            ;
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r->getBody();
    }

    /**
     * Get task properties
     *
     * @param $domain
     * @param $taskId
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getTaskProperties($domain, $taskId)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        $taskId = (string)$taskId;
        if (!$taskId)
            throw new \BadMethodCallException('Parameter $taskId is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/tasks/' . $taskId)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r->getBody();
    }


    /**
     * Get Ips associated to VPS $domain
     *
     * @param strin f $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getIps($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/ips')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r->getBody();
    }


    /**
     * Get IP properties
     *
     * @param string $domain
     * @param string $ip
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getIpProperties($domain, $ip)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        $ip = (string)$ip;
        if (!$ip)
            throw new \BadMethodCallException('Parameter $ip is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/ips/' . $ip)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r->getBody();
    }


    /**
     * @param $domain
     * @param $ip
     * @param $properties
     * @return mixed
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\VpsException
     */
    public function setIpProperties($domain, $ip, $properties)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new BadMethodCallException('Parameter $domain is missing.');
        $ip = (string)$ip;
        if (!$ip)
            throw new BadMethodCallException('Parameter $ip is missing.');
        if (!$properties)
            throw new BadMethodCallException('Parameter $properties is missing.');
        try {
            $r = $this->put('vps/' . $domain . '/ips/' . $ip, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($properties))->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r;
    }


    /**
     *
     * @param string $domain
     * @return jsons
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getSnapshotProperties($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/snapshot')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }

    /**
     * Set properties of a snapshot
     *
     * @param string $domain
     * @param array $properties
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function setSnapshotProperties($domain, array $properties)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        if (!$properties || !is_array($properties) || count($properties) == 0)
            throw new \BadMethodCallException('Parameter $properties must be a non empty array.');
        $qr = '';
        foreach ($properties as $k => $v) {
            $qr .= $k . '=' . $v . '&';
        }
        $qr = substr($qr, 0, strlen($qr) - 1);
        try {
            $r = $this->put('vps/' . $domain . '/snapshot/?' . $qr)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r;
    }


    /**
     * Delete snapshot of this VPS
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function deleteSnapshot($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->delete('vps/' . $domain . '/snapshot')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r;
    }


    /**
     * Revert to last snapshot
     *
     * @param $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function revertToLastSnapshot($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('vps/' . $domain . '/snapshot')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r;
    }

    /**
     * Order a snapshot
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function orderSnapshot($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('vps/' . $domain . '/orderSnapshot')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r;
    }

    /**
     * Create a snapshot
     * @param $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function createSnapshot($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->post('vps/' . $domain . '/createSnapshot')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode());
        }
        return $r;
    }

    /**
     * Get availables templates for VPS $domain
     *
     * @param string $domain
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getAvailableTemplates($domain)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        try {
            $r = $this->get('vps/' . $domain . '/templates')->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }


    /**
     * Get template properties
     *
     * @param string $domain
     * @param int|string $templateId (will be casted to string when building request)
     * @return mixed
     * @throws Exception\VpsException
     * @throws \BadMethodCallException
     * @throws Exception\VpsNotFoundException
     */
    public function getTemplateProperties($domain, $templateId)
    {
        $domain = (string)$domain;
        if (!$domain)
            throw new \BadMethodCallException('Parameter $domain is missing.');
        $templateId = (string)$templateId;
        if (!$templateId)
            throw new \BadMethodCallException("Parameter $templateId is missing");
        try {
            $r = $this->get('vps/' . $domain . '/templates/' . $templateId)->send();
        } catch (\Exception $e) {
            throw new VpsException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody();
    }

    # YEAH, all is done !!!!!!!!
}