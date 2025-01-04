<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Support\Facades\DB;

Route::get('/databases', function () {
    try {
        // Run the command to list databases
        $output = [];
        $exitCode = null;

        $username = env('SERVER_DB_USERNAME');
        $password = env('SERVER_DB_PASSWORD');

        // Execute the MariaDB command
        exec("mysql -u $username -p$password -e 'SHOW DATABASES;' 2>&1", $output, $exitCode);

        // Check if the command was successful
        if ($exitCode !== 0) {
            return response()->json([
                'error' => 'Failed to fetch databases',
                'details' => implode("\n", $output)
            ], 500);
        }

        // Return the list of databases as a JSON response
        return response()->json($output);
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
