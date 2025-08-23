<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchTheme extends Model
{
    use HasFactory;
    
    protected $table = 'researchthemes';
    protected $primaryKey = 'themeid';
    public $incrementing = false;
    protected $keyType = 'int';
    
    protected $fillable = [
        'themeid',
        'themename',        
        'applicablestatus',         
        'themedescription'
    ];
}
