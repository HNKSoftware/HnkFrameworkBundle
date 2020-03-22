<?php

namespace Hnk\HnkFrameworkBundle\Twig;

use Hnk\HnkFrameworkBundle\Notification\AbstractNotificationManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NotificationExtension extends AbstractExtension
{
    /**
     * @var AbstractNotificationManager
     */
    protected $notificationManager;

    /**
     * @param AbstractNotificationManager $notificationManager
     */
    public function __construct(AbstractNotificationManager $notificationManager)
    {
        $this->notificationManager = $notificationManager;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('hnk_notification_list', array($this, 'getNotification')),
            new TwigFunction('hnk_notification_sorted_list', array($this, 'getNotificationSorted')),
            new TwigFunction('hnk_notification_has', array($this, 'hasNotifications')),
        );
    }

    public function getNotification()
    {
        return $this->notificationManager->getNotifications();
    }

    public function getNotificationSorted()
    {
        return $this->notificationManager->getSortedNotifications();
    }

    public function hasNotifications()
    {
        return $this->notificationManager->hasNotifications();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'hnk_notification';
    }
}
