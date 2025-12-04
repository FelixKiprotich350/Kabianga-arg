<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ResearchProject extends Model
{
    use HasFactory;
    protected $table = 'researchprojects';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_COMPLETED = 'COMPLETED';

    const VALID_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_PAUSED,
        self::STATUS_CANCELLED,
        self::STATUS_COMPLETED,
    ];



    // Use UUIDs instead of auto-incrementing IDs
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'researchid';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'researchid',
        'researchnumber',
        'proposalidfk',
        'projectstatus',
        'ispaused',
        'commissioningdate',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            if (!in_array($model->projectstatus, self::VALID_STATUSES)) {
                throw new \InvalidArgumentException('Invalid project status. Must be one of: ' . implode(', ', self::VALID_STATUSES));
            }
        });
    }

    public function fundingsummary()
    {
        $fundings = ResearchFunding::where('researchidfk', $this->researchid)->get();
        $total = $fundings->sum('amount');
        $result = [
            'total' => $total,
            'fundingrows' => $fundings->count(),
        ];
        return $result;
    }
    
    public function proposal()
    {
        return $this->hasOne(Proposal::class, 'proposalid', 'proposalidfk');
    }
    public function applicant()
    {
        return $this->hasOneThrough(User::class, Proposal::class, 'proposalid', 'userid', 'proposalidfk', 'useridfk');
    }
    public function mandeperson()
    {
        return $this->belongsTo(User::class, 'supervisorfk', 'userid');
    }
    
    /**
     * Check if the project is commissioned and can receive funding
     */
    public function canReceiveFunding()
    {
        return !is_null($this->commissioningdate);
    }
    
    /**
     * Check if the given user is the owner of this project
     */
    public function isOwnedBy($userId)
    {
        return $this->applicant && $this->applicant->userid === $userId;
    }
    
    /**
     * Check if project has reached maximum funding tranches (3)
     */
    public function hasReachedMaxTranches()
    {
        return ResearchFunding::where('researchidfk', $this->researchid)->count() >= 3;
    }
}
