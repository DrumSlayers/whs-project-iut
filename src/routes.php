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

    // Définition du content type de la page
    $response = $response->withHeader('Content-type', 'application/json');
    // copy to body de la response pour initialiser $body
    $body = $response->getBody();
    // écrire $body avec le json encodé
    $body->write($assertions);
    
    return $response;
  }
);


// Get an html view of assertions
$app->get('/assertions', function (Request $request, Response $response, $args) {
    // prepare the search query
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
    //return $assertions;
    // prepare data for the template
    $args['assertions'] = json_decode($assertions);
    return $this->renderer->render($response, 'assertions.phtml', $args);
});

