<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $table = 'GEN_FILES';
    protected $fillable = [
        'module',
        'reference_number',
        'name',
        'originalDocumentName',
        'extension',
        'path',
        'file_type',
        'file_size',
        'created_by',
        'created_name'
    ];
}
