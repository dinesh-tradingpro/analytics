<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function __construct()
    {
        // Common initialization for all controllers can go here
    }

    public function callApiEndpoint($endpoint, $params = [], $method = 'GET')
    {
        // Increase PHP execution time limit for API calls
        set_time_limit(300); // 5 minutes

        // Increase memory limit to handle large API responses
        ini_set('memory_limit', '512M'); // 512MB

        $apiKey = config('app.CRM_API_KEY');
        $apiUrl = config('app.CRM_API_URL');

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 300, // 5 minutes
                'verify' => true,
            ]);

            $options = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$apiKey,
                ],
            ];

            // Add segment configuration to parameters
            $params['segment'] = [
                'limit' => 30000, // Reduced from 10000 to prevent memory issues
            ];

            // Add parameters based on HTTP method
            if (strtoupper($method) === 'GET') {
                $options['query'] = $params;
            } else {
                $options['json'] = $params;
            }

            $response = $client->request($method, $apiUrl.'/'.ltrim($endpoint, '/'), $options);

            return [
                'success' => true,
                'status_code' => $response->getStatusCode(),
                'data' => json_decode($response->getBody()->getContents(), true),
            ];

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // 4xx errors
            return [
                'success' => false,
                'status_code' => $e->getResponse()->getStatusCode(),
                'error' => 'Client error: '.$e->getMessage(),
                'data' => null,
            ];

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // 5xx errors
            return [
                'success' => false,
                'status_code' => $e->getResponse()->getStatusCode(),
                'error' => 'Server error: '.$e->getMessage(),
                'data' => null,
            ];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Connection errors, timeouts, etc.
            return [
                'success' => false,
                'status_code' => 0,
                'error' => 'Request error: '.$e->getMessage(),
                'data' => null,
            ];

        } catch (\Exception $e) {
            // Any other errors
            return [
                'success' => false,
                'status_code' => 0,
                'error' => 'Unexpected error: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Call API endpoint with pagination to retrieve all data
     *
     * @param  string  $endpoint
     * @param  array  $params
     * @param  string  $method
     * @param  int  $batchSize
     * @return array
     */
    public function callApiEndpointWithPagination($endpoint, $params = [], $method = 'GET', $batchSize = 5000)
    {
        $allData = [];
        $offset = 0;
        $hasMoreData = true;
        $totalFetched = 0;

        logger("Starting paginated API call to {$endpoint} with batch size {$batchSize}");

        while ($hasMoreData) {
            // Clone params and add pagination
            $paginatedParams = $params;
            $paginatedParams['segment'] = [
                'limit' => $batchSize,
                'offset' => $offset,
            ];

            logger("Fetching batch with offset {$offset}");

            $response = $this->callApiEndpointSingle($endpoint, $paginatedParams, $method);

            if (! $response['success']) {
                logger("API call failed at offset {$offset}: ".$response['error']);

                return [
                    'success' => false,
                    'error' => $response['error'],
                    'data' => $allData, // Return what we have so far
                    'total_fetched' => $totalFetched,
                ];
            }

            $batchData = $response['data'] ?? [];
            $batchCount = count($batchData);

            logger("Received {$batchCount} records in this batch");

            if ($batchCount === 0) {
                // No more data
                $hasMoreData = false;
            } else {
                // Add this batch to our collection
                $allData = array_merge($allData, $batchData);
                $totalFetched += $batchCount;

                // If we got less than the batch size, we've reached the end
                if ($batchCount < $batchSize) {
                    $hasMoreData = false;
                } else {
                    // Move to next batch
                    $offset += $batchSize;
                }
            }
        }

        logger("Completed paginated fetch. Total records: {$totalFetched}");

        return [
            'success' => true,
            'data' => $allData,
            'total_fetched' => $totalFetched,
        ];
    }

    /**
     * Single API call without automatic segment configuration
     * Used internally by callApiEndpointWithPagination
     */
    protected function callApiEndpointSingle($endpoint, $params = [], $method = 'GET')
    {
        // Increase PHP execution time limit for API calls
        set_time_limit(300); // 5 minutes

        // Increase memory limit to handle large API responses
        ini_set('memory_limit', '1G'); // Increased to 1GB for large datasets

        $apiKey = config('app.CRM_API_KEY');
        $apiUrl = config('app.CRM_API_URL');

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 300, // 5 minutes
                'verify' => true,
            ]);

            $options = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$apiKey,
                ],
            ];

            // Add parameters based on HTTP method
            if (strtoupper($method) === 'GET') {
                $options['query'] = $params;
            } else {
                $options['json'] = $params;
            }

            $response = $client->request($method, $apiUrl.'/'.ltrim($endpoint, '/'), $options);

            return [
                'success' => true,
                'status_code' => $response->getStatusCode(),
                'data' => json_decode($response->getBody()->getContents(), true),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'status_code' => 0,
                'error' => 'API call error: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }
}
