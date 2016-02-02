<?php

namespace App\Http\Controllers;

use App\ExceptionsCounter;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionCounterController extends Controller
{

    /**
     * The method checks the errors and sends an email to the administrator
     * if the number has reached 10, and resets the counter.
     *
     * @param $commandName
     * @param $apiUrl
     */
    public function exceptionCounter($commandName, $apiUrl)
    {
        try {
            $data = ExceptionsCounter::where('command_name', $commandName)->firstOrFail();
        } catch (NotFoundHttpException $e) {
            Log::error('Exception counter error: ' . $e->getMessage());
            return;
        }

        if ($data->counter == 10) {
            $data->counter = 0;
            $data->save();

            Mail::send('emails.exceptions-count', array('command_name' => $commandName, 'api_url' => $apiUrl), function ($message) {
                $message->from('user@example.ru', 'Laravel')->subject('Penn Api');
            });
        } else {
            $data->counter += 1;
            $data->save();
        }

        $this->monitoringCount();
    }

    /**
     * The method checks the number of failed connections
     *
     * @return string|void
     */
    public function monitoring() {
        try {
            $data = ExceptionsCounter::where('command_name', 'monitoring')->firstOrFail();
        } catch (NotFoundHttpException $e) {
            Log::error('Exception counter monitoring error: ' . $e->getMessage());
            return;
        }
        return $data->counter >= 10 ? 'NO' : 'YES';
    }

    /**
     * The method increases the number of errors per one
     *
     */
    public function monitoringCount() {
        try {
            $data = ExceptionsCounter::where('command_name', 'monitoring')->firstOrFail();
        } catch (NotFoundHttpException $e) {
            Log::error('Exception counter monitoring error: ' . $e->getMessage());
            return;
        }

        $data->counter += 1;
        $data->save();
    }

    /**
     * The method resets the error
     *
     */
    public function monitoringReset()
    {
        try {
            $data = ExceptionsCounter::where('command_name', 'monitoring')->firstOrFail();
        } catch (NotFoundHttpException $e) {
            Log::error('Exception counter monitoring error: ' . $e->getMessage());
            return;
        }
        $data->counter = 0;
        $data->save();
    }
}
