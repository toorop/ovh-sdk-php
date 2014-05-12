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

class Keyring
{

    static private $appKey = null;
    static private $appSecret = null;
    static private $consumerKey = null;
    //Le paramètre "appUrlRegion" permet de choisir avec quelle API on veut travailler (FR ou CA)
    static private $appUrlRegion = null;


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
    * FR = api.ovh.com
    * CA = ca.api.ovh.com
    */
    public static function setAppUrlRegion($Region){
		self::$appUrlRegion = self::getUrlApi($Region);
	}
	
	public static function getAppUrlRegion(){
		return self::$appUrlRegion;
	}

	public static function getUrlApi($Region){
		if($Region == 'FR'){
			return 'https://api.ovh.com/1.0/';
		}
		elseif($Region == 'CA'){
			return 'https://ca.api.ovh.com/1.0/';    
		}
	}
}
