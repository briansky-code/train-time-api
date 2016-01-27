<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;
use App\Departure;
use App\ExceptionsCounter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
            $this->exceptionCounter();
            return;
        } catch (ConnectException $e) {
            Log::error('Guzzle error: ' . $e->getMessage());
            $this->exceptionCounter();
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
            $this->exceptionCounter();
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
            $data = ExceptionsCounter::where('command_name', 'departure:start')->firstOrFail();
        } catch (MethodNotAllowedHttpException $e) {
            Log::error('Exception counter error: ' . $e->getMessage());
        }

        if ($data->counter == 10) {
            $data->counter = 0;
            $data->save();

            Mail::send('emails.exceptions-count', array('command_name' => 'departure:start', 'api_url' => 'Departure'), function ($message) {
                $message->from('user@example.ru', 'Laravel')->subject('Penn Api');
            });
        } else {
            $data->counter = $data->counter + 1;
            $data->save();
        }

    }
}
