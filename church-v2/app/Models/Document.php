<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'tdocuments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'document_type',
        'full_name',
        'file',
        'uploaded_by',
    ];
}
