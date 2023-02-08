<?php
require_once('HttpClient.class.php');


// Assesment STEP 1
$assessmentStep1 = new HttpClient('https://corednacom.corewebdna.com/assessment-endpoint.php', 'OPTIONS');
$assessmentStep1->makeRequest();

echo '<div style="background-color: #EEE;"><h1>Step 1</h1>';
echo '<h3>Response Code</h3>';
echo $assessmentStep1->fetchResponseHTTPCode();

echo '<h3 style="border-top: 1px dotted #808080;">Headers</h3>';
echo '<pre>';print_r($assessmentStep1->fetchResponseHeaders());echo '</pre>';

echo '<h3 style="border-top: 1px dotted #808080;">Response</h3>';
echo '<pre>';print_r($assessmentStep1->fetchResponse());echo '</pre>';

echo '<div>';


// Assesment STEP 2
$authToken = $assessmentStep1->fetchResponse()[0];
$dataParams = [];
$dataParams['url'] =  'https://corednacom.corewebdna.com/assessment-endpoint.php';
$dataParams['method'] = 'POST';
$dataParams['payload'] = [
                            "name" => "Vishal Saini",
                            "email" => "vishal.work08@gmail.com",
                            "url" => "https://github.com/vishalwork08/php-http-client"
                        ];
$dataParams['headers'] = "Content-type: application/json\r\n" . "Authorization: Bearer " . $authToken . "\r\n";

$assessmentStep2 = new HttpClient($dataParams['url'], $dataParams['method'], $dataParams['payload'], $dataParams['headers']);
$assessmentStep2->makeRequest();

echo '<div style="background-color: #DAF7A6; margin-top: 20px;"><h1>Step 2</h1>';

echo '<h3>Request Details</h3>';
echo '<pre>';print_r($dataParams);echo '</pre>';

echo '<h3>Response Code</h3>';
echo $assessmentStep2->fetchResponseHTTPCode();

echo '<h3 style="border-top: 1px dotted #808080;">Headers</h3>';
echo '<pre>';print_r($assessmentStep2->fetchResponseHeaders());echo '</pre>';

echo '<h3 style="border-top: 1px dotted #808080;">Response</h3>';
echo '<pre>';print_r($assessmentStep2->fetchResponse());echo '</pre>';

echo '<div>';
