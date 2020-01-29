<?php

function array_unique_multidimensional($input)
{
    $serialized = array_map('serialize', $input);
    $unique = array_unique($serialized);
    return array_intersect_key($input, $unique);
}

function insert_into_database(PDO $pdo, $counter_value, $source_data_array) {
    $stmt = $pdo->prepare('INSERT INTO flow_data (date, time, stage, flow) VALUES ( :date, :time, :stage, :flow)');

    $counter = $counter_value;

    while ($counter < count($source_data_array)) {
        $stmt->execute(array(
                ':date' => $source_data_array[$counter][0],
                ':time' => $source_data_array[$counter][1],
                ':stage' => $source_data_array[$counter][2],
                ':flow' => $source_data_array[$counter][3])
        );
        $counter++;
    }
    $file = 'fetch_log.txt';
    // Open the file to get existing content
    $current = file_get_contents($file);
    // Append a new person to the file
    $current .= date('Y-m-d') . "\t" . date('H:i:s') . "\t" .strval(count($source_data_array) - $counter_value) . " new records fetched.\n";
    // Write the contents back to the file
    file_put_contents($file, $current);
}