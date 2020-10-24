<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isNull;

class Transcodes extends Model
{
    protected $table = 'transcodes';
    protected $primaryKey = 'id';
    protected $fillable = [
        "original_file_name",
        "original_file_size",
        "transcoder",
        "bitrate",
        "resolution"
    ];


    protected $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Kolkata')->format('H:i:s d-m-Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        if (!isNull($value)) {
            return Carbon::parse($value)->setTimezone('Asia/Kolkata')->format('H:i:s d-m-Y');
        }
    }
}
