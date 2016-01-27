<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Console\Command;
use App\Departure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

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
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $api_key = config('services.traintime.api_key');

        $client = new Client(['base_uri' => 'https://traintime.lirr.org/']);

        try {
            $request = $client->request('GET', 'api/Departure',
                ['query' => ['api_key' => $api_key, 'loc' => 'NYK']])->getBody();
        } catch (ServerException $e) {
            Log::error('Guzzle error: ' . $e->getMessage());
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
        }
    }
}
