<?php

namespace App\Helpers;

use Config, DB, Schema;

class DatabaseConnection {

    // Set schema connection for creating database tables
    public static function setSchemaConnection($schema) {
        config(['database.connections.station_schema' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('WAREHOUSE_DATABASE', 'forge'),
            'username' => env('WAREHOUSE_USERNAME', 'forge'),
            'password' => env('WAREHOUSE_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => $schema,
            'sslmode' => 'prefer',
        ]]);

        return Schema::connection('station_schema');
    }

    // Set database connection on the fly
    public static function setDBConnection($schema) {
        config(['database.connections.station_schema' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('WAREHOUSE_DATABASE', 'forge'),
            'username' => env('WAREHOUSE_USERNAME', 'forge'),
            'password' => env('WAREHOUSE_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => $schema,
            'sslmode' => 'prefer',
        ]]);

        return DB::reconnect('station_schema');
    }

}