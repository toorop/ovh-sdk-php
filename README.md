OVH SDK for PHP
===========

PHP SDK to consume OVH web services

**WARNING : This SDK is currently in development, some functions/methods are not (or not correctly) implemented. Use with care.**


## Before Using the SDK
### Get OVH credentials
You need three keys  :
- Application Key (AK)
- Applcation Secret (AS)
- Consumer Key (CK)

You will find in the tools directory a script named getOvhCredentials.php.
Use it to get yours credentials (read how to in the header of the script).

### Requirements
You need PHP 5.3.2+ compiled with the cURL extension.

## Install OVH SDK
### Installing via Composer

The recommended way to install OVH SDK is through [Composer](http://getcomposer.org).

1. Add ``toorop/ovh-sdk-php`` as a dependency in your project's ``composer.json`` file:

        {
            "require": {
                "toorop/ovh-sdk-php": "dev-master"
            }
        }

2. Download and install Composer:

        curl -s http://getcomposer.org/installer | php

3. Install your dependencies:

        php composer.phar install

4. Require Composer's autoloader

    Composer also prepares an autoload file that's capable of autoloading all of the classes in any of the libraries that it downloads. To use it, just add the following line to your code's bootstrap process:

        require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).

## Using the SDK
### Quick Start
```php
<?php
// Include the OVH SDK via composer autoload
require_once 'vendor/autoload.php';
use \Ovh\Common\Ovh;

// Populate your keyring
$config=array(
    'AK' => 'YOUR APPLICATION KEY',
    'AS' => 'YOUR APPLICATION SECRET',
    'CK' => 'YOUR CONSUMER KEY'
);

// Init your OVH instance
$ovh = new Ovh($config);

/**
 *
 *  Dedicated Server Part
 *
 */

// Get your Dedicated Servers
$dedicatedServerList=$ovh->getDedicatedServerList();
foreach($dedicatedServerList as $dedicatedServerDomain)
    print "$dedicatedServerDomain\n";

// Init a Dedicated Server instance
$dedicatedServer=$ovh->getDedicatedServer($dedicatedServerDomain);

// Get Dedicated Server properties
$r=$dedicatedServer->getProperties();

// Get Dedicated Server Service Infos
$r=$dedicatedServer->getServiceInfos();

// Get current task
$task=$dedicatedServer->getTasks();

// Get properties of a task
$properties = $dedicatedServer->getTaskProperties($taskId);



/**
 *
 *  VPS Part
 *
 */

// Get yours VPS
$vpsList=$ovh->getVpsList();
foreach($vpsList as $vpsDomain)
    print "$vpsDomain\n";

// Init a VPS instance
$vps=$ovh->getVps($vpsDomain);

// Get VPS properties
$r=$vps->getProperties();

// Get VPS Status
$r=$vps->getStatus();

// Get Monitoring
$r=$vps->getMonitoring('today',"cpu:max");

// Get current Monitorin
$r=$vps->getCurrentMonitoring('cpu:max');

// Stop VPS
$vps->stop();

// Start VPS
$vps->start();

// reboot VPS
$vps->reboot();

// (re)Set root password
$vps->setRootPassword();

// Get availables upgrade
$upgrades=$vps->getAvailableUpgrades();

// Get availables options
$options=$vps->getAvailableOptions();

// Get model
$models=$vps->getModels();

// Order a cPanel Licence
$r=$vps->orderCpanelLicense();

// Order a Plesk Licence
$r=$vps->orderPleskLicence(1);

// Order a FTP backup
$r=$vps->orderFtpBackup();

// Get disks
$disks=$vps->getDisks();

// Get disk properties
$properties=$vps->getDiskProperties($diskId);

// Get disk usage
$usage=$vps->getDiskUsage($diskId,'used');

// Get disk monitoring
$monit=$vps->getDiskMonitoring($diskId,'lastday','max');

// Get current task
$task=$vps->getTasks();

// Get properties of a task
$properties = $vps->getTaskProperties($taskId);

// Get VPS IPs
$ip=$vps->getIps();

// Get IP properties
$properties=$vps->getIpProperties($ip);

// Set IP properties
$vps->setIpProperties('$ip', array('reverse'=>'IP Reverse'));

// Get snapshot properties
$properties=$vps->getSnapshotProperties();

// Order a snapshot
$order=$vps->orderSnapshot();

// Get available templates
$templates=$vps->getAvailableTemplates();

// Get templates properties
$properties=$vps->getTemplateProperties($templateId);

```

