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


use Ovh\Common\Exception\BadConstructorCallException;
use Ovh\Cloud\CloudClient;
use Ovh\Cloud\Pca;

/**
 * Cloud class
 */
class Cloud 
{
	private $passport=null;

	private static $CloudClient=null;

    /**
     * Constructor
     *
     * @param $passport
     * @throws \Ovh\Common\Exception\BadConstructorCallException;
     */
    public function __construct($passport) 
    {
		if (!$passport ) {
			throw new BadConstructorCallException( '$passport parameter is missing', 1 );
		}
        $this->passport=$passport;
	}


    /**
     * Return Cloud client
     *
     * @return object cloudClient
     */
    private static function getClient()
    {
        if (!self::$CloudClient instanceof CloudClient) {
            self::$CloudClient = new CloudClient();
        }
        return self::$CloudClient;
    }


    /**
     * Get PCA services associated with this cloud passport
     *
     * @return array of services
     */
    public function getPcaServices()
    {
        return json_decode(self::getClient()->getPcaServices($this->passport));
    }

    /**
     * Return PCA instance
     *
     * @param string $pcaName
     * @return object Pca
     */
    public function getPca($pcaName)
    {
        return new Pca($this->passport,$pcaName);
    }
    
    /***** OVH Cloud *****/
    /**
    * Get Project Properties
    * GET /cloud/project/{serviceName}
    *
    * @return
    */
    public function getProjectProperties(){
        return json_decode(self::getClient()->getProjectProperties($this->passport));
    }    
    
    /**
    * Delete Instances of Project Cloud Public
    * DELETE /cloud/project/{serviceName}/instance/{instanceId}
    *
    * @return null
    */
    public function deleteInstance($idInstance){
        return json_decode(self::getClient()->deleteInstance($this->passport, $idInstance));
    } 
    
    /**
    * POST Reboot Instance
    * POST /cloud/project/{serviceName}/instance/{instanceId}/reboot 
    *
    * @return
    */
    public function postInstanceReboot($idInstance, $type){
        return json_decode(self::getClient()->postInstanceReboot($this->passport, $idInstance, $type));
    } 
 
     /**
    * POST Snapshot Instance
    * POST /cloud/project/{serviceName}/instance/{instanceId}/snapshot
    *
    * @return
    */
    public function postInstanceSnapshot($idInstance, $snapshotName){
        return json_decode(self::getClient()->postInstanceSnapshot($this->passport, $idInstance, $snapshotName));
    } 
       
    /**
    * Get Project Balance
    * GET /cloud/project/{serviceName}/balance
    *
    * @return
    */
    public function getProjectBalance(){
        return json_decode(self::getClient()->getProjectBalance($this->passport));
    }            

    /**
    * Get Project Quota
    * GET /cloud/project/{serviceName}/quota
    *
    * @return
    */
    public function getProjectQuota(){
        return json_decode(self::getClient()->getProjectQuota($this->passport));
    }   
                
    /**
    * Get Public Cloud Project Instance
    * GET /cloud/project/{serviceName}/instance
    *
    * @return array of Public Cloud Project Instance
    */
    public function getProjectInstance(){
        return json_decode(self::getClient()->getProjectInstance($this->passport));
    }       
}