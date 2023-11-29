<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralTable extends Model
{
    use SoftDeletes;
   protected $table = 'CONFIG_GENERAL_TABLES';

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'active',
        'module',
        'created_by',
        'deleted_at'
    ];

    public static function find($id)
    {
        $entries = self::all();

        foreach ($entries as $entry) {
            if ($entry['id'] == $id) {
                return $entry;
            }
        }
        return 'nothing';
    }
}
