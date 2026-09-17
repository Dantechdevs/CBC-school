<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KemisSyncLog extends Model
{
    protected $table = 'kemis_sync_logs';

    protected $fillable = [
        'sync_type', 'records_synced', 'records_failed', 'status',
        'error_log', 'initiated_by', 'started_at', 'completed_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function initiator() { return $this->belongsTo(StaffMember::class, 'initiated_by'); }

    public function scopeRunning($q)   { return $q->where('status', 'running'); }
    public function scopeCompleted($q) { return $q->where('status', 'completed'); }
    public function scopeFailed($q)    { return $q->where('status', 'failed'); }
}
