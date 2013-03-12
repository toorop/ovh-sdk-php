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

namespace Ovh\Common;

use Ovh\Common\AbstractClient;

class OvhClient extends AbstractClient {

    /**
     * Return VPS list
     *
     * @return mixed
     */
    public function getVpsList(){
        $request = $this->get('vps');
        $response=$request->send();
        return $response->getBody(true);
    }

    /**
     * Get XDSL Services
     *
     * @return json encoded string
     */
    public function getXdslServices(){
        return $this->get('xdsl')->send()->getBody(true);
    }

    /**
     * Get CDN services
     *
     * @return json string
     */
    public function getCdnServices(){
        return $this->get('cdn')->send()->getBody(true);
    }

}


