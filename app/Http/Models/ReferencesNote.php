<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReferencesNote extends BaseModel
{
    public $incrementing = false;
    protected $fillable = ['id', 'reference_id', 'note'];
    protected $table = 'references_notes';
}