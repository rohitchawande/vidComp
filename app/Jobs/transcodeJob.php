<?php

namespace App\Jobs;

use App\Http\Controllers\videoTranscoderController;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class transcodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;
    public $tries = 5;


    private $transCode;
    private $inputFileName;
    private $transcoder;
    private $bitrate;
    private $resolution;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transCode, $inputFileName, $transcoder, $bitrate, $resolution)
    {
        $this->transCode = $transCode;
        $this->inputFileName = $inputFileName;
        $this->transcoder = $transcoder;
        $this->bitrate = $bitrate;
        $this->resolution = $resolution;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $height = explode('x', $this->resolution)[1];
        $width = explode('x', $this->resolution)[0];
        $starttime = Carbon::now();

        echo 'started at' . $starttime;
        switch ($this->transcoder) {
            case 'PB':
                $outputFileName = $this->inputFileName . '_PBmedia.mp4';
                (new videoTranscoderController)->transcodePBmedia($this->inputFileName, $outputFileName, $height, $width, $this->bitrate);
                $this->updateJobData($outputFileName);
                break;
            case 'FF': // disabled
                $outputFileName = $this->inputFileName . '_FFMpeg.mp4';
                (new videoTranscoderController)->transcodeFFMPEG($this->inputFileName, $outputFileName, $height, $width, $this->bitrate);
                $this->updateJobData($outputFileName);
                break;
            case 'HB':
                $outputFileName = $this->inputFileName . '_HBCLI.mp4';
                (new videoTranscoderController)->transcodeHandBrakeCLI($this->inputFileName, $outputFileName, $height, $width, $this->bitrate);
                $this->updateJobData($outputFileName);
                break;
            default:
                return false;
        }
        echo 'started at' . $starttime->diffForHumans();
    }

    private function updateJobData($outputFileName)
    {
        $this->transCode->compressed_file_name = $outputFileName;
        $this->transCode->compressed_file_size = Storage::disk('converted')->size($outputFileName);
        $this->transCode->completed_at = Carbon::now();
        $this->transCode->save();
    }
}
