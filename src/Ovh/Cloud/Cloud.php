<?php

namespace Ovh\Cloud;


use Ovh\Common\Exception\BadConstructorCallException;
use Ovh\Cloud\CloudClient;
use Ovh\Cloud\Pca;

/**
 * Cloud class
 */
class Cloud 
{
	private $passport=null;

	private static $CloudClient=null;

    /**
     * Constructor
     *
     * @param $passport
     * @throws \Ovh\Common\Exception\BadConstructorCallException;
     */
    public function __construct($passport) 
    {
		if (!$passport ) {
			throw new BadConstructorCallException( '$passport parameter is missing', 1 );
		}
        $this->passport=$passport;
	}


    /**
     * Return Cloud client
     *
     * @return object cloudClient
     */
    private static function getClient()
    {
        if (!self::$CloudClient instanceof CloudClient) {
            self::$CloudClient = new CloudClient();
        }
        return self::$CloudClient;
    }


    /**
     * Get PCA services associated with this cloud passport
     *
     * @return array of services
     */
    public function getPcaServices()
    {
        return json_decode(self::getClient()->getPcaServices($this->passport));
    }

    /**
     * Return PCA instance
     *
     * @param string $pcaName
     * @return object Pca
     */
    public function getPca($pcaName)
    {
        return new Pca($this->passport,$pcaName);
    }
}
