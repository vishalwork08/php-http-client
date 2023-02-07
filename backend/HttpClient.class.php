<?php
class HttpClient
{
    // properties of class
    public $url, $method, $payload, $headers;
    private $responseCode = 200;
    private $response = [];
    private $responseHeader = [];

    // methods of class
    function __construct($url, $method, $payload, $headers)
    {
        $this->url = $url;
        $this->method = $method;
        $this->payload = $payload;
        $this->headers = $headers;
    }
    public function makeRequest()
    {
        // validate request
        try {
            if (!filter_var($this->url, FILTER_VALIDATE_URL)) // validate URL format
                throw new Exception("Invalid URL");
            if (!in_array($this->method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'CONNECT', 'OPTIONS', 'TRACE']))
                throw new Exception("Invalid Method");
            if (!empty($this->payload) && !is_array(json_decode($this->payload, true))) // payload must be JSON if it's sent
                throw new Exception("Payload, if passed, must be a valid JSON");
        } catch (Exception $e) {
            $this->responseCode = 400; // bad i.e. malformed request
            $this->response = array('error' => 'Malformed request. Please check your request again', 'hint' => $e->getMessage());
        }

        try {
            // proceed only when validation went positive
            if ($this->responseCode != 400) {
                $contextOptions = array(
                    'http' =>
                    array(
                        'method'  => $this->method,
                        'content' => $this->payload,
                        'header'  => $this->headers
                    )
                );
                $context  = stream_context_create($contextOptions);
                $result = file_get_contents($this->url, 0, $context);
                $this->response = json_decode($result, 2);
                $this->responseHeader = $http_response_header;
                $this->responseCode = substr($http_response_header[0], 9, 3);
            }
        } catch (Exception $e) {
            $this->responseCode = 500; // unexpected i.e. internal server error
            $this->response = array('error' => 'An unexpected error occured. Please refer to the hint', 'hint' => $e->getMessage());
        }
    }
    public function fetchResponseHTTPCode()
    {
        return $this->responseCode;
    }
    public function fetchResponseHeader()
    {
        return $this->responseHeader;
    }
    public function fetchResponse()
    {
        return $this->response;
    }
}
