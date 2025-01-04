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

        // Get the credentials for MariaDB
        $username = escapeshellarg(config('host.db.username'));
        $password = escapeshellarg(config('host.db.password'));

        // SQL query to fetch database details
        $query = "
            SELECT
                SCHEMA_NAME AS database_name,
                DEFAULT_CHARACTER_SET_NAME AS encoding,
                DEFAULT_COLLATION_NAME AS collation,
                (SELECT GROUP_CONCAT(DISTINCT User)
                 FROM mysql.db
                 WHERE mysql.db.Db = SCHEMA_NAME) AS users
            FROM information_schema.SCHEMATA
            WHERE SCHEMA_NAME NOT IN ('information_schema', 'mysql', 'performance_schema', 'sys')
            ORDER BY SCHEMA_NAME;
        ";

        // Command to execute the query
        $command = "mysql -u $username -p$password -e \"$query\" 2>&1";

        // Run the command
        exec($command, $output, $exitCode);

        // Check for errors
        if ($exitCode !== 0) {
            return response()->json([
                'error' => 'Failed to fetch databases and users',
                'details' => implode("\n", $output),
            ], 500);
        }

        // Parse the output
        $parsedOutput = [];
        $headers = null;

        foreach ($output as $line) {
            $columns = preg_split('/\s+/', trim($line));

            if (!$headers) {
                // First line contains headers
                $headers = $columns;
                continue;
            }

            // Map values to headers
            $parsedOutput[] = array_combine($headers, $columns);
        }

        return response()->json($parsedOutput);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


