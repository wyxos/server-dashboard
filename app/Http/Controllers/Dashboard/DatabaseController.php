<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use mysqli;

class DatabaseController extends Controller
{
    public function index(){
        $host = '127.0.0.1';
        $username = config('host.db.username');
        $password = config('host.db.password');

        try {
            $conn = new mysqli($host, $username, $password);
            if ($conn->connect_error) {
                throw new Exception('Connection failed: ' . $conn->connect_error);
            }

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

            $result = $conn->query($query);

            $databases = [];
            while ($row = $result->fetch_assoc()) {
                $databases[] = $row;
            }

            $conn->close();

            return response()->json($databases);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store()
    {
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
    }
}
