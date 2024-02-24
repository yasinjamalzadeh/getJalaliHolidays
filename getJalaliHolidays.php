<?php
/**
 * getJalaliHolidays function written in php
 * created by Yasin Jamalzadeh
 *      02/24/2024
 * 
 * 
 *      Version: 1.0
 *      
 * 
 * 
 *   */


//
$result_array['status'] = false;
$result_array['holidays'] = array();

if (!isset($_REQUEST['year'])) {
    $result_array['status'] = false;
    $result_array['msg'] = 'The year value is not entered.';
    echo json_encode($result_array);
    die;
} elseif (!isset($_REQUEST['month'])) {
    $result_array['status'] = false;
    $result_array['msg'] = 'The month value is not entered.';
    echo json_encode($result_array);
    die;
}

$url = 'https://www.time.ir/'; //Collecting data from time.ir website

$data = array(
    'Year' => $_REQUEST['year'],
    'Month' => $_REQUEST['month'],
    'Base1' => '0',
    'Base2' => '1',
    'Base3' => '2',
    'Responsive' => 'true'
);

$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === false) {
    // Handle error
    $result_array['status'] = false;
    $result_array['msg'] = 'Handle error.';
    echo json_encode($result_array);
    die;
} else {
    $result_array['status'] = true;
    //
    $pattern = '/>\s+?</';
    $replacement = '><';
    $response = preg_replace($pattern, $replacement, $response);
    //
    $pattern = '/<li class=\'eventHoliday \'><span[^>]+id="[^"]+">(.*?)<\/span>/s';
    preg_match_all($pattern, $response, $matches);

    if (!empty($matches[1])) {
        $firstSpanValues = $matches[1];
        $count = count($firstSpanValues);

        for ($i = 0; $i < $count; $i++) {

            // Remove non-numeric characters from Persian number
            $firstSpanNumber = strtok($firstSpanValues[$i], ' ');

            array_push($result_array['holidays'], $firstSpanNumber);
        }
    }
    echo json_encode($result_array);
    die;
}
?>