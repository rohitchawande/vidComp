<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
        return Carbon::parse($value)->setTimezone('Asia/Kolkata')->format('d-m-Y H:i:s');
    }

    public function getCompletedAtAttribute($value)
    {
        if ($value != '') {
            return Carbon::parse($value)->setTimezone('Asia/Kolkata')->format('d-m-Y H:i:s');
        }
    }

    public function getTranscoderAttribute($value)
    {
        switch ($value) {
            case 'PB':
                return 'PBMedia';
            case 'FF':
                return 'FFMpeg Native';
            case 'HB':
                return 'HandBrakeCLI';
        }
    }

    public function getBitrateAttribute($value)
    {
        return $value . "kbps";
    }

    public function getOriginalFileSizeAttribute($value)
    {
        return round((($value / 1024) / 1024), 2) . "MB";
    }

    public function getCompressedFileSizeAttribute($value)
    {
        if ($value != '') {
            return round((($value / 1024) / 1024), 2) . "MB";
        }
    }
}
