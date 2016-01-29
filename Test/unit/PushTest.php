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
        $push->setIdentifier('abc');

        $this->assertInstanceOf("\\abraovic\\PHPush\\Push", $push, "Object does not implement Push interface");
        $this->assertObjectHasAttribute("service", $push);
        $this->assertAttributeInstanceOf("\\abraovic\\PHPush\\iOS\\APNS", "service", $push);

        $apns = $push->getService();
        $this->assertInstanceOf("\\abraovic\\PHPush\\iOS\\APNS",    $apns);
        $this->assertObjectHasAttribute("deviceToken",              $apns);
        $this->assertObjectHasAttribute("certificatePath",          $apns);
        $this->assertObjectHasAttribute("certificateParaphrase",    $apns);
        $this->assertObjectHasAttribute("settings",                 $apns);
        $this->assertObjectHasAttribute("development",              $apns);
        $this->assertObjectHasAttribute("timeToLive",               $apns);
        $this->assertObjectHasAttribute("identifier",               $apns);
        $this->assertObjectHasAttribute("priority",                 $apns);

        $message = new PHPush\iOS\Message();
        $this->assertInstanceOf("\\abraovic\\PHPush\\Message", $message, "Object does not implement Message interface");

        $message->setAlert("This will be shown");
        $message->setBadge(2);
        $this->assertObjectHasAttribute("alert",                $message);
        $this->assertObjectHasAttribute("badge",                $message);
        $this->assertObjectHasAttribute("sound",                $message);
        $this->assertObjectHasAttribute("contentAvailable",     $message);
        $this->assertObjectHasAttribute("additionalProperties", $message);
    }

    /**
     * @group gcm
     */
    public function testGCM()
    {
        $type = PHPush\Push\Push::ANDROID;
        $credentials = [
            "device_token" => "fake-token",
            "google_api_key" => "fake-google-api-key"
        ];

        $push = new PHPush\Push\Push($type, $credentials);
        $push->setNotificationTTL(123);
        $push->setIdentifier('abc');

        $this->assertInstanceOf("\\abraovic\\PHPush\\Push", $push, "Object does not implement Push interface");
        $this->assertObjectHasAttribute("service", $push);
        $this->assertAttributeInstanceOf("\\abraovic\\PHPush\\Android\\GCM", "service", $push);

        $gcm = $push->getService();
        $this->assertInstanceOf("\\abraovic\\PHPush\\Android\\GCM", $gcm);
        $this->assertObjectHasAttribute("deviceToken",              $gcm);
        $this->assertObjectHasAttribute("googleApiKey",             $gcm);
        $this->assertObjectHasAttribute("settings",                 $gcm);
        $this->assertObjectHasAttribute("timeToLive",               $gcm);
        $this->assertObjectHasAttribute("restrictedPackageName",    $gcm);

        $message = new PHPush\Android\Message();
        $this->assertInstanceOf("\\abraovic\\PHPush\\Message", $message, "Object does not implement Message interface");

        $message->setData("data");
        $message->setDelayWithIdle(1);
        $this->assertObjectHasAttribute("data",             $message);
        $this->assertObjectHasAttribute("delayWithIdle",    $message);
        $this->assertObjectHasAttribute("dryRun",           $message);
    }
}