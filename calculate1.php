<?php

    $cityDistances = [
        'A' => [
            'B' => 5, 'C' => 7,
            'D' => PHP_INT_MAX, 'E' => PHP_INT_MAX, 'F' => PHP_INT_MAX
        ],
        'B' => [
            'A' => 5, 'E' => 20,
            'C' => PHP_INT_MAX, 'D' => 15, 'F' => PHP_INT_MAX
        ],
        'C' => [
            'A' => 7, 'D' => 5,
            'B' => PHP_INT_MAX, 'E' => 35, 'F' => PHP_INT_MAX
        ],
        'D' => [
            'A' => PHP_INT_MAX, 'B' => 15,
            'C' => 5, 'E' => PHP_INT_MAX, 'F' => 20
        ],
        'E' => [
            'A' => PHP_INT_MAX, 'B' => 20,
            'C' => 35, 'D' => PHP_INT_MAX, 'F' => 10
        ],
        'F' => [
            'A' => PHP_INT_MAX, 'B' => PHP_INT_MAX,
            'C' => PHP_INT_MAX, 'D' => 20, 'E' => 10
        ],
    ];

    // Cab pricing
    $cabPrices = [
        'Cab1' => 0.5, // Price per minute for Cab1
        'Cab2' => 0.6,
        'Cab3' => 0.7,
        'Cab4' => 0.8,
        'Cab5' => 0.9,
    ];

    // Get user input
    $source = $_POST['source'];
    $destination = $_POST['destination'];

    // Calculate shortest distance
    function shortestDistance($distances, $source, $destination) {
        $visited = [];
        $dist = [];
        $prev = [];
        foreach (array_keys($distances) as $city) {
            $dist[$city] = PHP_INT_MAX;
            $prev[$city] = null;
        }
        $dist[$source] = 0;

        while (count($visited) < count($distances)) {
            $min = null;
            foreach (array_keys($distances) as $city) {
                if (!in_array($city, $visited) && ($min === null || $dist[$city] < $dist[$min])) {
                    $min = $city;
                }
            }

            foreach ($distances[$min] as $neighbor => $distance) {
                $alt = $dist[$min] + $distance;
                if ($alt < $dist[$neighbor]) {
                    $dist[$neighbor] = $alt;
                    $prev[$neighbor] = $min;
                }
            }

            $visited[] = $min;
        }

        $path = [];
        $current = $destination;
        while ($current !== null) {
            $path[] = $current;
            $current = $prev[$current];
        }
        $path = array_reverse($path);

        return [
            'distance' => $dist[$destination],
            'path' => $path,
        ];
    }

    $result = shortestDistance($cityDistances, $source, $destination);

    // Calculate estimated cost
    $chosenCab = 'Cab1'; // Assume Cab1 for now
    $pricePerMinute = $cabPrices[$chosenCab];
    $travelTime = $result['distance']; // in minutes
    $estimatedCost = $pricePerMinute * $travelTime;

    echo '<div style="border: 2px solid #3498db; padding: 15px; border-radius: 10px; max-width: 400px; margin: 20px auto; background-color: #f1f1f1; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 16px;">';
echo '<h2 style="color: #3498db; font-size: 20px;">Result:</h2>';
echo '<p style="font-size: 18px;"><strong>Shortest Distance from ' . $source . ' to ' . $destination . ':</strong> ' . $result['distance'] . ' minutes</p>';
echo '<p style="font-size: 18px;"><strong>Estimated Cost using ' . $chosenCab . ':</strong> $' . number_format($estimatedCost, 2) . '</p>';
echo '<p style="font-size: 18px;"><strong>Route:</strong> ' . implode(" -> ", $result['path']) . '</p>';
echo '</div>';


    
?>