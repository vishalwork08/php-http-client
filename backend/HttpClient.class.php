<?php

/**
 * Summary: Class HttpClient is used to return API response(php array), response code(integer) & headers(php array)
 *      based on the parameters received in the constructor
 * 
 * How to make use of the class:
 *      $assessmentStep1 = new HttpClient('https://reqres.in/api/users/', 'GET');
 *      $assessmentStep1->makeRequest(); // makes the HTTP request & sets response in class's properties
 *      $assessmentStep1->fetchResponseHTTPCode(); // fetches response HHTP code as a single value i.e. integer
 *      $assessmentStep1->fetchresponseHeaders(); // fetches response headers as a PHP array
 *      $assessmentStep1->fetchResponse(); // fetches complete response as a PHP array
 */
class HttpClient
{
    public $url, $method, $payload = [], $headers;
    private $responseCode = 200;
    private $response = [];
    private $responseHeaders = [];

    /**
     * Constructor
     *
     * @param string $url
     * @param string $method
     * @param array $payload
     * @param array $headers
     * @return void
     */
    function __construct($url, $method, $payload = [], $headers = '')
    {
        $this->url = $url;
        $this->method = $method;
        $this->payload = $payload;
        $this->headers = $headers;
    }

    /**
     * Validates inputs fetched in the constructor
     *
     * @return void
     */
    public function validateRequest()
    {
        // validate request
        try {
            if (!filter_var($this->url, FILTER_VALIDATE_URL)) // validate URL format
                throw new Exception("Invalid URL");
            if (!in_array($this->method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'CONNECT', 'OPTIONS', 'TRACE']))
                throw new Exception("Invalid Method");
            if (!empty($this->payload)) // !is_array(json_decode($this->payload, true))
            {
                if (!is_array($this->payload))
                    throw new Exception("Payload is not a valid format");
                else
                    $this->payload = json_encode($this->payload);
                // throw new Exception($this->payload);
            }
        } catch (Exception $e) {
            $this->responseCode = 400; // bad i.e. malformed request
            $this->response = array('error' => 'Malformed request. Please check your request again', 'hint' => $e->getMessage());
        }
    }

    /**
     * Makes an HTTP request based on the parameters fetched in the constructor
     *
     * @return void
     */
    public function makeRequest()
    {
        $this->validateRequest();

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
                $this->response = is_array(json_decode($result, true)) ? json_decode($result, 2) :  [$result]; // $this->response = json_decode($result, 2);
                $this->responseHeaders = $http_response_header;
                $this->responseCode = substr($http_response_header[0], 9, 3);
            }
        } catch (Exception $e) {
            $this->responseCode = 500; // unexpected i.e. internal server error
            $this->response = array('error' => 'An unexpected error occured. Please refer to the hint', 'hint' => $e->getMessage());
        }
    }
    /**
     * returns HTTP response code after API call
     *
     * @return int
     */
    public function fetchResponseHTTPCode(): int
    {
        return $this->responseCode;
    }
    /**
     * returns HTTP headers code after API call
     *
     * @return array
     */
    public function fetchresponseHeaders(): array
    {
        return $this->responseHeaders;
    }
    /**
     * returns response code after API call
     *
     * @return array
     */
    public function fetchResponse(): array
    {
        return $this->response;
    }
}
