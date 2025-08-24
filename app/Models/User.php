<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Auth;
use Error;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Models\Permission;
use App\Models\Proposal;
use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\CustomVerifyEmailNotification;
use App\Traits\HasPermissions;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissions;
    // Table name (optional, if not following Laravel naming conventions)
    protected $table = 'users';

    // Use UUIDs instead of auto-incrementing IDs
    public $incrementing = false;
    protected $keyType = 'string';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'userid';

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
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userid',
        'name',
        'email',
        'pfno',
        'password',
        'role',
        'isactive',
        'isadmin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'isactive' => 'boolean',
        'isadmin' => 'boolean',
    ];


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification());
    }

    //functions 
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'userpermissions', 'useridfk', 'permissionidfk');
    }
    public function notifiabletypes()
    {
        return $this->belongsToMany(NotificationType::class, 'notifiableusers', 'useridfk', 'notificationfk');

    }

    public function department()
    {
        return $this->hasOneThrough(
            Department::class,
            Proposal::class,
            'useridfk', // Foreign key on proposals table
            'depid', // Foreign key on departments table
            'userid', // Local key on users table
            'departmentidfk' // Local key on proposals table
        )->latest();
    }
    public function defaultpermissions()
    {
        $defaultp = Permission::where('targetrole', Auth::user()->role)->orderBy('priorityno');
        return $defaultp;
    }

    public function haspermission($shortname)
    {
        // Super admin has all permissions
        if ((isset($this->isadmin) && $this->isadmin)) {
            return true;
        }

        // Check user permissions
        return $this->permissions()->where('shortname', $shortname)->exists();
    }


    // public function hasselfpermission($shortname)
    // {
    //     return $this->permissions()->where('shortname', $shortname)->exists();
    // }
    public function issuperadmin()
    {
        if ($this->isadmin) {
            return true;
        } else {
            return false;
        }
    }
    public function canapproveproposal($proposalid)
    {
        try {
            $proposal = Proposal::findOrFail($proposalid);
            if ($this->haspermission('canapproveproposal') && ($proposal->useridfk != $this->userid) && $proposal->receivedstatus == \App\Models\ReceivedStatus::RECEIVED && $proposal->approvalstatus == \App\Models\ApprovalStatus::PENDING) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }

    }

    public function canrejectproposal($proposalid)
    {
        try {
            $proposal = Proposal::findOrFail($proposalid);
            if ($this->haspermission('canrejectproposal') && ($proposal->useridfk != $this->userid) && $proposal->receivedstatus == \App\Models\ReceivedStatus::RECEIVED && $proposal->approvalstatus == \App\Models\ApprovalStatus::PENDING) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }

    }
    public function canproposechanges($proposalid)
    {
        try {
            $proposal = Proposal::findOrFail($proposalid);
            if ($this->haspermission('canproposechanges') && ($proposal->useridfk != $this->userid) && $proposal->approvalstatus == \App\Models\ApprovalStatus::PENDING && $proposal->receivedstatus == \App\Models\ReceivedStatus::RECEIVED) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }

    }
    public function canreceiveproposal($proposalid)
    {
        try {
            $proposal = Proposal::findOrFail($proposalid);
            if ($this->haspermission('canreceiveproposal') && ($proposal->useridfk != $this->userid) && $proposal->approvalstatus == \App\Models\ApprovalStatus::PENDING && $proposal->submittedstatus == \App\Models\SubmittedStatus::SUBMITTED && $proposal->receivedstatus == \App\Models\ReceivedStatus::PENDING) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }

    }
    public function canenableediting($proposalid)
    {
        try {
            $proposal = Proposal::findOrFail($proposalid);
            if ($this->haspermission('canenabledisableproposaledit') && ($proposal->useridfk != $this->userid) && $proposal->approvalstatus == \App\Models\ApprovalStatus::PENDING && $proposal->submittedstatus == \App\Models\SubmittedStatus::SUBMITTED && $proposal->receivedstatus == \App\Models\ReceivedStatus::RECEIVED && !$proposal->allowediting) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }

    }
    public function candisableediting($proposalid)
    {
        try {
            $proposal = Proposal::findOrFail($proposalid);
            if ($this->haspermission('canenabledisableproposaledit') && ($proposal->useridfk != $this->userid) && $proposal->approvalstatus == \App\Models\ApprovalStatus::PENDING && $proposal->submittedstatus == \App\Models\SubmittedStatus::SUBMITTED && $proposal->receivedstatus == \App\Models\ReceivedStatus::RECEIVED && $proposal->allowediting) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }

    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'user_id', 'userid');
    }

    public function activeRoles()
    {
        return $this->userRoles()->active();
    }

    public function hasActiveRole($roleType)
    {
        return $this->activeRoles()->where('role_type', $roleType)->exists();
    }

    public function isCommitteeMember()
    {
        return $this->hasActiveRole('committee_member');
    }

    public function getCurrentRoles()
    {
        return $this->activeRoles()->pluck('role_type')->toArray();
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'useridfk', 'userid');
    }

    public function getEffectivePermissions()
    {
        $permissions = [];

        // Base researcher permissions
        $permissions = array_merge($permissions, $this->getResearcherPermissions());

        // Committee member permissions
        if ($this->isCommitteeMember()) {
            $permissions = array_merge($permissions, $this->getCommitteePermissions());
        }

        // Additional assigned permissions
        $userPermissions = $this->permissions()->pluck('shortname')->toArray();
        $permissions = array_merge($permissions, $userPermissions);

        return array_unique($permissions);
    }

    private function getResearcherPermissions()
    {
        return ['cansubmitproposal', 'canviewmyproposals', 'caneditmyproposal'];
    }

    private function getCommitteePermissions()
    {
        return ['canviewallproposals', 'canapproveproposal', 'canrejectproposal', 'canproposechanges'];
    }

    public function hasPermissionDynamic($permission)
    {
        if ($this->isadmin)
            return true;

        return in_array($permission, $this->getEffectivePermissions());
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'userid');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }
}
