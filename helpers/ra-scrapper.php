<?php
// File path to the JSON file
$jsonFilePath = '../events.json';

// URL and headers for the request
$url = 'https://ra.co/graphql';
$headers = [
    'Content-Type: application/json',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:130.0) Gecko/20100101 Firefox/130.0',
    'Referer' => 'https://ra.co/clubs/193011',
    'Origin' => 'https://ra.co',
];

// Request body (GraphQL query and variables)
$data = [
    'operationName' => 'GET_DEFAULT_EVENTS_LISTING',
    'variables' => [
        'indices' => ['EVENT'],
        'pageSize' => 100,
        'page' => 1,
        'aggregations' => [],
        'filters' => [
            ['type' => 'CLUB', 'value' => '193011'],
            ['type' => 'DATERANGE', 'value' => '{"gte":"2023-09-08T04:10:00.000Z"}']
        ],
        'sortOrder' => 'ASCENDING',
        'sortField' => 'DATE',
    ],
    'query' => '
    query GET_DEFAULT_EVENTS_LISTING(
        $indices: [IndexType!],
        $aggregations: [ListingAggregationType!],
        $filters: [FilterInput],
        $pageSize: Int,
        $page: Int,
        $sortField: FilterSortFieldType,
        $sortOrder: FilterSortOrderType
    ) {
        listing(
            indices: $indices
            aggregations: $aggregations
            filters: $filters
            pageSize: $pageSize
            page: $page
            sortField: $sortField
            sortOrder: $sortOrder
        ) {
            data {
                ... on Event {
                    id
                    title
                    artists {
                        name
                    }
                    date
                    images {
                        filename
                    }
                    pick {
                        blurb
                    }
                }
            }
            totalResults
        }
    }
    '
];

// Initialize cURL session
$ch = curl_init($url);

// Set options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute cURL request
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
    exit;
}

// Decode the response
$events = json_decode($response, true);
// Pretty var_dump for debugging
// echo '<pre>';
// var_dump($events);
// echo '</pre>';

// Load existing events from events.json file if it exists
$existingEvents = [];
if (file_exists($jsonFilePath)) {
    $jsonData = file_get_contents($jsonFilePath);
    $existingEvents = json_decode($jsonData, true);
    if (!$existingEvents) {
        $existingEvents = [];
    }
}

// Helper function to check if the event already exists
function eventExists($newEvent, $existingEvents) {
    foreach ($existingEvents as $existingEvent) {
        if ($existingEvent['title'] === $newEvent['title'] && $existingEvent['date'] === $newEvent['date']) {
            return true; // Event already exists
        }
    }
    return false;
}

// Prepare new events
$newEvents = [];
if (isset($events['data']['listing']['data']) && is_array($events['data']['listing']['data'])) {
    foreach ($events['data']['listing']['data'] as $event) {
        $newEvent = [
            'title' => $event['title'],
            'artists' => array_column($event['artists'], 'name'),
            'date' => $event['date'],
            'image' => isset($event['images'][0]['filename']) ? $event['images'][0]['filename'] : 'N/A',
            'url' => 'https://ra.co/events/' . $event['id'],
            'description' => isset($event['pick']['blurb']) ? $event['pick']['blurb'] : ''
        ];

        // Add only unique events to the list
        if (!eventExists($newEvent, $existingEvents)) {
            $newEvents[] = $newEvent;
        }
    }
}

// Merge new events with existing events
$updatedEvents = array_merge($existingEvents, $newEvents);

// Save the updated events back to events.json
file_put_contents($jsonFilePath, json_encode($updatedEvents, JSON_PRETTY_PRINT));

echo "Events have been updated successfully.";
curl_close($ch);
?>
