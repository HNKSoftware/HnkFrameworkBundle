<?php


namespace Hnk\HnkFrameworkBundle\Notification;


use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Bridge for Symfony session module
 *
 * Class SessionAwareNotificationManager
 * @package Hnk\HnkFrameworkBundle\Notification
 */
class SessionAwareNotificationManager extends AbstractNotificationManager
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    protected function loadNotifications(): void
    {
        foreach ($this->session->getFlashBag()->all() as $type => $flashes) {
            foreach ($flashes as $flash) {
                $this->notifications[] = new Notification($flash, $type);
            }
        }
    }
}