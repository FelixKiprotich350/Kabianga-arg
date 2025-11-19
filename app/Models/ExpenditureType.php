<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenditureType extends Model
{
    use HasFactory;

    protected $table = 'expendituretypes';
    protected $primaryKey = 'typeid';
    public $incrementing = true;

    protected $fillable = [
        'typename',
        'description',
        'isactive'
    ];

    protected $casts = [
        'isactive' => 'boolean',
    ];

    public function expenditures()
    {
        return $this->hasMany(Expenditureitem::class, 'itemtypeid', 'typeid');
    }
}