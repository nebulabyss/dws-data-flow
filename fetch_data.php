<?php
require_once './pdo.php';
require_once './functions.php';
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
$patterns[0] = '/( )+/';
$patterns[1] = '/<br>/';
$replacements = $delimiter;

$raw_source_data = preg_replace($patterns, $replacements, $raw_source_data);

$source_data_array = explode($delimiter, $raw_source_data);

$source_data_array = array_chunk($source_data_array, 4);

$source_data_array = array_unique_multidimensional($source_data_array);

$source_data_array = array_values($source_data_array);

$stmt = $pdo->prepare('SELECT date, TIME_FORMAT(time, \'%H:%i\') time FROM flow_data ORDER BY flow_id DESC LIMIT 1');

$stmt->execute(array());

$last_date_time = $stmt->fetch(PDO::FETCH_ASSOC);

if ($last_date_time === false) insert_into_database($pdo, 0, $source_data_array ); else {
    $date_key = array_search($last_date_time['date'], array_column($source_data_array, 0));

    while ($date_key < count($source_data_array)) {
        if ($source_data_array[$date_key][1] == $last_date_time['time']) {
            $date_key++;
            break;
        }
        $date_key++;
    }
    insert_into_database($pdo, $date_key, $source_data_array);
}
