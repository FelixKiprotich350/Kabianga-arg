<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $table = 'notifications';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'data', 'read_at'
    ];
    
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userid');
    }
    
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }
    
    public function isRead()
    {
        return !is_null($this->read_at);
    }
    
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}