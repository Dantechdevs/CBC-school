<?php

namespace App\Livewire\Notifications;

use App\Jobs\SendSmsJob;
use App\Models\Guardian;
use App\Models\SchoolNotification;
use App\Services\AfricasTalkingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SendNotification extends Component
{
    public string $title        = '';
    public string $message      = '';
    public string $type         = 'general';
    public string $channel      = 'sms';
    public string $targetGrade  = '';
    public string $targetGroup  = 'all'; // all | grade | boarding | day

    public bool $sending  = false;
    public bool $sent     = false;
    public int  $count    = 0;
    public string $flash  = '';

    protected $rules = [
        'title'   => 'required|string|max:100',
        'message' => 'required|string|max:480',
        'channel' => 'required|in:sms,email,push,all',
        'type'    => 'required|in:general,fees,exam,report_card,attendance,emergency',
    ];

    public function getRecipientsCount(): int
    {
        $query = Guardian::query();
        if ($this->targetGrade) {
            $query->whereHas('learners', fn($q) => $q->where('grade_level', $this->targetGrade)->where('is_active', true));
        }
        if ($this->targetGroup === 'boarding') {
            $query->whereHas('learners', fn($q) => $q->where('boarding_status', 'boarding'));
        } elseif ($this->targetGroup === 'day') {
            $query->whereHas('learners', fn($q) => $q->where('boarding_status', 'day'));
        }
        return $query->distinct()->count();
    }

    public function updatedTargetGrade(): void  { $this->count = $this->getRecipientsCount(); }
    public function updatedTargetGroup(): void  { $this->count = $this->getRecipientsCount(); }

    public function send(): void
    {
        $this->validate();
        $this->sending = true;

        $staff = Auth::user()->staffMember;

        $notification = SchoolNotification::create([
            'sender_id'         => $staff->id,
            'title'             => $this->title,
            'message'           => $this->message,
            'type'              => $this->type,
            'channel'           => $this->channel,
            'target_grade'      => $this->targetGrade ?: null,
            'total_recipients'  => $this->getRecipientsCount(),
            'status'            => 'queued',
            'scheduled_at'      => now(),
        ]);

        // Dispatch background job
        SendSmsJob::dispatch($notification->id, $this->targetGrade, $this->targetGroup);

        $this->sent     = true;
        $this->sending  = false;
        $this->flash    = "Notification queued for {$notification->total_recipients} recipients.";
        $this->reset(['title', 'message']);
    }

    public function render()
    {
        $this->count = $this->getRecipientsCount();
        return view('livewire.notifications.send-notification')->layout('layouts.admin');
    }
}
