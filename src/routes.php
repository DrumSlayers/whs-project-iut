<?php
// Routes

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get a simple message
$app->get('/API', function(Request $request, Response $response) {
    echo "Welcome to whs API";
});

// API Assertion
$app->get('/API/assertions', function(Request $request, Response $response, $args) {

    // prepare the search query.
    $search=$request->getQueryParam('search');
    if (strlen($search) == 0)
      $search = '.';
    // pdo
    $settings = $this->get('settings')['db'];
    $db = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'] .";charset=utf8", $settings['user'], $settings['password']);

    // SQL query
    $search = str_replace(" ", "|", $search);
    $query = $db->prepare("SELECT * FROM api_assertion WHERE sentence REGEXP :search");
    $query->bindParam('search', $search);
    $query->execute();
    $result = $query->fetchAll();
    $assertions = json_encode($result);

    // define the response content header to json MIME type
    $response = $response->withHeader('Content-type', 'application/json');
    // initialise $body with a raw body from the response
    $body = $response->getBody();
    // write $body with the encoded json
    $body->write($assertions);
    
    return $response;
  }
);


// Get an html view of assertions
$app->get('/assertions', function (Request $request, Response $response, $args) {

    // prepare the search query with arg ?search from the request and prepare the search query for cURL.
    $search=$request->getQueryParam('search');
    
    if (strlen($search) == 0)
      $search = '';
    else
      $search='?search=' . $search;

    // Initialise cURL for API calls
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => 'http://localhost:8081/API/assertions' . $search
    ));

    // set $assertions with JSON data from curl
    $assertions = curl_exec($curl);
    curl_close($curl);

    // prepare data for the template
    $args['assertions'] = json_decode($assertions);
    return $this->renderer->render($response, 'assertions.phtml', $args);
});

