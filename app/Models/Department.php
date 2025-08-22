<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Proposal;
use App\Models\School;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';

    // Use UUIDs instead of auto-incrementing IDs
    public $incrementing = false;
    protected $keyType = 'string';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'depid';

    // Boot method to auto-generate UUIDs
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'schoolfk', 'schoolid');
    }
    
    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Proposal::class,
            'departmentidfk',
            'userid',
            'depid',
            'useridfk'
        )->distinct();
    }
}
