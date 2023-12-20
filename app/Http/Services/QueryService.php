<?php

namespace App\Http\Services;

use Config;
use Illuminate\Support\Facades\DB;
use Exception;

class QueryService
{
    /**
     * Test the database connection for validity
     */
    public function testConnection($host, $database, $username, $password)
    {
        try {

            // Set the database configuration dynamically
            $config = [
                'driver' => 'mysql',
                'host' => $host,
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
            ];
            
            DB::purge('mysql');
            Config::set('database.connections.mysql', $config);
            DB::reconnect('mysql');

            // Test Database connection
            DB::Select('select 1');

            return true; // Connection successful
        } catch (Exception $exception) {
            return false; // Connection failed
        }
    }

    /**
     * Execute the query
     */
    public function query($query)
    {
        try {
            // Test Database connection
            $result = DB::Select($query);
            return $this->transform($result); 

        } catch (Exception $exception) {
            throw $exception; 
        }
    }

    /**
     * Transform the result set into a format that can be used by the chart
     */
    private function transform($dataset)
    {
        $result = [
            'labels' => [],
            'datasets' => [],
        ];

        if(count($dataset) == 0) {
            return $result;
        }

        $datasetKeys = get_object_vars($dataset[0]);
        unset($datasetKeys['labels']);
        $datasetKeys = array_keys($datasetKeys);

        foreach ($dataset as $row) {
            array_push($result['labels'], $row->labels);

            foreach($datasetKeys as $key) {
                if(!array_key_exists($key, $result['datasets'])) {
                    $result['datasets'][$key] = [];
                }

                $val = $row->{$key};
                if(is_numeric($val)) {
                    $val = floatval($val);
                }
                array_push($result['datasets'][$key], $val);
            }
        }

        return $result;
    }
}