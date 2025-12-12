<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalInnovationMeta extends Model
{
    protected $table = 'proposal_innovation_meta';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'proposal_id',
        'gap',
        'solution',
        'targetcustomers',
        'valueproposition',
        'competitors',
        'attraction'
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
        return $this->belongsTo(Proposal::class, 'proposal_id', 'proposalid');
    }
}