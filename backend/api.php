<?php

require_once('HttpClient.class.php');

try {
  $dataParams = file_get_contents('php://input');
  $dataArray = json_decode($dataParams, true);

  // echo json_encode($dataArray);die;

  if (!empty($dataArray['requestPayload']) && !is_array(json_decode($dataArray['requestPayload'], true)))
  {
    throw new Exception("Payload is not a valid format");return;
  }
  else { // convert to array to pass as parameter
    $dataArray['requestPayload'] = (array)json_decode($dataArray['payload'], true);
  }
    

  $request = new HttpClient($dataArray['url'], $dataArray['method'], $dataArray['requestPayload'], $dataArray['requestHeader']);
  $request->makeRequest();

  $responseHTTPCode = $request->fetchResponseHTTPCode();
  $responseHeaders = $request->fetchResponseHeaders();
  $response = $request->fetchResponse();

  foreach ($responseHeaders as $eachHeader) {
    header($eachHeader);
  }
  http_response_code($responseHTTPCode);
  echo json_encode($response);
} catch (Exception $e) {
  http_response_code(500); // unexpected error
  $errorArray = ['message' => 'An unexpected error has occured. Please check your request again or refer to the hint', 'hint' => $e->getMessage()];
  echo json_encode($errorArray);
}
