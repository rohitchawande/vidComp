<?php

namespace App\Http\Controllers;

use App\Jobs\transcodeJob;
use App\Models\Transcodes;
use FFMpeg\Coordinate\Dimension;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\FFMpeg as NativeFFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class videoTranscoderController extends Controller
{
    public function gateway(Request $request)
    {
        $bitrate = $request->bitrate;
        $transcoder = $request->transcoder;
        $resolution = $request->resolution;

        $fileName = $request->file('video_file')->getClientOriginalName();
        $fileext = $request->file('video_file')->getClientOriginalExtension();
        $fileSize = $request->file('video_file')->getSize();
        $newFileName = uniqid() . ".$fileext";

        Storage::put($newFileName, File::get($request->file('video_file')->path()));

        $transCode = Transcodes::create([
            "original_file_name" => $fileName,
            "original_file_size" => $fileSize,
            "transcoder" => $transcoder,
            "bitrate" => $bitrate,
            "resolution" => $resolution
        ]);

        transcodeJob::dispatch($transCode, $newFileName, $transcoder, $bitrate, $resolution);

        return Redirect::to('/');
    }

    public function transcodePBmedia($inputFilePath, $outputFileName, $height, $width, $bitrate)
    {
        $lowBitrateFormat = (new X264('libmp3lame', 'libx264'))->setKiloBitrate($bitrate);

        FFMpeg::fromDisk('original')
            ->open($inputFilePath)
            ->addFilter(function ($filters) use ($width, $height) {
                $filters->resize(new Dimension($width, $height));
            })
            ->export()
            ->toDisk('converted')
            ->inFormat($lowBitrateFormat)
            ->onProgress(function ($percentage) {
                echo "{$percentage}% transcoded" . PHP_EOL;
            })
            ->save($outputFileName);
    }

    public function transcodeFFMPEG($inputFilePath, $outputFileName, $height, $width, $bitrate)
    {
        $ffmpeg = NativeFFMpeg::create(['timeout' => 3600]);

        $video = $ffmpeg->open(Storage::disk('original')->path('') . $inputFilePath);

        $video->filters()
            ->resize(new Dimension($width, $height))
            ->synchronize();

        $format = new X264("libmp3lame", "libx264");

        $format->on('progress', function ($video, $format, $percentage) {
            echo "$percentage % transcoded" . PHP_EOL;
        })->setKiloBitrate($bitrate);

        $video->save($format, Storage::disk('converted')->path('') . $outputFileName);
    }

    public function transcodeHandBrakeCLI($inputFilePath, $outputFileName, $height, $width, $bitrate)
    {

        $execString = "HandBrakeCLI -i " . Storage::disk('original')->path('') . $inputFilePath . " -o " . Storage::disk('converted')->path('') . $outputFileName . " -e x264 -b $bitrate -w $width -l $height";
        // echo $execString;

        shell_exec($execString);
    }
}
