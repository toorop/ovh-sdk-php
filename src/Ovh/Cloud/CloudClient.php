<?php
/**
 * Copyright 2013 Stéphane Depierrepont (aka Toorop)
 *
 * Authors :
 *  - Stéphane Depierrepont (aka Toorop)
 *  - Gillardeau Thibaut (aka Thibautg16)
 *
 *  Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://www.apache.org/licenses/LICENSE-2.0.txt
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * 2015-05-22 - Thibautg16 - ajout "new" /cloud : http://www.ovh.com/fr/cloud/
 */

namespace Ovh\Cloud;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Cloud\Exception\CloudException;


class CloudClient extends AbstractClient
{

    /**
     * Get PCA services associated with this cloud passport
     *
     * @param string $pp OVH cloud passport
     * @return string (json encoded array)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaServices($pp)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (passport).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca')->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * Return properties of PCA $pca service name
     *
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @return string (Json encode object)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaProperties($pp, $pca)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCAS erviceName).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca/' . $pca)->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Set a SSH public key to PCA
     *
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @param string $key
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function setSshKey($pp, $pca, $key)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        if (!$key)
            throw new BadMethodCallException('Missing parameter $key (Public key for this pca).');
        $payload = array('sshkey' => $key);
        try {
            $this->put('cloud/' . $pp . '/pca/' . $pca, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Set password to PCA
     *
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @param string $passwd
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function setPassword($pp, $pca, $passwd)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        if (!$passwd)
            throw new BadMethodCallException('Missing parameter $passwd (Password for this pca).');
        $payload = array('password' => $passwd);
        try {
            $this->put('cloud/' . $pp . '/pca/' . $pca, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($payload))->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @return string (json encoded object)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaInfo($pp, $pca)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca/' . $pca . '/serviceInfos')->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @return string (json encoded array)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaSessions($pp, $pca)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca/' . $pca . '/sessions')->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @param string $sessId PCA service name
     * @return string (json encoded object)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaSessionProperties($pp, $pca, $sessId)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        if (!$sessId)
            throw new BadMethodCallException('Missing parameter $sessId (PCA Session ID).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca/' . $pca . '/sessions/' . $sessId)->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @param string $sessId PCA service name
     * @return string (json encoded array)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaSessionFiles($pp, $pca, $sessId)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        if (!$sessId)
            throw new BadMethodCallException('Missing parameter $sessId (PCA Session ID).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca/' . $pca . '/sessions/' . $sessId . '/files')->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @param string $sessId PCA service name
     * @param string $fileId PCA service name
     * @return string (json encoded array)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaSessionFilesProperties($pp, $pca, $sessId, $fileId)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        if (!$sessId)
            throw new BadMethodCallException('Missing parameter $sessId (PCA Session ID).');
        if (!$fileId)
            throw new BadMethodCallException('Missing parameter $fileId (file ID).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca/' . $pca . '/sessions/' . $sessId . '/files/' . $fileId)->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @return string json encoded array)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaTasks($pp, $pca)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca/' . $pca . '/tasks')->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @param string $sessionId session id to delete
     * @return string json encoded array
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function createPcaDeleteTask($pp, $pca, $sessionId)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        if (!$sessionId)
             throw new BadMethodCallException('Missing parameter $sessionId (string).');
        try {
            $r = $this->delete('cloud/' . $pp . '/pca/' . $pca . '/sessions/' . $sessionId)->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @param string $sessionId session id to restore
     * @return string json encoded array
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function createPcaRestoreTask($pp, $pca, $sessionId)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        if (!$sessionId)
             throw new BadMethodCallException('Missing parameter $sessionId (string).');
        try {
            $r = $this->post('cloud/' . $pp . '/pca/' . $pca . '/sessions/' . $sessionId . '/restore')->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @param string $taskId task ID
     * @return string json encoded array)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaTaskProperties($pp, $pca, $taskId)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        if (!$taskId)
            throw new BadMethodCallException('Missing parameter $taskId (ID of the tasks).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca/' . $pca . '/tasks/' . $taskId)->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * @param string $pp OVH cloud passport
     * @param string $pca PCA service name
     * @return string json encoded array)
     * @throws \Ovh\Cloud\Exception\CloudException
     * @throws \Ovh\Common\Exception\BadMethodCallException
     */
    public function getPcaUsage($pp, $pca)
    {
        if (!$pp)
            throw new BadMethodCallException('Missing parameter $pp (OVH cloud passport).');
        if (!$pca)
            throw new BadMethodCallException('Missing parameter $pca (PCA ServiceName).');
        try {
            $r = $this->get('cloud/' . $pp . '/pca/' . $pca . '/usage')->send();
        } catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /***** OVH Cloud *****/
    /**
    * Get Project Properties
    * GET /cloud/project/{serviceName}
    *
    * @return 
    * @throws \Ovh\Cloud\Exception\CloudException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getProjectProperties($serviceName){
        if (!$serviceName)
            throw new BadMethodCallException('Missing parameter $serviceName (OVH Cloud Project).');
        try {
            $r = $this->get('cloud/project/'.$serviceName)->send();
        }
        catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
    
    /**
    * Delete Instances of Project Cloud Public
    * DELETE /cloud/project/{serviceName}/instance/{instanceId}
    *
    * @return 
    * @throws \Ovh\Cloud\Exception\CloudException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function deleteInstance($serviceName, $idInstance){
        if (!$serviceName)
            throw new BadMethodCallException('Missing parameter $serviceName (OVH Cloud Project).');
        if (!$idInstance)
            throw new BadMethodCallException('Missing parameter $idInstance (OVH Cloud Project Instance ID).');
       
       try {
            $r = $this->delete('cloud/project/'.$serviceName.'/instance/'.$idInstance)->send();
        }
        catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }       
    
    /**
    * POST Reboot Instance
    * POST /cloud/project/{serviceName}/instance/{instanceId}/reboot
    *
    * @return null
    * @throws \Ovh\Cloud\Exception\CloudException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function postInstanceReboot($serviceName, $idInstance, $type='soft'){
        if (!$serviceName)
            throw new BadMethodCallException('Missing parameter $serviceName (OVH Cloud Project).');
        if (!$idInstance)
            throw new BadMethodCallException('Missing parameter $idInstance (OVH Cloud Instance ID).');           
        
        $post = array(
            'type' => $type
		);
         
        try {
            $r = $this->post('cloud/project/'.$serviceName.'/instance/'.$idInstance.'/reboot', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($post))->send();
        }
        catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
    * POST Snapshot Instance
    * POST /cloud/project/{serviceName}/instance/{instanceId}/snapshot
    *
    * @return null
    * @throws \Ovh\Cloud\Exception\CloudException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function postInstanceSnapshot($serviceName, $idInstance, $snapshotName){
        if (!$serviceName)
            throw new BadMethodCallException('Missing parameter $serviceName (OVH Cloud Project).');
        if (!$idInstance)
            throw new BadMethodCallException('Missing parameter $idInstance (OVH Cloud Instance ID).');           
        if (!$snapshotName)
            throw new BadMethodCallException('Missing parameter $snapshotName (OVH Cloud Instance Snapshot Name).');          
        $post = array(
            'snapshotName' => $snapshotName
		);
         
        try {
            $r = $this->post('cloud/project/'.$serviceName.'/instance/'.$idInstance.'/snapshot', array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($post))->send();
        }
        catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }    
    
    /**
    * Get Project Balance
    * GET /cloud/project/{serviceName}/balance
    *
    * @return 
    * @throws \Ovh\Cloud\Exception\CloudException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getProjectBalance($serviceName){
        if (!$serviceName)
            throw new BadMethodCallException('Missing parameter $serviceName (OVH Cloud Project).');
        try {
            $r = $this->get('cloud/project/'.$serviceName.'/balance')->send();
        }
        catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
    * Get Project Quota
    * GET /cloud/project/{serviceName}/quota
    *
    * @return 
    * @throws \Ovh\Cloud\Exception\CloudException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getProjectQuota($serviceName){
        if (!$serviceName)
            throw new BadMethodCallException('Missing parameter $serviceName (OVH Cloud Project).');
        try {
            $r = $this->get('cloud/project/'.$serviceName.'/quota')->send();
        }
        catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
                      
    /**
    * Get Instances of Project Cloud Public
    * GET /cloud/project/{serviceName}/instance
    *
    * @return 
    * @throws \Ovh\Cloud\Exception\CloudException
    * @throws \Ovh\Common\Exception\BadMethodCallException
    */
    public function getProjectInstance($serviceName){
        if (!$serviceName)
            throw new BadMethodCallException('Missing parameter $serviceName (OVH Cloud Project).');
        try {
            $r = $this->get('cloud/project/'.$serviceName.'/instance')->send();
        }
        catch (\Exception $e) {
            throw new CloudException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }       
}