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
    date_default_timezone_set('Africa/Johannesburg');
    $file = 'fetch_log.txt';
    // Load file content
    $file_content = file_get_contents($file);
    // Generate new log entry
    $log_insert = date('Y-m-d') . "\t" . date('H:i:s') . "\t" .strval(count($source_data_array) - $counter_value) . " new records fetched.\n";
    // Append new log entry
    $file_content .= $log_insert;
    // Write the contents back to the file
    file_put_contents($file, $file_content);
}

function fetch_chart_data(PDO $pdo) {
    $stmt = $pdo->prepare('SELECT date, MAX(flow) as MaxFlow FROM flow_data GROUP BY date');

    $stmt->execute(array());
    $key_pairs = ($stmt->fetchAll(PDO::FETCH_KEY_PAIR));
    $chart_data = "";
    foreach ($key_pairs as $k => $v) {
        $chart_data .= "['" . $k . "', " . $v . "],";
    }
    $chart_data = substr($chart_data, 0, -1);
    echo $chart_data;
}