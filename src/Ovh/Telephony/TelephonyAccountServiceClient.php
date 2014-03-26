<?php 

namespace Ovh\Telephony;

use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\Common\Exception\NotImplementedYetByOvhException;
use Ovh\Telephony\Exception\TelephonyAccountServiceException;

class TelephonyAccountServiceClient extends AbstractClient
{
	/**
     * Get Telephony account service properties
     *
     * @param string $service
     * @param Telephony $billingAccount
     * @return string Json
     * @throws Exception\TelephonyAccountServiceException
     * @throws BadMethodCallException
     */
    public function getProperties($service, $billingAccount)
    {
        if (!$service)
            throw new BadMethodCallException('Parameter $service is missing.');
        if (!$billingAccount)
            throw new BadMethodCallException('Parameter $billingAccount is missing.');
        try {
            $r = $this->get('telephony/' . $billingAccount->getBillingAccount() . '/service/' . $service)->send();
        } catch (\Exception $e) {
            throw new TelephonyAccountServiceException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

    /**
     * Set Properties of Telephony account
     *
     * @param string $service
     * @param Telephony $billingAccount
     * @param array $properties (available keys are callBack & templates)
     * @return \Guzzle\Http\Message\Response
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @throws Exception\SmsException
     */
    public function setProperties($service, $billingAccount, $properties)
    {
        if (!$billingAccount)
            throw new BadMethodCallException('Parameter $billingAccount is missing.');
        if (!$properties)
            throw new BadMethodCallException('Parameter $properties is missing.');
        if (!is_array($properties))
            throw new BadMethodCallException('Parameter $properties must be a array.');
        $t = array();
        if (array_key_exists('description', $properties))
            $t['description'] = $properties['description'];
        $properties = $t;
        unset($t);
        if (count($properties) == 0)
            throw new BadMethodCallException('Parameter $properties does not contain valid key. valid key is "description"');
        try {
            $r = $this->put('telephony/' . $billingAccount->getBillingAccount() . '/service/' . $service, array('Content-Type' => 'application/json;charset=UTF-8'), json_encode($properties))->send();
        } catch (\Exception $e) {
            throw new TelephonyAccountServiceException($e->getMessage(), $e->getCode(), $e);
        }
        return true;
    }

	/**
     * Get Telephony account service voice consumptions.
     *
     * @param string $service
     * @param Telephony $billingAccount
     * @return string Json
     * @throws Exception\TelephonyAccountServiceException
     * @throws BadMethodCallException
     */
    public function getVoiceConsumptions($service, $billingAccount, $params = null)
    {
    	$paramsString = "";
    	if (!$service)
            throw new BadMethodCallException('Parameter $service is missing.');
        if (!$billingAccount)
            throw new BadMethodCallException('Parameter $billingAccount is missing.');
        if ($params != null && is_array($params) && count($params) > 0)
        {
        	$paramsString = "?";
        	if (array_key_exists('creationDatetime.from', $params))
        	{
        		$string = $params['creationDatetime.from'];
        		if ($params['creationDatetime.from'] instanceof \Datetime)
        			$string = $params['creationDatetime.from']->format("Y-m-d\TH:i:sP");
            	$paramsString .= "creationDatetime.from=".urlencode($string);
        	}
        	if (array_key_exists('creationDatetime.to', $params))
        	{
                $paramsString .= "&"; 
        		$string = $params['creationDatetime.to'];
        		if ($params['creationDatetime.to'] instanceof \Datetime)
        			$string = $params['creationDatetime.to']->format("Y-m-d\TH:i:sP");
            	$paramsString .= "creationDatetime.to=".urlencode($string);
        	}
        	if (array_key_exists('destinationType', $params) && in_array($params['destinationType'], array('landline', 'mobile', 'special')))
            {
                $paramsString .= "&";
            	$paramsString .= "destinationType=".$params['destinationType'];
            }
        	if (array_key_exists('planType', $params) && in_array($params['planType'], array('outplan', 'priceplan')))
            {
                $paramsString .= "&";
            	$paramsString .= "planType=".$params['planType'];
            }
        	if (array_key_exists('wayType', $params) && in_array($params['wayType'], array('incoming', 'outgoing', 'transfer')))
            {
                $paramsString .= "&";
            	$paramsString .= "wayType=".$params['wayType'];
            }
        }
        try {
            $r = $this->get('telephony/' . $billingAccount->getBillingAccount() . '/service/' . $service . '/voiceConsumption' . $paramsString)->send();
        } catch (\Exception $e) {
            throw new TelephonyAccountServiceException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }

	/**
     * Get Telephony account service fax consumptions.
     *
     * @param string $service
     * @param Telephony $billingAccount
     * @return string Json
     * @throws Exception\TelephonyAccountServiceException
     * @throws BadMethodCallException
     */
    public function getFaxConsumptions($service, $billingAccount, $params = null)
    {
    	$paramsString = "";
    	if (!$service)
            throw new BadMethodCallException('Parameter $service is missing.');
        if (!$billingAccount)
            throw new BadMethodCallException('Parameter $billingAccount is missing.');
        if ($params != null && is_array($params) && count($params) > 0)
        {
        	$paramsString = "?";
            if (array_key_exists('creationDatetime.from', $params))
            {
                $string = $params['creationDatetime.from'];
                if ($params['creationDatetime.from'] instanceof \Datetime)
                    $string = $params['creationDatetime.from']->format("Y-m-d\TH:i:sP");
                $paramsString .= "creationDatetime.from=".urlencode($string);
            }
            if (array_key_exists('creationDatetime.to', $params))
            {
                $paramsString .= "&"; 
                $string = $params['creationDatetime.to'];
                if ($params['creationDatetime.to'] instanceof \Datetime)
                    $string = $params['creationDatetime.to']->format("Y-m-d\TH:i:sP");
                $paramsString .= "creationDatetime.to=".urlencode($string);
            }
            if (array_key_exists('destinationType', $params) && in_array($params['destinationType'], array('landline', 'mobile', 'special')))
            {
                $paramsString .= "&";
                $paramsString .= "destinationType=".$params['destinationType'];
            }
            if (array_key_exists('planType', $params) && in_array($params['planType'], array('outplan', 'priceplan')))
            {
                $paramsString .= "&";
                $paramsString .= "planType=".$params['planType'];
            }
            if (array_key_exists('wayType', $params) && in_array($params['wayType'], array('incoming', 'outgoing', 'transfer')))
            {
                $paramsString .= "&";
                $paramsString .= "wayType=".$params['wayType'];
            }
        }
        try {
            $r = $this->get('telephony/' . $billingAccount->getBillingAccount() . '/service/' . $service . '/faxConsumption' . $paramsString)->send();
        } catch (\Exception $e) {
            throw new TelephonyAccountServiceException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
}