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


/**
 * TODO :
 *      - comments
 *      - Sanity Check on setter
 *
 *
 */

namespace Ovh\Common\Auth;

class Keyring{

    static private $appKey = null;         // OVH Application Key
    static private $appSecret = null;      // OVH Application Secret
    static private $consumerKey = null;    // OVH Consumer Key
    static private $appUrlRegion = null;   // Choix API (FR, CA, SYSCA, SYSFR, KIMSUFIFR, KIMSUFICA)


    public static function setAppKey($key)
    {
        self::$appKey=(string)$key;
    }

    public static function getAppKey()
    {
        return self::$appKey;
    }

    public static function setAppSecret($secret)
    {
        self::$appSecret=(string)$secret;
    }

    public static function getAppSecret()
    {
        return self::$appSecret;
    }

    public static function setConsumerKey($key)
    {
        self::$consumerKey=(string)$key;
    }

    public static function getConsumerKey()
    {
        return self::$consumerKey;
    }

    /** Paramètre "UrlRegion"  
    * FR = eu.api.ovh.com
    * CA = ca.api.ovh.com
	* KIMSUFIFR = eu.api.kimsufi.com
	* KIMSUFICA = ca.api.kimsufi.com
	* SYSFR = eu.api.soyoustart.com
	* SYSCA = ca.api.soyoustart.com
    */
    public static function setAppUrlRegion($Region){
		self::$appUrlRegion = self::getUrlApi($Region);
	}
	
	public static function getAppUrlRegion(){
		return self::$appUrlRegion;
	}

	public static function getUrlApi($Region){
		if($Region == 'FR'){
			return 'https://eu.api.ovh.com/1.0/';
		}
		elseif($Region == 'CA'){
			return 'https://ca.api.ovh.com/1.0/';
		}
		elseif($Region == 'KIMSUFIFR'){
			return 'https://eu.api.kimsufi.com/1.0/';
		}
		elseif($Region == 'KIMSUFICA'){
			return 'https://ca.api.kimsufi.com/1.0/';
		}
		elseif($Region == 'SYSFR'){
			return 'https://eu.api.soyoustart.com/1.0/';
		}
		elseif($Region == 'SYSCA'){
			return 'https://ca.api.soyoustart.com/1.0/';
		}
	}
}
