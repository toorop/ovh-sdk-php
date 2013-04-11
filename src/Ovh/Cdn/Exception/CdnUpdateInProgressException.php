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

namespace Ovh\Cdn\Exception;

class CdnUpdateInProgressException  extends \RuntimeException
{
    public function __construct($code=0,$prevException=null){
        parent::__construct('There is an already a task in progress for this kind of request. Please wait a few minutes and retry.',$code,$prevException);
    }
}
