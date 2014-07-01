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

use Guzzle\Http\Message\Response;
use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Dedicated\Nasha\Exception\NashaException;

class NashaClient extends AbstractClient
{

    /**
     * Get properties
     *
     * @param string $nasha
     * @return string Json
     * @throws Exception\ServerException
     * @throws Exception\ServerNotFoundException
     */
    public function getnashaProperties($nasha)
    {
        try {
            $r = $this->get('dedicated/nasha/' . $nasha)->send();
        } catch (\Exception $e) {
            throw new NashaException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


}