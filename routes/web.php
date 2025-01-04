<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

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
        $output = [];
        $exitCode = null;

        $username = escapeshellarg(config('host.db.username'));
        $password = escapeshellarg(config('host.db.password'));

        // Execute the MariaDB command to list databases
        $command = "mysql -u $username -p$password -e 'SHOW DATABASES;' 2>&1";
        exec($command, $output, $exitCode);

        // Check if the command was successful
        if ($exitCode !== 0) {
            return response()->json([
                'error' => 'Failed to fetch databases',
                'details' => implode("\n", $output),
            ], 500);
        }

        // Remove the header and format the output
        $databases = array_slice($output, 1);

        return response()->json($databases);
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


