<?php

namespace App\Console\Commands;

use App\ExceptionsCounter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Trains;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class TrainsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trains:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Getting information about each train and save for statistics';

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

        $data = json_decode($request, true);

        foreach ($data['TRAINS'] as $item) {
            if (!empty($item['TRACK'])) {
                $train = Trains::where('sched', $item['SCHED'])
                    ->where('train_id', $item['TRAIN_ID'])
                    ->where('track', $item['TRACK'])
                    ->orderBy('created_at', 'desc')->first();

                if (!$train) {
                    $new_train = new Trains;
                    $new_train->sched = $item['SCHED'];
                    $new_train->train_id = $item['TRAIN_ID'];
                    $new_train->track = $item['TRACK'];
                    $new_train->save();
                }
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
            $data = ExceptionsCounter::where('command_name', 'trains:start')->firstOrFail();
        } catch (MethodNotAllowedHttpException $e) {
            Log::error('Exception counter error: ' . $e->getMessage());
        }

        if ($data->counter == 10) {
            $data->counter = 0;
            $data->save();

            Mail::send('emails.exceptions-count', array('command_name' => 'trains:start', 'api_url' => 'Departure'), function ($message) {
                $message->from('user@example.ru', 'Laravel')->subject('Penn Api');
            });
        } else {
            $data->counter = $data->counter + 1;
            $data->save();
        }

    }
}
