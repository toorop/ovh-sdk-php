<?php
/**
 * Copyright 2013 Stéphane Depierrepont (aka Toorop)
 *
 * Authors :
 *  - Stéphane Depierrepont (aka Toorop)
 *  - Florian Jensen (aka flosoft) : https://github.com/flosoft
 *  - Gillardeau Thibaut (aka Thibautg16)
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

namespace Ovh\Dedicated\Nasha;

use Ovh\Common\Exception\NotImplementedYetException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;
use Ovh\Common\Ovh;
use Ovh\Dedicated\Nasha\NashaClient;
use Ovh\Common\Task;

class Nasha
{
	private $domain = null;
	private static $nashaClient = null;

	/**
	 * @param string $domain
	 */
	 
	public function __construct($domain)
	{
		$this->setNasha($domain);
	}

	/**
	 * Return Dedicated Server client
	 *
	 * @return null|serverClient
	 */
	private static function getClient()
	{
		if (!self::$nashaClient instanceof ServerClient){
			self::$nashaClient=new ServerClient();
		};
		return self::$nashaClient;
	}

	/**
	 * Set nasha
	 *
	 * @param string $domain
	 */
	public function setNasha($domain)
	{
		$this->nasha = $domain;
	}

	/**
	 * Get domain
	 *
	 * @return null | string domain
	 */
	public function getNasha()
	{
		return $this->nasha;
	}


	###
	# Mains methods from OVH Rest API
	##

	/**
	 *  Get properties
	 *
	 *  @return object
	 *
	 */
	public function getnashaProperties()
	{
		return json_decode(self::getClient()->getProperties($this->getNasha()));
	}


/*
{get}	/dedicated/nasha/{serviceName}
{put}	/dedicated/nasha/{serviceName}
{get}	/dedicated/nasha/{serviceName}/partition
{post}	/dedicated/nasha/{serviceName}/partition
{get}	/dedicated/nasha/{serviceName}/partition/{partitionName}
{put}	/dedicated/nasha/{serviceName}/partition/{partitionName}
{delete}/dedicated/nasha/{serviceName}/partition/{partitionName}
{get}	/dedicated/nasha/{serviceName}/partition/{partitionName}/access
{post}	/dedicated/nasha/{serviceName}/partition/{partitionName}/access
{get}	/dedicated/nasha/{serviceName}/partition/{partitionName}/access/{ip}
{delete}/dedicated/nasha/{serviceName}/partition/{partitionName}/access/{ip}
{get}	/dedicated/nasha/{serviceName}/partition/{partitionName}/authorizableBlocks
{get}	/dedicated/nasha/{serviceName}/partition/{partitionName}/authorizableIps
{get}	/dedicated/nasha/{serviceName}/partition/{partitionName}/snapshot
{post}	/dedicated/nasha/{serviceName}/partition/{partitionName}/snapshot
{get}	/dedicated/nasha/{serviceName}/partition/{partitionName}/snapshot/{snapshotType}
{delete}/dedicated/nasha/{serviceName}/partition/{partitionName}/snapshot/{snapshotType}
{get}	/dedicated/nasha/{serviceName}/serviceInfos
{put}	/dedicated/nasha/{serviceName}/serviceInfos
{get}	/dedicated/nasha/{serviceName}/task
{get}	/dedicated/nasha/{serviceName}/task/{taskId}
*/






}
