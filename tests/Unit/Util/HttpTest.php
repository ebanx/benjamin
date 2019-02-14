<?php

namespace Tests\Unit\Util;

use Ebanx\Benjamin\Models\Notification;
use Ebanx\Benjamin\Util\Http;
use Tests\TestCase;

class HttpTest extends TestCase
{
    public static function getNotificationsData()
    {
        return [
            [new Notification('payment_status_change', 'update', ['123']), true],
            [new Notification('payment_status_change', 'refund', ['123']), true],
            [new Notification('payment_status_change', 'invalid', ['123']), false],
            [new Notification('payment_status_changes', 'update', ['123']), false],
            [new Notification('payment_status_change', 'update', []), false],
        ];
    }

    /**
     * @dataProvider getNotificationsData
     */
    public function testValidNotification($notification, $valid)
    {
        $this->assertEquals($valid, Http::isValidNotification($notification));
    }

    public function getValidNotificationGets()
    {
        $expected_operation = 'payment_status_change';
        $expected_notification_type = 'update';
        $expected_hash  = ['123'];
        $notification = new Notification($expected_operation, $expected_notification_type, $expected_hash);

        $this->assertEquals($expected_operation, $notification->getOperation());
        $this->assertEquals($expected_notification_type, $notification->getNotificationType());
        $this->assertEquals($expected_hash, $notification->getHashCodes());
    }
}
