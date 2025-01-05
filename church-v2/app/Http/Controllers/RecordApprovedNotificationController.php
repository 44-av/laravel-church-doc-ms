<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RecordApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $record;
    protected $approvedBy;

    public function __construct($record, $approvedBy)
    {
        $this->record = $record;
        $this->approvedBy = $approvedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => sprintf('Your record "%s" has been approved by %s.', $this->record->title, $this->approvedBy),
            'record_id' => $this->record->id,
            'approved_by' => $this->approvedBy,
        ];
    }
}
