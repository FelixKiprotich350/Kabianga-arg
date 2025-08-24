<?php

namespace App\Models;

use Auth;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ResearchTheme;

enum SubmittedStatus: string
{
    case PENDING = 'PENDING';
    case SUBMITTED = 'SUBMITTED';
}

enum ReceivedStatus: string
{
    case PENDING = 'PENDING';
    case RECEIVED = 'RECEIVED';
}

enum ApprovalStatus: string
{
    case DRAFT = 'DRAFT';
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
}



class Proposal extends Model
{
    use HasFactory;
    protected $table = 'proposals';

    // Use UUIDs instead of auto-incrementing IDs
    public $incrementing = false;
    protected $keyType = 'string';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'proposalid';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'proposalid',
        'grantnofk',
        'useridfk',
        'pfnofk',
        'themefk',
        'highqualification',
        'departmentidfk',
        'approvalstatus',
        'faxnumber',
        'cellphone',
        'officephone',
        'submittedstatus',
        'receivedstatus',
        'allowediting'
    ];

    protected $casts = [
        'submittedstatus' => SubmittedStatus::class,
        'receivedstatus' => ReceivedStatus::class,
        'approvalstatus' => ApprovalStatus::class,
        'allowediting' => 'boolean',
    ];



    //functions
    public function applicant()
    {
        return $this->belongsTo(User::class, 'useridfk', 'userid');
    }
    //functions
    public function department()
    {
        return $this->belongsTo(Department::class, 'departmentidfk', 'depid');
    }
    //functions
    public function grantitem()
    {
        return $this->belongsTo(Grant::class, 'grantnofk', 'grantid');
    }
    public function themeitem()
    {
        return $this->belongsTo(ResearchTheme::class, 'themefk', 'themeid');
    }

    public function collaborators()
    {
        return $this->hasMany(Collaborator::class, 'proposalidfk', 'proposalid');
    }
    public function publications()
    {
        return $this->hasMany(Publication::class, 'proposalidfk', 'proposalid');
    }
    
    public function expenditures()
    {
        return $this->hasMany(Expenditureitem::class, 'proposalidfk', 'proposalid');
    }
    public function researchdesigns()
    {
        return $this->hasMany(ResearchDesignItem::class, 'proposalidfk', 'proposalid');
    }
    public function workplans()
    {
        return $this->hasMany(Workplan::class, 'proposalidfk', 'proposalid');
    }
    public function researchProject()
    {
        return $this->belongsTo(ResearchProject::class, 'research_project_id', 'id');
    }
    public function proposalchanges()
    {
        return $this->belongsTo(ResearchTheme::class, 'themefk', 'themeid');
    }

    public function hasPendingUpdates()
    {
        try {
            $changes = $this->proposalchanges()->get();
            if ($changes->where('status', 'Pending')) {
                return true;
            }
            else {
                return false;
            }
        } catch (\Exception $exception) {
            return false;
        }

    }
    public function isApprovable()
    {
        try {
            $user = Auth::user();
            if (($user->userid == $this->useridfk) && $this->allowediting && $this->approvalstatus == ApprovalStatus::PENDING) {
                return true;
            }
            else {
                return false;
            }
        } catch (\Exception $exception) {
            return false;
        }

    }

    public function isEditable()
    {
        // Cannot edit if approved or rejected
        if ($this->approvalstatus == ApprovalStatus::APPROVED || $this->approvalstatus == ApprovalStatus::REJECTED) {
            return false;
        }
        
        return $this->receivedstatus == ReceivedStatus::PENDING || $this->allowediting;
    }
}
