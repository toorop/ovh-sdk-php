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

namespace Ovh\Common\Exception;

use Guzzle\Http\Message\Response;

class ServiceResponseException extends \RuntimeException
{

    protected $response;
    protected $type; // client/server
    protected $httpCode;



    public function __construct(Response $response, $code = 0, $prevException = NULL)
    {
        $this->response=$response;
        $this->httpCode=$response->getStatusCode();

        $message = $this->response->getBody(true);
        if ($j=json_decode($message)){
            $message=$j->message;
        }


        # ([ string $message = "" [, int $code = 0 [, Exception $previous = NULL ]]] )
        parent::__construct($message,0,$prevException);


    }


}
