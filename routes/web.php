<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Http\Request;

Route::get('/create-database', function (Request $request) {
    $databaseName = $request->input('name'); // Get the database name from the request

    // Validate the input
    if (empty($databaseName)) {
        return response()->json(['error' => 'Database name is required.'], 400);
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $databaseName)) {
        return response()->json(['error' => 'Invalid database name. Only letters, numbers, and underscores are allowed.'], 400);
    }

    try {
        // Construct the shell command
        $username = escapeshellarg(config('host.db.username'));
        $password = escapeshellarg(config('host.db.password'));
        $escapedDatabaseName = escapeshellarg($databaseName);

        $command = "mysql -u $username -p$password -e 'CREATE DATABASE $escapedDatabaseName;' 2>&1";

        // Execute the command
        $output = [];
        $exitCode = null;
        exec($command, $output, $exitCode);

        if ($exitCode !== 0) {
            return response()->json([
                'error' => 'Failed to create database',
                'details' => implode("\n", $output)
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Database '$databaseName' created successfully."
        ]);
    } catch (\Exception $e) {
        // Handle errors
        return response()->json(['error' => $e->getMessage()], 500);
    }
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

