<?php

namespace Hnk\HnkFrameworkBundle\Tests\Notification;

use Hnk\HnkFrameworkBundle\Notification\AbstractNotificationManager;
use Hnk\HnkFrameworkBundle\Notification\Notification;
use PHPUnit\Framework\TestCase;

class DummyNotificationManager extends AbstractNotificationManager
{
    private $notificationsForLoad;

    public function __construct($notificationsForLoad)
    {
        $this->notificationsForLoad = $notificationsForLoad;
    }


    protected function loadNotifications(): void
    {
        foreach ($this->notificationsForLoad as $notificationData) {
            $this->notifications[] = new Notification($notificationData["message"], $notificationData["type"]);
        }
    }
}

class AbstractNotificationManagerTest extends TestCase
{
    public function testEmptyManager()
    {
        $manager = new DummyNotificationManager([]);

        $this->assertFalse($manager->hasNotifications(), "has notification check");
        $this->assertEmpty($manager->getNotifications(), "get check");
        $this->assertEmpty($manager->getSortedNotifications(), "get sorted check");
    }

    public function testLoad()
    {
        $data = [
            ["message" => "notification 1", "type" => Notification::TYPE_ERROR],
            ["message" => "notification 2", "type" => Notification::TYPE_INFO],
        ];
        $manager = new DummyNotificationManager($data);

        $this->assertTrue($manager->hasNotifications(), "has notification check");

        $notifications = $manager->getNotifications();

        /** @var Notification $notification */
        foreach ($notifications as $index => $notification) {
            $this->assertTrue($notification instanceof Notification, "expected instance of Notification");
            $this->assertEquals($data[$index]["message"], $notification->getMessage());
            $this->assertEquals($data[$index]["type"], $notification->getType());
        }
    }

    public function testSort()
    {
        $data = [
            ["message" => "notification warning", "type" => Notification::TYPE_WARNING],
            ["message" => "notification error", "type" => Notification::TYPE_ERROR],
            ["message" => "notification info", "type" => Notification::TYPE_INFO],
            ["message" => "notification success", "type" => Notification::TYPE_SUCCESS],
        ];
        $expectedOrder = [
            $data[1],
            $data[3],
            $data[0],
            $data[2],
        ];
        $manager = new DummyNotificationManager($data);

        $this->assertTrue($manager->hasNotifications(), "has notification check");

        $notifications = $manager->getSortedNotifications();

        /** @var Notification $notification */
        foreach ($notifications as $index => $notification) {
            $this->assertTrue($notification instanceof Notification, "expected instance of Notification");
            $this->assertEquals($expectedOrder[$index]["message"], $notification->getMessage());
            $this->assertEquals($expectedOrder[$index]["type"], $notification->getType());
        }
    }

    public function testAdd()
    {
        $data = [
            ["message" => "notification 1", "type" => Notification::TYPE_ERROR],
            ["message" => "notification 2", "type" => Notification::TYPE_INFO],
        ];
        $manager = new DummyNotificationManager($data);

        $manager->addNotification("notification info");
        $manager->addNotification("notification success", Notification::TYPE_SUCCESS);

        $expectedNotifications = [
            ["message" => "notification 1", "type" => Notification::TYPE_ERROR],
            ["message" => "notification 2", "type" => Notification::TYPE_INFO],
            ["message" => "notification info", "type" => Notification::TYPE_INFO],
            ["message" => "notification success", "type" => Notification::TYPE_SUCCESS],
        ];

        $notifications = $manager->getNotifications();

        /** @var Notification $notification */
        foreach ($notifications as $index => $notification) {
            $this->assertTrue($notification instanceof Notification, "expected instance of Notification");
            $this->assertEquals($expectedNotifications[$index]["message"], $notification->getMessage());
            $this->assertEquals($expectedNotifications[$index]["type"], $notification->getType());
        }
    }
}
