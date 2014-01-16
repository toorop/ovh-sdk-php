<?php 

namespace Ovh\Telephony;

use Ovh\Telephony\Telephony;
use Ovh\Telephony\TelephonyAccountServiceClient;
use Ovh\Telephony\VoiceConsumption;
use Ovh\Telephony\FaxConsumption;

class TelephonyAccountService
{
	private $service = null;
	private $billingAccount = null;
	private static $telephonyAccountServiceClient = null;

	/**
	 * 
	 * @param String $service
	 * @param Telephony $billingAccount
     * 
     * @throws \Ovh\Common\Exception\BadConstructorCallException;
     * 
	 */
	function __construct($service, Telephony $billingAccount) 
	{
		if (!$service)
            throw new BadConstructorCallException('$service parameter is missing.');
		if (!$billingAccount)
            throw new BadConstructorCallException('$billingAccount parameter is missing.');
		$this->service = $service;
		$this->billingAccount = $billingAccount;
	}

	/**
	 * Get Telephony Account Service
	 * @return string $service
	 */
	public function getService()
	{
		return $this->service;
	}

	/**
	 * Get Telephony Account Service
	 * @return string $service
	 */
	public function getBillingAccount()
	{
		return $this->billingAccount;
	}

	/**
     * Return TelephonyAccountService client
     *
     * @return SmsClient
     */
    private static function getClient()
    {
        if (!self::$telephonyAccountServiceClient instanceof TelephonyAccountServiceClient){
            self::$telephonyAccountServiceClient = new TelephonyAccountServiceClient();
        };
        return self::$telephonyAccountServiceClient;
    }

	/**
     * Get Service object properties
     *
     * @return object
     */
    public function getProperties()
    {
        return json_decode(self::getClient()->getProperties($this->service, $this->billingAccount));
    }

    /**
     * @param array $props (avalaibles keys are callBack and templates)
     * @return boolean
     */
    public function setProperties($props)
    {
        return self::getClient()->setProperties($this->service, $this->billingAccount, $props);
    }

    /**
     * Get Fax delivery records.
     * @return [type] [description]
     */
    public function getFaxConsumptions($params = null)
    {
    	$consumptionList = json_decode(self::getClient()->getFaxConsumptions($this->service, $this->billingAccount, $params));
    	$consumptions = array();

    	foreach ($consumptionList as $consumption) 
    	{
    		$consumptions[] = new FaxConsumption($consumption, $this);
    	}
    	return $consumptions;
    }

    /**
     * Get Call delivery records.
     * @return [type] [description]
     */
    public function getVoiceConsumptions($params = null)
    {
    	$consumptionList = json_decode(self::getClient()->getVoiceConsumptions($this->service, $this->billingAccount, $params));
    	$consumptions = array();

    	foreach ($consumptionList as $consumption) 
    	{
    		$consumptions[] = new VoiceConsumption($consumption, $this);
    	}
    	return $consumptions;
    }
}