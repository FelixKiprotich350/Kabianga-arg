<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalReview extends Model
{
    protected $table = 'proposalreviews';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'proposalid',
        'subject',
        'reviewcomment',
        'reviewerid',
        'status',
        'addresstime'
    ];

    protected $casts = [
        'addresstime' => 'datetime',
        'status' => 'string',
        'reviewerid' => 'string'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = \Illuminate\Support\Str::uuid();
        });
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposalid', 'proposalid');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewerid', 'userid');
    }
}