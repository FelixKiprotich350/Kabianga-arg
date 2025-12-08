<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalReviewer extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'assigned_by',
        'assigned_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id', 'proposalid');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'userid');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'userid');
    }
}
