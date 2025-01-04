<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/databases', function () {
    try {
        // Run the command to list databases and their associated users
        $output = [];
        $exitCode = null;

        $username = config('host.db.username');
        $password = config('host.db.password');

        // Query to fetch databases and their associated users
        $query = "
            SELECT db.Db AS database_name, user.User AS user_name
            FROM mysql.db AS db
            INNER JOIN mysql.user AS user ON db.User = user.User
            ORDER BY database_name, user_name;
        ";

        // Execute the MariaDB command
        exec("mysql -u $username -p$password -e \"$query\" 2>&1", $output, $exitCode);

        // Check if the command was successful
        if ($exitCode !== 0) {
            return response()->json([
                'error' => 'Failed to fetch databases and users',
                'details' => implode("\n", $output),
            ], 500);
        }

        // Parse the output
        $parsedOutput = [];
        $header = null;

        foreach ($output as $line) {
            $columns = preg_split('/\s+/', $line);
            if (!$header) {
                $header = $columns;
                continue;
            }
            $parsedOutput[] = array_combine($header, $columns);
        }

        // Return the parsed output as JSON
        return response()->json($parsedOutput);
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

