
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>Help</title>

    <!-- Custom styles for this template -->
    <link href="/css/app.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">

    <div class="row">
        <div class="page-header">
            <h2>Departures - Countdown <small>Returns a list of upcoming departures from the Penn Station</small></h2>
        </div>
        <div class="well well-sm">/api/data-departure?api_key=your_api_key</div>
    </div>

    <div class="row">
        <div class="page-header">
            <h2>Train Time Trip Search</h2>
        </div>
        <div class="well well-sm">/api/data-traintime?api_key=your_api_key&endsta=SFD</div>
        <div class="alert alert-danger" role="alert">
            <h4>Parameter <b>endsta</b> required</h4>
            <p>Options "endsta":</p>
            <p>'LIC', 'FHL', 'LYN' ,'CAV', 'ERY', 'ODE', 'IPK', 'LBH', 'RVC', 'BWN', 'FPT', 'MRK', 'KGN', 'BMR', 'WGH', 'SFD', 'MQA', 'MPK', 'AVL', 'CPG',
                'LHT', 'BTA', 'BSR', 'ATL', 'ISP', 'GRV', 'ODL', 'SVL', 'PD', 'BPT', 'MSY', 'SPK', 'NAV', 'WHN', 'HBY', 'SHN', 'BHN', 'EHN', 'AGT', 'MTK', 'ENY',
                'JAM', 'SSM', 'FLS', 'MHL', 'HPA', 'BDY', 'ADL', 'BSD', 'DGL', 'LNK', 'GNK', 'MHT', 'PDM', 'PWS', 'HOL', 'QVG', 'BRT', 'BRS', 'FPK', 'SMR', 'NBD',
                'GCY', 'CLP', 'HEM', 'NHP', 'MAV', 'MIN', 'EWN', 'ABT', 'RSN', 'GVL', 'GHD', 'SCF', 'GST', 'GCV', 'LVL', 'OBY', 'CPL', 'WBY', 'HVL', 'SYT', 'CSH',
                'HUN', 'GWN', 'NPT', 'KPK', 'STN', 'SJM', 'BK', 'PJN', 'BPG', 'FMD', 'PLN', 'WYD', 'DPK', 'BWD', 'CI', 'RON', 'MFD', 'YPK', 'RHD', 'MAK', 'SHD',
                'GPT', 'SAB', 'LMR', 'LTN', 'ROS', 'VSM', 'WWD', 'MVN', 'WDD', 'LVW', 'HGN', 'WHD', 'GBN', 'HWT', 'WMR', 'CHT', 'LCE', 'IWD', 'FRY'</p>
        </div>

    </div>

    <div class="row">
        <div class="page-header">
            <h2>Train info</h2>
        </div>
        <div class="well well-sm">/api/data-train?api_key=your_api_key&train_id=555&limit=10</div>
        <div class="alert alert-danger" role="alert">
            <h4>Parameters</h4>
            <ol>
                <li>train_id - <b>required</b></li>
                <li>limit - number of the last records.
                    Default - 5</li>
            </ol>
            <p>Example response:
                <pre>[{"sched":"01\/29\/2016 05:11:00","train_id":555,"track":19},{"sched":"01\/28\/2016 03:18:00","train_id":555,"track":17},{"sched":"01\/27\/2016 03:07:00","train_id":555,"track":17},{"sched":"01\/26\/2016 10:16:00","train_id":555,"track":20},{"sched":"01\/25\/2016 09:38:00","train_id":555,"track":19}]</pre></p>
        </div>

    </div>

</div>

</body>
</html>
