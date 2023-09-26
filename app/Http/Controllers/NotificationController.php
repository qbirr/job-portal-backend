<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use LaravelIdea\Helper\App\Models\_IH_Notification_C;

class NotificationController extends AppBaseController {
    /**
     * @param Notification $notification
     * @return JsonResponse
     */
    public function readNotification(Notification $notification) {
        $notification->read_at = Carbon::now();
        $notification->save();

        return $this->sendSuccess(__('messages.flash.notification_read'));
    }

    /**
     * @return JsonResponse
     */
    public function readAllNotification() {
        Notification::whereReadAt(null)->where('user_id', getLoggedInUserId())->update(['read_at' => Carbon::now()]);

        return $this->sendSuccess(__('messages.flash.all_notification_read'));
    }

    public function fetch() {
        $notifications = Notification::whereIn('notification_for', auth()->user()->roles->pluck('id'))->whereNull('read_at')
            ->where('user_id', getLoggedInUserId())->orderByDesc('created_at');
        return $this->sendSuccess($notifications->get());
    }
}
