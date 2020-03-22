<?php

namespace Hnk\HnkFrameworkBundle\Notification;


class Notification
{
    const TYPE_SUCCESS = 'success';
    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param string $message
     * @param string $type
     */
    public function __construct(string $message, string $type = self::TYPE_INFO)
    {
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
