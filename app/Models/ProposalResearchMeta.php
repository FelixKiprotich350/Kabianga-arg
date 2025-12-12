<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalResearchMeta extends Model
{
    protected $table = 'proposal_research_meta';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'proposal_id',
        'objectives',
        'hypothesis',
        'significance',
        'ethicals',
        'expoutput',
        'socio_impact',
        'res_findings'
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