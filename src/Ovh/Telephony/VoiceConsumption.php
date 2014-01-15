<?php 

namespace Ovh\Telephony;

use Ovh\Telephony\TelephonyAccountService;

class VoiceConsumption
{
	private $consumptionId;
	private $telephoneAccountService;
	private static $voiceConsumptionClient = null;

	/**
	 * @param string $consumptionId
	 * @param TelephonyAccountService $telephoneAccountService
     * 
     * @throws \Ovh\Common\Exception\BadConstructorCallException;
	 * 
	 */
	function __construct($consumptionId, TelephonyAccountService $telephoneAccountService) 
	{
		if (!$consumptionId)
            throw new BadConstructorCallException('$consumptionId parameter is missing.');
		if (!$telephoneAccountService)
            throw new BadConstructorCallException('$telephoneAccountService parameter is missing.');
		$this->consumptionId = $consumptionId;
		$this->telephoneAccountService = $telephoneAccountService;
	}

	/**
	 * Get ConsumptionID.
	 * @return [type] [description]
	 */
	public function getConsumptionId()
	{
		return $this->consumptionId;
	}

	/**
     * Return TelephonyAccountService client
     *
     * @return SmsClient
     */
    private static function getClient()
    {
        if (!self::$voiceConsumptionClient instanceof VoiceConsumptionClient){
            self::$voiceConsumptionClient = new VoiceConsumptionClient();
        };
        return self::$voiceConsumptionClient;
    }

    /**
     * Get this VoiceConsumption properties
     * @return array propreties
     */
	public function getProperties()
	{
		return json_decode(self::getClient()->getProperties($this->consumptionId, $this->telephoneAccountService));
	}

}