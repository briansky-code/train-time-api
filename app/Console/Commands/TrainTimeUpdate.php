<?php

namespace App\Console\Commands;

use App\ExceptionsCounter;
use App\TrainTime;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class TrainTimeUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'train_time:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtaining data on the directions';

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
        $destinations = array('LBH', 'FPT', 'JAM', 'OBY', 'WGH', 'SFD', 'GNK', 'BRT', 'HEM', 'HVL', 'HUN', 'PJN', 'FMD', 'RON', 'FRY', 'PWS', 'MPK', 'BTA', 'SPK', 'MTK');

        $client = new Client(['base_uri' => 'https://traintime.lirr.org/']);

        foreach ($destinations as $key => $value) {

            try {
                $request = $client->request('GET', 'api/TrainTime',
                    ['query' => ['api_key' => $api_key, 'startsta' => 'NYK', 'endsta' => $value]])->getBody();
            } catch (ServerException $e) {
                Log::error('Guzzle error: ' . $e->getMessage());
                $this->exceptionCounter();
                break;
            } catch (ConnectException $e) {
                Log::error('Guzzle error: ' . $e->getMessage());
                $this->exceptionCounter();
                break;
            }

            try {
                $data = TrainTime::firstOrNew(['name' => $value]);
                if ($data->exists) {
                    $data->data = $request;
                } else {
                    $data->name = $value;
                    $data->data = $request;
                }
                $data->save();
            } catch (ModelNotFoundException $e) {
                Log::error('Train Time request, value "' . $value . '": ' . $e->getMessage());
                $this->exceptionCounter();
            }
        }
    }

    /**
     * The method checks the errors and sends an email to the administrator
     * if the number has reached 10, and resets the counter.
     *
     */
    private function exceptionCounter()
    {
        try {
            $data = ExceptionsCounter::where('command_name', 'train_time:start')->firstOrFail();
        } catch (MethodNotAllowedHttpException $e) {
            Log::error('Exception counter error: ' . $e->getMessage());
        }

        if ($data->counter == 10) {
            $data->counter = 0;
            $data->save();

            Mail::send('emails.exceptions-count', array('command_name' => 'train_time:start', 'api_url' => 'TrainTime'), function ($message) {
                $message->from('user@example.ru', 'Laravel')->subject('Penn Api');
            });
        } else {
            $data->counter = $data->counter + 1;
            $data->save();
        }

    }
}
