<?php

namespace App\Console\Commands;

use App\Http\Controllers\ExceptionCounterController;
use App\TrainTime;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;

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
        $destinations = array(
            'LIC', 'FHL', 'LYN' ,'CAV', 'ERY', 'ODE', 'IPK', 'LBH', 'RVC', 'BWN', 'FPT', 'MRK', 'KGN', 'BMR', 'WGH', 'SFD', 'MQA', 'MPK', 'AVL', 'CPG',
            'LHT', 'BTA', 'BSR', 'ATL', 'ISP', 'GRV', 'ODL', 'SVL', 'PD', 'BPT', 'MSY', 'SPK', 'NAV', 'WHN', 'HBY', 'SHN', 'BHN', 'EHN', 'AGT', 'MTK', 'ENY',
            'JAM', 'SSM', 'FLS', 'MHL', 'HPA', 'BDY', 'ADL', 'BSD', 'DGL', 'LNK', 'GNK', 'MHT', 'PDM', 'PWS', 'HOL', 'QVG', 'BRT', 'BRS', 'FPK', 'SMR', 'NBD',
            'GCY', 'CLP', 'HEM', 'NHP', 'MAV', 'MIN', 'EWN', 'ABT', 'RSN', 'GVL', 'GHD', 'SCF', 'GST', 'GCV', 'LVL', 'OBY', 'CPL', 'WBY', 'HVL', 'SYT', 'CSH',
            'HUN', 'GWN', 'NPT', 'KPK', 'STN', 'SJM', 'BK', 'PJN', 'BPG', 'FMD', 'PLN', 'WYD', 'DPK', 'BWD', 'CI', 'RON', 'MFD', 'YPK', 'RHD', 'NYK', 'MAK', 'SHD',
            'GPT', 'SAB', 'LMR', 'LTN', 'ROS', 'VSM', 'WWD', 'MVN', 'WDD', 'LVW', 'HGN', 'WHD', 'GBN', 'HWT', 'WMR', 'CHT', 'LCE', 'IWD', 'FRY'
        );


        $client = new Client(['base_uri' => 'https://traintime.lirr.org/']);

        foreach ($destinations as $key => $value) {

            try {
                $request = $client->request('GET', 'api/TrainTime',
                    ['query' => ['api_key' => $api_key, 'startsta' => 'NYK', 'endsta' => $value]])->getBody();
            } catch (ServerException $e) {
                Log::error('Guzzle error: ' . $e->getMessage());
                $count->exceptionCounter('train_time:start', 'TrainTime');
                break;
            } catch (ConnectException $e) {
                Log::error('Guzzle error: ' . $e->getMessage());
                $count->exceptionCounter('train_time:start', 'TrainTime');
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
                $count->exceptionCounter('train_time:start', 'TrainTime');
            }
        }
    }
}
