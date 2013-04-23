<?php
/**
 * User: toorop
 * Date: 23/04/13
 * Time: 11:43
 */


include '../vendor/autoload.php';


use Ovh\Common\Ovh;
use Ovh\Cloud\Cloud;


class PcaTest extends PHPUnit_Framework_TestCase
{

    protected $keyring;
    protected $ovh;
    protected $pca;


    protected function setUp()
    {
        if (!file_exists('config.php'))
            throw new Exception('You must add a config.php file in tests folder. See tests/README.md for details');

        require_once 'config.php';

        // Test config
        // file exist
        $this->assertFileExists('config.php', "You must configure a config.php file");
        $this->assertTrue(defined('KR_CK'));

        $this->keyring = array('AK' => KR_AK, 'AS' => KR_AS, 'CK' => KR_CK);
        $this->ovh = new \Ovh\Common\Ovh($this->keyring);
        $this->pca = $this->ovh->getCloud(CLOUD_PASSPORT)->getPca(PCA_NAME);
    }

    public function testInit()
    {
        $this->assertInstanceOf('Ovh\Common\Ovh', $this->ovh);
        $this->assertInstanceOf('Ovh\Cloud\Pca', $this->pca);
    }

    public function testGetProperties()
    {
        $properties = $this->pca->getProperties();
        $this->assertTrue(is_object($properties), "Properties must be an object");
        $this->assertCount(5, (array)$properties, "Properties object must have 5 attributes");
        foreach (array('domain', 'password', 'sshkey', 'login', 'host') as $attr) {
            $this->assertObjectHasAttribute($attr, $properties, "Properties must have attribute '$attr'");
        }
    }

    public function testgetSshKey()
    {
        $key = $this->pca->getSshKey();
        $this->assertTrue(is_string($key), "Returned value must be a string");
        return $key;
    }

    /**
     * @depends testgetSshKey
     */
    public function testSetSshKey($key)
    {
        $this->pca->setSshKey($key); // exception on error
    }

    public function testSetPassword()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->pca->setPassword(null);
    }

    public function testGetLogin()
    {
        $this->assertTrue(is_string($this->pca->getLogin()));
    }

    public function testGetHost()
    {
        $this->assertTrue(is_string($this->pca->getHost()));
    }

    public function testGetInfo()
    {
        $i = $this->pca->getInfo();
        $this->assertTrue(is_object($i), "Returned value must be an object");
        $this->assertCount(8, (array)$i, "Returned object must have 8 attributes");
        foreach (array('status', 'engagedUpTo', 'contactBilling', 'domain', 'contactTech', 'expiration', 'contactAdmin', 'creation') as $attr) {
            $this->assertObjectHasAttribute($attr, $i, "PCA info must have attribute '$attr'");
        }
    }

    public function testGetSessions()
    {
        $s = $this->pca->getSessions();
        $this->assertTrue(is_array($s), "Returned value PCA sessions must be a array");
        if (count($s) > 0) return $s[0];
        else return false;
    }


    /**
     * @depends testGetSessions
     */
    public function testGetSessionProperties($session)
    {
        // test only if we have session
        if (!$session === false) {
            $p = $this->pca->getSessionProperties($session);
            $this->assertTrue(is_object($p), "Returned value must be an object");
            $this->assertCount(8, (array)$p, "Returned object must have 8 attributes");
            foreach (array('name', 'login', 'size', 'srcIp', 'state', 'endDate', 'id', 'startDate') as $attr) {
                $this->assertObjectHasAttribute($attr, $p, "PCA info must have attribute '$attr'");
            }
        }
    }

    /**
     * @depends testGetSessions
     */
    public function testGetSessionFiles($session)
    {
        // test only if we have session
        if (!$session === false) {
            $p = $this->pca->getSessionFiles($session);
            $this->assertTrue(is_array($p), "Returned value must be an array");
            if (count($p) > 0) return $p[0];
            return false;
        }
    }

    /**
     * @depends testGetSessions
     * @depends testGetSessionFiles
     */
    public function testGetSessionFilesProperties($session, $fileId)
    {
        // Make test only if we have session and fileId
        if ($session !== false && $fileId !== false) {
            $p = $this->pca->getSessionFilesProperties($session, $fileId);
            $this->assertTrue(is_object($p), "Returned value must be an object");
            $this->assertCount(8, (array)$p, "Returned object must have 8 attributes");
            foreach (array('SHA1', 'SHA256', 'name', 'type', 'id', 'MD5', 'state', 'size') as $attr) {
                $this->assertObjectHasAttribute($attr, $p, "PCA info must have attribute '$attr'");
            }
        }
    }

    public function testGetTasks()
    {
        $t = $this->pca->getTasks();
        $this->assertTrue(is_array($t), "Returned value must be an array");
        if (count($t) > 0) return $t[0];
        else return false;
    }

    /**
     * @depends testGetTasks
     */
    public function testGetTaskProperties($taskId)
    {
        if($taskId!==false){
            $p=$this->pca->getTaskProperties($taskId);
            $this->assertTrue(is_object($p), "Returned value must be an object");
            $this->assertCount(7, (array)$p, "Returned object must have 8 attributes");
            foreach (array('function', 'comment', 'status', 'todoDate', 'ipAddress', 'id', 'login') as $attr) {
                $this->assertObjectHasAttribute($attr, $p, "PCA info must have attribute '$attr'");
            }
        }
    }

    /**
     * We will test restore task
     *
     * @depends testGetSessions
     * @depends testGetSessionFiles
     */
    public function testAddTask($session,$fileId)
    {
        if($session && $fileId){
            $task=array('sessionId'=>$session,'taskFunction'=>'restore','fileIds'=>array($fileId));
            $t=$this->pca->addTask($task);
            $this->assertTrue(is_object($t), "Returned value must be an object");
            $this->assertCount(7, (array)$t, "Returned object must have 8 attributes");
            foreach (array('function', 'comment', 'status', 'todoDate', 'ipAddress', 'id', 'login') as $attr) {
                $this->assertObjectHasAttribute($attr, $t, "PCA task must have attribute '$attr'");
            }
            $this->assertTrue($t->status=='todo');
        }
    }

    public function testGetUsage(){
        $u=$this->pca->getUsage();
        $this->assertTrue(is_int($u));
    }


}
