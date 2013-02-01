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

class NotImplementedYetByOvhException extends \RuntimeException
{
    public function __construct($msg='', $code=0, \Exception $prev=null)
    {
        parent::__construct($msg, $code, $prev);
        $this->message="Not implemented yet by OVH (or there are still errors or not correctly implemented). Follow me on twitter @toorop_ . Follow project on Github : https://github.com/Toorop/ovh-sdk-php";
    }
}

