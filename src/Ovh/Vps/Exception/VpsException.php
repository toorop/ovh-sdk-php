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

namespace Ovh\Vps\Exception;

use Ovh\Common\Exception\InvalidResourceException;
use Ovh\Common\Exception\InvalidSignatureException;

use Ovh\Vps\Exception\VpsSnapshotDoesNotExistsException;
use Ovh\Vps\Exception\VpsSnapshotIsOnlyForCloudException;

use Guzzle\Http\Message\Response;     // for debugging only
use Guzzle\Http\Message\Request;

class VpsException extends \RuntimeException
{
    public function __construct($message = '', $code = 0, $prev)
    {
        #$pe=$this->getPrevious();
        #var_dump(get_class($prev));
        $request=$prev->getRequest();
        #print $request->getResource();
        #die();
        $response=$prev->getResponse();
        #var_dump($response->getBody());

        $statusCode=$response->getStatusCode();
        switch ($statusCode){
            case 404 :
                // Bad Method or Ressource not available
                if(stristr((string)$response->getBody(), 'The object') && stristr((string)$response->getBody(), 'does not exist'))
                    throw new InvalidResourceException('Ressource '.$request->getMethod().' '.$request->getResource().' does not exist',404);

                // Snapshot not available - virtualServer::Snapshot' does not exist
                if(stristr((string)$response->getBody(), "virtualServer::Snapshot' does not exist")) {
                    throw new VpsSnapshotDoesNotExistsException('There is no snapshot available for VPS '.$this->getDomain($request->getPath()),404);
                }

                else throw $prev;



            case 400 :
                // Bad signature
                if($response->getReasonPhrase()=="Bad Request - Invalid signature")
                    throw new InvalidSignatureException('The request signature is not valid.',400);

                // Snopshot only cloud - Only Cloud model are able to order a snapshot
                if($response->getReasonPhrase()=="Only Cloud model are able to order a snapshot")
                    throw new VpsSnapshotIsOnlyForCloudException('Only Cloud model are able to order a snapshot.',400);



                else throw $prev;



            default :
                throw $prev;
        }

    }

    /**
     * Return domain from path
     *
     * @param string $path
     * @return string domain
     */
    private function getDomain($path){
        $d=explode("/",$path);
        return $d[3];
    }


    public function debug(){
        $r=new Response();
        var_dump($r->getReasonPhrase());
        $req= new Request();
        $req->getPath();
    }

}
