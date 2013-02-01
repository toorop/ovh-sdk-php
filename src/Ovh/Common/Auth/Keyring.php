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

}
