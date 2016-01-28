<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;
use App\Departure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ExceptionCounterController;

class DepartureUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'departure:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actual information on departing trains';

    /**
     * Create a new command instance.
     *
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
                ['query' => ['api_key' => $api_key, 'loc' => 'NYK']])->getBody();
        } catch (ServerException $e) {
            Log::error('Guzzle error: ' . $e->getMessage());
            $count->exceptionCounter('departure:start', 'Departure');
            return;
        } catch (ConnectException $e) {
            Log::error('Guzzle error: ' . $e->getMessage());
            $count->exceptionCounter('departure:start', 'Departure');
            return;
        }

        try {
            $data = Departure::orderBy('created_at', 'desc')->first();;
            if ($data) {
                $data->data = $request;
                $data->save();
            } else {
                $departure = new Departure;
                $departure->data = $request;
                $departure->save();
            }
        } catch (ModelNotFoundException $e) {
            Log::error('Departure request error: ' . $e->getMessage());
            $count->exceptionCounter('departure:start', 'Departure');
        }
    }
}
