<?php
require_once './pdo.php';
// create curl resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "http://www.dwa.gov.za/Hydrology/Unverified/RTData.aspx?Station=H7H006FW&Type=Data");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$raw_source_data = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);

$head_marker = '*<br>';
$tail_marker = '<br></font>';
$delimiter = ';';

$head_pos = strpos($raw_source_data, $head_marker);
if ($head_pos === false) {
    echo 'head marker not found...';
    die;
}

$tail_pos = strpos($raw_source_data, $tail_marker);
if ($tail_pos === false) {
    echo 'tail marker not found...';
    die;
}

$raw_source_data = substr($raw_source_data, $head_pos + strlen($head_marker), $tail_pos - ($head_pos + strlen($head_marker)));

$patterns = array();
$patterns[0] = '/      /';
$patterns[1] = '/    /';
$patterns[1] = '/   /';
$patterns[2] = '/  /';
$patterns[3] = '/ /';
$patterns[4] = '/<br>/';
$replacements = $delimiter;

$raw_source_data = preg_replace($patterns, $replacements, $raw_source_data);

$source_data_array = explode($delimiter, $raw_source_data);

$source_data_array = array_chunk($source_data_array, 4);

function array_unique_multidimensional($input)
{
    $serialized = array_map('serialize', $input);
    $unique = array_unique($serialized);
    return array_intersect_key($input, $unique);
}

$source_data_array = array_unique_multidimensional($source_data_array);
$source_data_array = array_values($source_data_array);

$stmt = $pdo->prepare('INSERT INTO flow_data

        (date, time, height, flow)

        VALUES ( :date, :time, :height, :flow)');

$counter = 0;
$array_length = count($source_data_array);
for ($i = 0; $i < $array_length; $i++) {
    $stmt->execute(array(
            ':date' => $source_data_array[$counter][0],
            ':time' => $source_data_array[$counter][1],
            ':height' => $source_data_array[$counter][2],
            ':flow' => $source_data_array[$counter][3])
    );
    $counter++;
}
