<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class NotificationService
{
    public static function notify($userId, $message)
    {
        DB::table('notifications')->insert([
            'user_id' => $userId,
            'message' => $message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public static function notifyAdmin($message)
    {
        // Get all Admin user ids
        $adminIds = DB::table('users')->where('role', 'Admin')->pluck('id');
        foreach ($adminIds as $id) {
            self::notify($id, $message);
        }
    }
}
