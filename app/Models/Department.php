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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'depid',
        'shortname',
        'description',
        'schoolfk'
    ];

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
            'departmentidfk', // Foreign key on proposals table
            'userid', // Foreign key on users table
            'depid', // Local key on departments table
            'useridfk' // Local key on proposals table
        )->distinct();
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'departmentidfk', 'depid');
    }
}
