<?php 

namespace Ovh\Telephony;

use Ovh\Common\Exception\BadConstructorCallException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;
use Ovh\Telephony\TelephonyClient;
use Ovh\Telephony\TelephonyAccountService;

class Telephony
{
	private $billingAccount = null;
	private static $telephonyClient = null;

    /**
     * @param $billingAccount
     *
     * @throws \Ovh\Common\Exception\BadConstructorCallException;
     */
    public function __construct($billingAccount)
    {
        if (!$billingAccount)
            throw new BadConstructorCallException('$billingAccount parameter is missing.');
        $this->setbillingAccount($billingAccount);
    }

    /**
     * Return Telephony client
     *
     * @return SmsClient
     */
    private static function getClient()
    {
        if (!self::$telephonyClient instanceof TelephonyClient){
            self::$telephonyClient = new TelephonyClient();
        };
        return self::$telephonyClient;
    }

    /**
     * Set billingAccount
     *
     * @param string $billingAccount
     */
    public function setBillingAccount($billingAccount)
    {
        $this->billingAccount = $billingAccount;
    }

    /**
     * Get billingAccount
     *
     * @return null | string billingAccount
     */
    public function getBillingAccount()
    {
        return $this->billingAccount;
    }

    /**
     * Get Telephony object properties
     *
     * @return object
     */
    public function getProperties()
    {
        return json_decode(self::getClient()->getProperties($this->billingAccount));
    }

    /**
     * @param array $props (avalaibles keys are callBack and templates)
     * @return boolean
     */
    public function setProperties($props)
    {
        return self::getClient()->setProperties($this->billingAccount, $props);
    }

    /**
     * Get services associated with this billing account.
     * @return array billing accoun services
     */
    public function getBillingAccountServices()
    {
    	$serviceList = json_decode(self::getClient()->getBillingAccountServices($this->billingAccount));

    	$services = array();

    	foreach ($serviceList as $service) 
    	{
    		$services[] = new TelephonyAccountService($service, $this);
    	}
    	return $services;
    }

    /**
     * Get infos about the service
     * @return string service info
     */
    public function getServiceInfos()
    {
        return json_decode(self::getClient()->getServiceInfos($this->billingAccount));
    }
}

 ?>