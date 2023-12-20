<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Query Form</title>

    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container">

    @if ($errors->any())
    <br> <br>
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div>
        <h2 class="mt-4">MySQL Connection Details</h2>
        <form method="post" action="{{ route('executeQuery') }}" class="mb-4">
            @csrf

            <div class="row">
                <!-- Group 1 Fields - Column 1 -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="db_host" class="form-label">MySQL Host:</label>
                        <input type="text" name="db_host" id="db_host" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="db_database" class="form-label">MySQL Database:</label>
                        <input type="text" name="db_database" id="db_database" class="form-control" required>
                    </div>
                </div>

                <!-- Group 1 Fields - Column 2 -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="db_username" class="form-label">MySQL Username:</label>
                        <input type="text" name="db_username" id="db_username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="db_password" class="form-label">MySQL Password:</label>
                        <input type="password" name="db_password" id="db_password" class="form-control" required>
                    </div>
                </div>
            </div>

            <!-- Group 2 Fields -->
            <h2 class="mt-4">MySQL Query</h2>
            <div class="mb-3">
                <label for="sql_query" class="form-label">Enter MySQL Query:</label>
                <textarea name="sql_query" id="sql_query" class="form-control" rows="4" required placeholder='SELECT balance_date as labels, sum(balance) Sum_balanace, AVG(balance) avg_balance FROM `Daily_Account_Balance`
group by month(balance_date)'></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Execute Query</button>
        </form>
    </div>


    <div>
        @if(session('result'))

        <h2 class="mt-4">MySQL Query Results</h2>
        <p> {{session('query')}} </p>
        <hr>

        <div>
            <canvas id="myChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
        const ctx = document.getElementById('myChart');
        const result = @json(session('result'));

        if (result.labels.length == 0) {
            result = {
                labels: ['No Data'],
                datasets: {
                    'No Data': [0]
                }
            }

        }

        const borderColors = [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)'
        ];

        const labels = result.labels;
        const datasets = [];
        Object.keys(result.datasets).forEach((key, index) => {
            datasets.push({
                label: key,
                data: result.datasets[key],
                borderColor: borderColors[index % borderColors.length],
                backgroundColor: 'rgb(255,255,255)',
            });
        });

        const data = {
            labels: labels,
            datasets: datasets,
        };

        const config = {
            type: 'line',
            data: data,
        };

        new Chart(ctx, config);
        </script>


        @endif
    </div>

</body>

</html>