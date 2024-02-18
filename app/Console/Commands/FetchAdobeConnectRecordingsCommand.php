<?php

namespace App\Console\Commands;

use App\Services\AdobeConnect\AdobeConnectService;
use Illuminate\Console\Command;

class FetchAdobeConnectRecordingsCommand extends Command
{
    protected $signature = 'adobe:fetch';

    protected $description = 'Command description';


    public function handle(AdobeConnectService $service): void
    {

        $service->getRecordings();
    }
}
