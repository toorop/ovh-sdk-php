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
// @todo create a common exception client and extends from it

namespace Ovh\Cdn\Exception;

use Guzzle\Common\Exception\RuntimeException;
use Ovh\Common\Exception\InvalidResourceException;
use Ovh\Common\Exception\InvalidSignatureException;
use Ovh\Cdn\Exception\CdnDomainAlreadyConfiguredException;
use Guzzle\Http\Message\Response; // for debugging only
use Guzzle\Http\Message\Request;

class CdnException extends \RuntimeException
{

    public function __construct($message = '', $code = 0, $prev)
    {

        $request = $prev->getRequest();
        $response = $prev->getResponse();

        /*var_dump($response->getStatusCode());
        var_dump($response->getReasonPhrase());
        var_dump($request->getPath());
        die();*/


        $statusCode = $response->getStatusCode();
        switch ($statusCode) {
            case 404 :
                if ($response->getReasonPhrase() == "This service does not exist") {
                    throw new InvalidResourceException('Ressource ' . $request->getMethod() . ' ' . $request->getResource() . ' does not exist. Service name does not exists.', 404);
                } elseif (stristr((string)$response->getBody(), 'The requested object') && stristr((string)$response->getBody(), 'does not exist')) {
                    throw new InvalidResourceException('Ressource ' . $request->getMethod() . ' ' . $request->getResource() . ' does not exist.' . $response->getReasonPhrase(), 404);
                } else throw new RuntimeException($response->getReasonPhrase(), 404);
                break; // not really usefull but...


            case 400 :
                // Bad signature
                if ($response->getReasonPhrase() == "Bad Request - Invalid signature") {
                    throw new InvalidSignatureException('The request signature is not valid.', 400);
                } elseif ($response->getReasonPhrase() == "This rule is being update on CDN, please wait few minutes") {
                    throw new CdnUpdateInProgressException();
                } else {
                    throw $prev;
                }
                break;


            case 409:
                if ($response->getReasonPhrase() == "CDN already configured for this domain") {
                    throw new CdnDomainAlreadyConfiguredException();
                } elseif ($response->getReasonPhrase() == "Active Task detected") {
                    throw new CdnUpdateInProgressException();
                } else {
                    throw $prev;
                }
                break;

            default :
                throw $prev;
        }
    }

    public function debug()
    {
        $r = new Response();
        var_dump($r->getReasonPhrase());
        $req = new Request();
        $req->getParams();
        $req->getPath();
    }

}
