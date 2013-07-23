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
	 * Get properties
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

}