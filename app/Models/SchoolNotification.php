<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolNotification extends Model
{
    use HasFactory;

    protected $table = 'school_notifications';

    protected $fillable = [
        'sender_id','title','message','type','channel','target_recipients',
        'target_grade','total_recipients','sent_count','failed_count',
        'status','scheduled_at','sent_at',
    ];

    protected $casts = [
        'target_recipients' => 'array',
        'scheduled_at'      => 'datetime',
        'sent_at'           => 'datetime',
    ];

    public function sender() { return $this->belongsTo(StaffMember::class, 'sender_id'); }
    public function logs()   { return $this->hasMany(NotificationLog::class, 'notification_id'); }
}
