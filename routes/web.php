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
        // Query to fetch databases, associated users, and encoding details
        $query = "
            SELECT
                SCHEMA_NAME AS database_name,
                DEFAULT_CHARACTER_SET_NAME AS encoding,
                DEFAULT_COLLATION_NAME AS collation,
                GROUP_CONCAT(DISTINCT user.User) AS users
            FROM information_schema.SCHEMATA AS schemata
            LEFT JOIN mysql.db AS db ON schemata.SCHEMA_NAME = db.Db
            LEFT JOIN mysql.user AS user ON db.User = user.User
            GROUP BY schemata.SCHEMA_NAME, schemata.DEFAULT_CHARACTER_SET_NAME, schemata.DEFAULT_COLLATION_NAME
            ORDER BY database_name;
        ";

        // Execute the query
        $results = DB::select($query);

        return response()->json($results);
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


