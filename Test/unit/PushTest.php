<?php
namespace abraovic\PHPush\Test;

use abraovic\PHPush;

class PushTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group apns
     */
    public function testAPNS()
    {
        $type = PHPush\Push\Push::IOS;
        $credentials = [
            "device_token" => "fake-token",
            'certificate_path' => 'fake-cert-path',
            'certificate_phrase' => 'fake-cert-phrase',
            'dev' => true
        ];

        $push = new PHPush\Push\Push($type, $credentials);
        $push->setNotificationTTL(123);

        $this->assertInstanceOf("\\abraovic\\PHPush\\Push", $push, "Object does not implement Push interface");
        $this->assertObjectHasAttribute("service", $push);
        $this->assertAttributeInstanceOf("\\abraovic\\PHPush\\iOS\\APNS", "service", $push);

        $apns = $push->getService();
        $apns->setIdentifier("abc");
        $this->assertInstanceOf("\\abraovic\\PHPush\\iOS\\APNS",    $apns);
        $this->assertObjectHasAttribute("deviceToken",              $apns);
        $this->assertObjectHasAttribute("certificatePath",          $apns);
        $this->assertObjectHasAttribute("certificatePassphrase",    $apns);
        $this->assertObjectHasAttribute("settings",                 $apns);
        $this->assertObjectHasAttribute("development",              $apns);
        $this->assertObjectHasAttribute("timeToLive",               $apns);
        $this->assertObjectHasAttribute("identifier",               $apns);
        $this->assertObjectHasAttribute("priority",                 $apns);

        $message = new PHPush\Push\Message($type, "Here is a message");
        $message->setBadge(2);
        $message->setBody("Some body");
        $message->setAdditional(["add" => "data"]);
        $iosMsg = $message->getMessage();
        $iosMsg->setCategory("abc");

        $this->assertAttributeInstanceOf("\\abraovic\\PHPush\\iOS\\Aps", "aps", $iosMsg);

    }

    /**
     * @group fcm
     */
    public function testFCM()
    {
        $type = PHPush\Push\Push::ANDROID;
        $credentials = [
            "device_token" => "fake-token",
            "google_api_key" => "fake-google-api-key"
        ];

        $push = new PHPush\Push\Push($type, $credentials);
        $push->setNotificationTTL(123);

        $this->assertInstanceOf("\\abraovic\\PHPush\\Push", $push, "Object does not implement Push interface");
        $this->assertObjectHasAttribute("service", $push);
        $this->assertAttributeInstanceOf("\\abraovic\\PHPush\\Android\\FCM", "service", $push);

        $fcm = $push->getService();
        $fcm->setRestrictedPackageName('abc');
        $this->assertInstanceOf("\\abraovic\\PHPush\\Android\\FCM", $fcm);
        $this->assertObjectHasAttribute("deviceToken",              $fcm);
        $this->assertObjectHasAttribute("googleApiKey",             $fcm);
        $this->assertObjectHasAttribute("settings",                 $fcm);
        $this->assertObjectHasAttribute("timeToLive",               $fcm);
        $this->assertObjectHasAttribute("restrictedPackageName",    $fcm);

        $message = new PHPush\Push\Message($type, "Here is a message");
        $message->setBadge(20);
        $message->setBody("Some body");
        $message->setAdditional(["add" => "data"]);
        $andMsg = $message->getMessage();
        $andMsg->setDryRun(false);

        $this->assertAttributeInstanceOf("\\abraovic\\PHPush\\Android\\Notification", "notification", $andMsg);
    }
}