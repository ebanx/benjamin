<?php

namespace Ebanx\Benjamin\Util;

use Ebanx\Benjamin\Models\Notification;

class Http
{
    const OPERATION = 'payment_status_change';
    const UPDATE = 'update';
    const REFUND = 'refund';
    const CHARGEBACK = 'chargeback';

    public static function isValidNotification(Notification $notification)
    {
        return self::isValidOperation($notification)
            && self::isValidNotificationType($notification)
            && self::isValidHashCodes($notification);
    }

    private static function isValidOperation(Notification $notification)
    {
        return $notification->getOperation() === self::OPERATION;
    }

    private static function isValidNotificationType(Notification $notification)
    {
        return $notification->getNotificationType() === self::UPDATE
            || $notification->getNotificationType() === self::REFUND
            || $notification->getNotificationType() === self::CHARGEBACK;
    }

    private static function isValidHashCodes(Notification $notification)
    {
        return count($notification->getHashCodes()) > 0;
    }
}
