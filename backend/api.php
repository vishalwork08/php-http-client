<?php

require_once('HttpClient.class.php');

$dataParams = file_get_contents('php://input');
$dataArray = json_decode($dataParams, true);

$request = new HttpClient($dataArray['url'], $dataArray['method'], $dataArray['payload'], $dataArray['headers']);
$request->makeRequest();

$responseHTTPCode = $request->fetchResponseHTTPCode();
$responseHeader = $request->fetchResponseHeader();
$response = $request->fetchResponse();


try {
  foreach ($responseHeader as $eachHeader) {
    header($eachHeader);
  }
  http_response_code($responseHTTPCode);
  echo json_encode($response);
} catch (Exception $e) {
  http_response_code(500); // unexpected error
  $errorArray = ['message' => 'An unexpected error has occured. Please check your request again or refer to the hint', 'hint' => $e];
  echo json_encode($errorArray);
}
