<?php

namespace Hnk\HnkFrameworkBundle\Controller;

use Hnk\HnkFrameworkBundle\Notification\Notification;
use Hnk\HnkFrameworkBundle\Notification\SessionAwareNotificationManager;
use Symfony\Component\HttpFoundation\RedirectResponse;

trait NotificationTrait
{
    /**
     * Returns services required for Notification module to work
     * Use this list in getSubscribedServices method in controller
     *
     * @return array
     */
    public static function getNotificationServicesForSubscription(): array
    {
        return [
            'hnk_framework.notification_manager' => '?'.SessionAwareNotificationManager::class,
        ];
    }

    /**
     * Creates new flash message with type = 'success' and returns RedirectResponse
     *
     * @param string $message
     * @param string $route
     * @param array $routeParameters
     * @return RedirectResponse
     */
    public function redirectWithSuccess(string $message, string $route, array $routeParameters = []): RedirectResponse
    {
        return $this->redirectWithFlash($message, $route, $routeParameters, Notification::TYPE_SUCCESS);
    }

    /**
     * Creates new flash message with type = 'error' and returns RedirectResponse
     *
     * @param string $message
     * @param string $route
     * @param array $routeParameters
     * @return RedirectResponse
     */
    public function redirectWithError(string $message, string $route, array $routeParameters = []): RedirectResponse
    {
        return $this->redirectWithFlash($message, $route, $routeParameters, Notification::TYPE_SUCCESS);
    }

    /**
     * Creates new flash message and returns RedirectResponse
     *
     * @param string $message
     * @param string $route
     * @param array $routeParameters
     * @param string $notificationType
     *
     * @return RedirectResponse
     */
    public function redirectWithFlash(
        string $message,
        string $route,
        array $routeParameters = [],
        string $notificationType = Notification::TYPE_INFO
    ): RedirectResponse {
        $this->addFlash($notificationType, $message);

        return $this->redirect($this->generateUrl($route, $routeParameters));
    }

    /**
     * Creates new notification that can be used during the current request
     *
     * @param string $message
     * @param string $type
     */
    public function setNotification($message, $type = Notification::TYPE_INFO)
    {
        $this->get('hnk_framework.notification_manager')->addNotification($message, $type);
    }

}
