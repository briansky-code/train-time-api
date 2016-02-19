<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ExceptionCounterController;

class MonitoringCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command checks the availability of the service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param ExceptionCounterController $count
     * @return mixed
     */
    public function handle(ExceptionCounterController $count)
    {
        $api_key = config('services.traintime.api_key');
        $client = new Client(['base_uri' => 'https://traintime.lirr.org/']);
        try {
            $request = $client->request('GET', 'api/Departure',
                ['query' => ['api_key' => $api_key, 'loc' => 'NYK']]);
        } catch(ConnectException $e) {
            Log::error('Guzzle error (monitoring command): ' . $e->getMessage());
            $count->monitoringCount();
            return;
        } catch(ClientException $e) {
            Log::error('Guzzle error (monitoring command): ' . $e->getMessage());
            $count->monitoringCount();
            return;
        }

        if($request->getStatusCode() == 200) {
            $count->exceptionCounterReset('monitoring');
        }

    }
}
