<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnovationTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'name',
        'contacts',
        'role'
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id', 'proposalid');
    }
}