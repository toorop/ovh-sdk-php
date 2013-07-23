<?php
/**
 * Copyright 2013 Florian Jensen (aka flosoft)
 * based on work by Stéphane Depierrepont (aka Toorop)
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


namespace Ovh\Dedicated\Server;

use Guzzle\Http\Exception\ClientErrorResponseException;
#use Guzzle\Http\Exception\BadResponseException;
#use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Common\Exception\NotImplementedYetException;
//use Ovh\Vps\Exception\VpsNotFoundException;
use Ovh\Dedicated\Server\Exception\ServerException;


class serverClient extends AbstractClient
{

	/**
	 * Get properties
	 *
	 * @param string $domain
	 * @return string Json
	 * @throws Exception\ServerException
	 * @throws Exception\ServerNotFoundException
	 */
	public function getProperties($domain)
	{
		try {
			$r = $this->get('dedicated/server/' . $domain)->send();
		} catch (\Exception $e) {
			throw new ServerException($e->getMessage(), $e->getCode(), $e);
		}
		return $r->getBody(true);
	}

	/**
	 * Get Service Infos
	 *
	 * @param string $domain
	 * @return string Json
	 * @throws Exception\ServerException
	 * @throws Exception\ServerNotFoundException
	 */
	public function getServiceInfos($domain)
	{
		try {
			$r = $this->get('dedicated/server/' . $domain . '/serviceInfos')->send();
		} catch (\Exception $e) {
			throw new ServerException($e->getMessage(), $e->getCode(), $e);
		}
		return $r->getBody(true);
	}


	/**
	 * Tasks associated to this virtual server
	 *
	 * @param string $domain
	 * @return mixed
	 * @throws \Ovh\Common\Exception\BadMethodCallException
	 * @throws Exception\ServerException
	 */
	public function getTasks($domain)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		try {
			$r = $this->get('dedicated/server/' . $domain . '/task')->send();
		} catch (\Exception $e) {
			throw new ServerException($e->getMessage(), $e->getCode(), $e);
		}
		return $r->getBody(true);
	}

	/**
	 * Get task properties
	 *
	 * @param $domain
	 * @param $taskId
	 * @return mixed
	 * @throws \Ovh\Common\Exception\BadMethodCallException
	 * @throws Exception\ServerException
	 */
	public function getTaskProperties($domain, $taskId)
	{
		$domain = (string)$domain;
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing.');
		$taskId = (string)$taskId;
		if (!$taskId)
			throw new BadMethodCallException('Parameter $taskId is missing.');
		try {
			$r = $this->get('server/dedicated/' . $domain . '/task/' . $taskId)->send();
		} catch (\Exception $e) {
			throw new ServerException($e->getMessage(), $e->getCode(),$e);
		}
		return $r->getBody(true);
	}

}