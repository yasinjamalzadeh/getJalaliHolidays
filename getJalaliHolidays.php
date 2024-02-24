<?php
/**
 * getJalaliHolidays function written in php
 * created by Yasin Jamalzadeh
 *      02/24/2024
 * 
 * 
 *      Version: 2.0
 *      
 * 
 * 
 *   */


//
function convertEntitiesToEnglish($input)
{
    $htmlEntities = [
        "&#1776;", // ۰
        "&#1777;", // ۱
        "&#1778;", // ۲
        "&#1779;", // ۳
        "&#1780;", // ۴
        "&#1781;", // ۵
        "&#1782;", // ۶
        "&#1783;", // ۷
        "&#1784;", // ۸
        "&#1785;", // ۹
    ];

    $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    $convertedString = str_replace($htmlEntities, $englishNumbers, $input);

    return $convertedString;
}

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
    $pattern = '/<div class=\' holiday\'><div class=\'jalali\' style=\'\'>(.*?)<\/div>/s';
    preg_match_all($pattern, $response, $matches);

    if (!empty($matches[1])) {
        $gottenValue = $matches[1];
        $count = count($gottenValue);

        for ($i = 0; $i < $count; $i++) {

            $engNum = convertEntitiesToEnglish($gottenValue);

            if (!in_array($engNum, $result_array['holidays'])) {
                $result_array['holidays'][] = $engNum;
            }
        }
    }
    echo json_encode($result_array);
    die;
}
?>