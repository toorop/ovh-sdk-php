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


namespace Ovh\Xdsl\Exception;

use Ovh\Common\Exception\InvalidResourceException;
use Ovh\Common\Exception\InvalidSignatureException;
use Ovh\Xdsl\Exception\XdslBaseException;
use Ovh\Xdsl\Exception\XdslNotificationAlreadyExistsException;
use Ovh\Xdsl\Exception\XdslThereIsAlreadyATaskException;

use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\Request;

class XdslException extends \RuntimeException
{
    public function __construct($message = '', $code = 0, $prev)
    {
        $request = $prev->getRequest();
        $response = $prev->getResponse();
        $statusCode = $response->getStatusCode();

        switch ($statusCode) {

            case 400 :
                // Bad signature
                if ($response->getReasonPhrase() == "Bad Request - Invalid signature")
                    throw new InvalidSignatureException('The request signature is not valid.', 400);
                throw $prev;

            case 403 :
                throw new XdslBaseException($response->getReasonPhrase(), 403);

            case 404 :
                // Bad Method or Ressource not available
                if (stristr((string)$response->getBody(true), 'Object') && stristr((string)$response->getBody(true), 'does not exist'))
                    throw new InvalidResourceException('Ressource ' . $request->getMethod() . ' ' . $request->getResource() . ' does not exist', 404);
                throw $prev;

            case 500 :
                // Notification already exist
                if ($response->getReasonPhrase() == 'A notification already exists with the same email/phone')
                    throw new XdslNotificationAlreadyExistsException('A notification already exists with the same email/phone.', 400);
                if (stristr($response->getReasonPhrase(), 'There is already a task with id'))
                    throw new XdslThereIsAlreadyATaskException($response->getReasonPhrase(), 400);
                throw $prev;


            default :
                throw $prev;
        }

    }


}
