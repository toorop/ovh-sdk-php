<?php
/**
 * User: toorop
 * Date: 22/04/13
 * Time: 15:37
 */

namespace Ovh\Cloud;

use Ovh\Cloud\CloudClient;
use Ovh\Common\Exception\BadConstructorCallException;


class Pca
{
    private $pp = null; // Cloud passport
    private $sn = null; // PCA service name
    private static $CloudClient = null;


    public function __construct($pp, $pcaName)
    {
        if (!$pp) {
            throw new BadConstructorCallException('$pp (OVH cloud passport) parameter is missing', 1);
        }
        $this->pp = $pp;
        if (!$pcaName) {
            throw new BadConstructorCallException(' $pcaName (PCA service name) parameter is missing', 1);
        }
        $this->sn = $pcaName;
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
     * Get PCA properties
     *
     * @return string (json encoded object)
     */
    public function getProperties()
    {
        return json_decode(self::getClient()->getPcaProperties($this->pp, $this->sn));
    }

    /**
     * Return SSH key assoiciated with this PCA
     *
     * @return string key
     */
    public function getSshKey()
    {
        return $this->getProperties()->sshkey;
    }

    /**
     * Set SSH key
     *
     * @param string $key
     */
    public function setSshKey($key)
    {
        self::getClient()->setSshKey($this->pp, $this->sn, $key);
    }

    /**
     * Set Password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        self::getClient()->setPassword($this->pp, $this->sn, $password);
    }


    /**
     * Return PCA login
     *
     * @return string Login
     */
    public function getLogin()
    {
        return $this->getProperties()->login;
    }


    /**
     * Return PCA host
     *
     * @return string host
     */
    public function getHost()
    {
        return $this->getProperties()->host;
    }

    /**
     * Get info about this PCA
     *
     * @return object info
     */
    public function getInfo()
    {
        return json_decode(self::getClient()->getPcaInfo($this->pp, $this->sn));
    }

    /**
     * Get PCA sessions
     *
     * @return array of string session
     */
    public function getSessions()
    {
        return json_decode(self::getClient()->getPcaSessions($this->pp, $this->sn));
    }

    /**
     * Get session properties
     *
     * @param string $sessId
     * @return object props
     */
    public function getSessionProperties($sessId)
    {
        return json_decode(self::getClient()->getPcaSessionProperties($this->pp, $this->sn, $sessId));
    }

    /**
     * Get files associated with sessionId
     *
     * @param string $sessId
     * @return array of files id
     */
    public function getSessionFiles($sessId)
    {
        return json_decode(self::getClient()->getPcaSessionFiles($this->pp, $this->sn, $sessId));
    }

    /**
     * Get files properties
     *
     * @param string $sessId
     * @param string $fileId
     * @return object file properties
     */
    public function getSessionFilesProperties($sessId, $fileId)
    {
        return json_decode(self::getClient()->getPcaSessionFilesProperties($this->pp, $this->sn, $sessId, $fileId));
    }

    /**
     * Return tasks associated with this PAC
     *
     * @return array of task id
     */
    public function getTasks()
    {
        return json_decode(self::getClient()->getPcaTasks($this->pp, $this->sn));
    }

    /**
     * Return properties of task $taskId
     *
     * @param string $taskId
     * @return object task
     */
    public function getTaskProperties($taskId)
    {
        return json_decode(self::getClient()->getPcaTaskProperties($this->pp, $this->sn, $taskId));

    }

    /**
     * Add a new task (restore, delete)
     *
     * @param  array $task. Keys : string sessionId, string taskFunction (restore|delete), array of string fileIds.
     * @return object task
     * @throws \Ovh\Common\Exception\BadMethodCallException
     * @deprecated Use addDeleteTask or addRestoreTask
     */
    public function addTask(array $task)
    {
        if (!array_key_exists('sessionId', $task))
            throw new BadMethodCallException("Parameter $task must have the key 'sessionId'");
        if (!array_key_exists('taskFunction', $task))
            throw new BadMethodCallException("Parameter $task must have the key 'taskFunction'");

        if ($task['taskFunction'] == 'restore') {
            return json_decode(self::getClient()->createPcaRestoreTask($this->pp, $this->sn,$task['sessionId']));
        } elseif ($task['taskFunction'] == 'delete') {
            return json_decode(self::getClient()->createPcaDeleteTask($this->pp, $this->sn,$task['sessionId']));
        }
        throw new BadMethodCallException("Task function available are 'delete' or 'restore'");   
    }

    /**
     * Add a new delete task
     * 
     * @param string $sessionId The session id to delete.
     * @param object task
     */
    public function addDeleteTask($sessionId)
    {
        return json_decode(self::getClient()->createPcaDeleteTask($this->pp, $this->sn, $sessionId));
    }

    /**
     * Add a new restore task
     * 
     * @param string $sessionId The session id to restore.
     * @param object task
     */
    public function addRestoreTask($sessionId)
    {
        return json_decode(self::getClient()->createPcaRestoreTask($this->pp, $this->sn, $sessionId));
    }

    /**
     * Get PCA usage
     *
     * @return object task
     */
    public function getUsage(){
        return json_decode(self::getClient()->getPcaUsage($this->pp, $this->sn));
    }

}