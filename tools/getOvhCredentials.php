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
 * STEP 0
 *
 *  First you need to have OVH PHP SDK installed (see : readme at : https://github.com/Toorop/ovh-sdk-php)
 */


/**
 * STEP 1
 *
 *  set the path to your autoload.php file:
 *  eg $autoloadPath="../vendor/autoload.php";
 *
 */
$autoloadPath = "../vendor/autoload.php";


/**
 * STEP 2
 *
 *  - Go to http://www.ovh.com/cgi-bin/api/createApplication.cgi
 *  - You will get your application key (AK) and your application secret (AS)
 *  - Init $myAppKey var below with your application key.
 *
 */

$myAppKey = "";

/**
 * STEP 3
 *
 *  Run this script
 *  You will get :
 *      - your consumer KEY
 *      - a validation link.
 *
 *  Copy and past the validation link in your broswer and follow OVH instructions to validate your key
 *
 *  That's all you get all you need :
 *      Your Application Key (AK)
 *      Your Application Secret (AS)
 *      Your Consumer Key (CK)
 */


/**
 * DO NOT EDIT AFTER
 */

if (empty($myAppKey))
    trigger_error('You must edit this script and set $myAppKey parameter (STEP 2)', E_USER_ERROR);

include "$autoloadPath";

use Guzzle\Http\Client;

$client = new Client('https://api.ovh.com');
$headers = array('X-Ovh-Application' => $myAppKey, 'Content-type' => 'application/json');
$request = $client->post('/1.0/auth/credential', $headers, '{"accessRules":[{"method":"GET","path":"/*"},{"method":"POST","path":"/*"},{"method":"PUT","path":"/*"},{"method":"DELETE","path":"/*"}]}');

$response = $request->send();
$data = $response->json();

$vUrl = $data['validationUrl'];
$ck = $data['consumerKey'];

print "Your Consumer Key (CK) is : $ck\n";
print "Before using yours credentials you have to validate them.\nCopy and past this URL : $vUrl in your browser to proceed.\n";


