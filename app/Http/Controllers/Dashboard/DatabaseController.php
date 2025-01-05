<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Database;
use App\Models\DatabaseUser;
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

                $database = Database::query()->updateOrCreate(
                    ['name' => $row['database_name']],
                    [
                        'encoding' => $row['encoding'],
                        'collation' => $row['collation'],
                    ]
                );

                // Parse users from the query result
                $users = !empty($row['users']) ? explode(',', $row['users']) : [];

                // Sync users with the pivot table
                $userIds = [];
                foreach ($users as $username) {
                    $user = DatabaseUser::firstOrCreate(['username' => $username]);
                    $userIds[] = $user->id;
                }

                // Sync the pivot table
                $database->users()->sync($userIds);
            }

            $conn->close();

            return response()->json($databases);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store()
    {
        // Validate the request input
        request()->validate([
            'name' => 'required|string|regex:/^[a-zA-Z0-9_]+$/',
            'encoding' => 'nullable|string',
            'collation' => 'nullable|string',
        ]);

        $databaseName = request('name');
        $encoding = request('encoding', 'utf8'); // Default to utf8 if not provided
        $collation = request('collation', 'utf8_general_ci'); // Default to utf8_general_ci if not provided

        try {
            // Database connection details
            $host = '127.0.0.1';
            $username = config('host.db.username');
            $password = config('host.db.password');

            // Establish a connection using mysqli
            $conn = new mysqli($host, $username, $password);

            if ($conn->connect_error) {
                throw new Exception('Connection failed: ' . $conn->connect_error);
            }

            // SQL to create the database with character set and collation
            $sql = "CREATE DATABASE `$databaseName` CHARACTER SET $encoding COLLATE $collation";

            if ($conn->query($sql) === FALSE) {
                throw new Exception('Error creating database: ' . $conn->error);
            }

            // Create the database instance in the model
            $database = Database::create([
                'name' => $databaseName,
                'encoding' => $encoding,
                'collation' => $collation,
            ]);

            $conn->close();

            return response()->json([
                'success' => true,
                'message' => "Database '$databaseName' created successfully.",
                'database' => $database,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $database = Database::query()->findOrFail($id);

        $name = $database->name;

        try {
            // Database connection details
            $host = '127.0.0.1';
            $username = config('host.db.username');
            $password = config('host.db.password');

            // Establish a connection using mysqli
            $conn = new mysqli($host, $username, $password);

            if ($conn->connect_error) {
                throw new Exception('Connection failed: ' . $conn->connect_error);
            }

            // SQL to drop the database
            $sql = "DROP DATABASE `$name`";

            if ($conn->query($sql) === FALSE) {
                throw new Exception('Error dropping database: ' . $conn->error);
            }

            // Delete the database record from the model
            $database = Database::where('name', $name)->first();
            if ($database) {
                $database->users()->detach(); // Detach related users
                $database->delete(); // Delete the database record
            }

            $conn->close();

            return response()->json([
                'success' => true,
                'message' => "Database '$name' deleted successfully.",
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
