<?php


namespace Hnk\HnkFrameworkBundle\Notification;


abstract class AbstractNotificationManager
{
    /**
     * @var Notification[]
     */
    protected $notifications = [];

    /**
     * @var bool
     */
    protected $isLoaded = false;

    protected abstract function loadNotifications(): void;

    /**
     * Returns list of notifications
     *
     * @return array
     */
    public function getNotifications(): array
    {
        $this->load();

        return $this->notifications;
    }

    /**
     * Lazy loads notifications and returns true if there are any notification loaded
     *
     * @return bool
     */
    public function hasNotifications(): bool
    {
        $this->load();

        return !empty($this->notifications);
    }

    /**
     * Adds new notification
     *
     * @param string $message
     * @param string $type
     *
     * @return $this
     */
    public function addNotification(string $message, string $type = Notification::TYPE_INFO): self
    {
        $this->load();

        $this->notifications[] = new Notification($message, $type);

        return $this;
    }

    /**
     * Returns list of notifications sorted by type
     *
     * @return array
     */
    public function getSortedNotifications(): array
    {
        $this->load();

        return $this->sortNotifications($this->notifications);
    }

    protected function load(): void
    {
        if ($this->isLoaded) {
            return;
        }

        $this->loadNotifications();
        $this->isLoaded = true;
    }

    protected function sortNotifications(array $notifications): array
    {
        usort($notifications, function (Notification $a, Notification $b) {
            $typeOrder = array(
                Notification::TYPE_ERROR, Notification::TYPE_SUCCESS, Notification::TYPE_WARNING, Notification::TYPE_INFO
            );

            return array_search($a->getType(), $typeOrder) - array_search($b->getType(), $typeOrder);
        });

        return $notifications;
    }

}