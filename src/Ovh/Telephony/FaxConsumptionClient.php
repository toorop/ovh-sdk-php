<?php 

namespace Ovh\Telephony;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;
use Ovh\Telephony\Exception\VoiceConsumptionException;

class FaxConsumptionClient extends AbstractClient
{
	/**
     * Get Telephony account service properties
     *
     * @param string $service
     * @param Telephony $telephoneAccount
     * @return string Json
     * @throws Exception\VoiceConsumptionException
     * @throws BadMethodCallException
     */
    public function getProperties($consumptionId, $telephoneAccount)
    {
        if (!$consumptionId)
            throw new BadMethodCallException('Parameter $consumptionId is missing.');
        if (!$telephoneAccount)
            throw new BadMethodCallException('Parameter $telephoneAccount is missing.');
        try {
            $r = $this->get('telephony/' . $telephoneAccount->getBillingAccount()->getBillingAccount() . '/service/' . $telephoneAccount->getService() . '/faxConsumption/' . $consumptionId)->send();
        } catch (\Exception $e) {
            throw new VoiceConsumptionException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
}