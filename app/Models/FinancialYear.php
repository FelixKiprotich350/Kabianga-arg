<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use HasFactory;
    protected $table = 'finyears';
 
    protected $primaryKey = 'id';

    protected $fillable = [
        'finyear',
        'startdate', 
        'enddate',
        'description'
    ];

    public function grants()
    {
        return $this->hasMany(Grant::class, 'finyearfk', 'id');
    }
}
