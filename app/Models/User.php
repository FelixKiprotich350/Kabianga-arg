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
use App\Services\SimpleMailService;
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
        $url = route('password.reset', ['token' => $token]) . '?email=' . urlencode($this->email);
        $content = "You are receiving this email because we received a password reset request for your account. This link will expire in 60 minutes.";
        
        SimpleMailService::send($this->email, 'Reset Your Password', $content, $url, 'Reset Password');
    }

    public function sendEmailVerificationNotification()
    {
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $this->userid, 'hash' => sha1($this->email)]
        );
        $content = "Please click the button below to verify your email address.";
        
        SimpleMailService::send($this->email, 'Verify Your Email Address', $content, $url, 'Verify Email');
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
        if ($this->isadmin) {
            return ['*']; // All permissions for admin
        }

        // Only user-assigned permissions
        return $this->permissions()->pluck('shortname')->toArray();
    }

    public function hasPermissionDynamic($permission)
    {
        if ($this->isadmin)
            return true;

        return $this->permissions()->where('shortname', $permission)->exists();
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
