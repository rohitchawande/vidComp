<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg;
use FFMpeg\Format\Video\X264;

class test extends Controller
{

    public function transcodePBmedia()
    {

        $inputFileName = 'videoplayback.mp4';
        $outputFileName = uniqid() . '.mp4';

        $lowBitrateFormat = (new X264('libmp3lame', 'libx264'))->setKiloBitrate(500);
        $starttime = Carbon::now();

        echo 'started at' . $starttime;

        FFMpeg::fromDisk('local')
            ->open($inputFileName)
            // ->addFilter(function ($filters) {
            //     $filters->resize(new Dimension(960, 540));
            // })
            ->export()
            ->toDisk('converted_videos')
            ->inFormat($lowBitrateFormat)
            ->onProgress(function ($percentage) {
                echo "{$percentage}% transcoded" . PHP_EOL;
            })
            ->save($outputFileName);

        echo 'started at' . $starttime->diffForHumans();
    }


    public function transcodeFFMPEG()
    {
        $inputFileName = 'videoplayback.mp4';
        $outputFileName = uniqid() . '.mp4';


        $ffmpeg = FFMpeg::create(['timeout' => 3600]);

        $video = $ffmpeg->open(storage_path('app/' . $inputFileName));

        $video->filters()->resize(new Dimension(960, 540))->synchronize();

        $format = new X264("libmp3lame", "libx264");

        $format->on('progress', function ($video, $format, $percentage) {
            echo "$percentage % transcoded" . PHP_EOL;
        })->setKiloBitrate(500);


        $starttime = Carbon::now();
        echo 'started at ' . $starttime . PHP_EOL;

        $video->save($format, storage_path('app/' . $outputFileName));

        echo 'started at' . $starttime->diffForHumans();
    }

    public function transcodeHandBrakeCLI()
    {

        $starttime = Carbon::now();
        echo 'started at ' . $starttime . PHP_EOL;
        $inputFileName = 'videoplayback.mp4';

        $outputFileName = uniqid() . '.mp4';

        shell_exec("HandBrakeCLI -i /mnt/d/GitHub/protone/storage/app/videoplayback.mp4 -o /mnt/d/GitHub/protone/storage/app/" . $outputFileName . " -e x264 -w 960 -l 540");

        echo 'started at' . $starttime->diffForHumans();
    }
}
