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

/**
 * @todo remove NOT USED
 */

namespace Ovh\Vps\Exception;

use Ovh\Vps\Exception\VpsException;

class VpsExceptionHandler
{

    public function  __construct()
    {

    }

    public static function handle($e,array $opt=array())
    {
        #print get_class($e);

        #$response=$e->getResponse();
        #$statusCode=$response->getStatusCode();
        #var_dump($statusCode);
        throw new VpsException($e->getMessage(),$e->getCode());
        #die();
    }


}
