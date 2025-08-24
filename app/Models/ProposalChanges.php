<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProposalChanges extends Model
{
    use HasFactory;
    protected $table = 'proposalchanges';

    protected $fillable = [
        'proposalidfk',
        'suggestedbyfk', 
        'suggestedchange',
        'triggerissue',
        'status'
    ];

    //function properties
    public function suggestedby()
    {
        return $this->belongsTo(User::class, 'suggestedbyfk');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposalidfk');
    }
}
