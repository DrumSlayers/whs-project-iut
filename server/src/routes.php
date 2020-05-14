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

    // SQL query with eloquent ORM
    $result = App\Assertion::where('sentence', 'like', '%' . $search . '%')->get();

    // encoding SQL query response to JSON
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

// API GET Assertion with ID (Get Assertion)
$app->get('/API/assertions/[{id}]', function(Request $request, Response $response, $args) {

  // SQL query with eloquent ORM
  $result = App\Assertion::find($args['id']);

  // encoding SQL query response to JSON
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

// API POST Assertion with ID (Insert Assertion)
$app->post('/API/assertions', function(Request $request, Response $response, $args) {

  // insert body of the request into $body
  $body = $request->getBody();

  // decode request body's json into the $decoded_body array
  $decoded_json = json_decode($body, true);

  // prepare the ORM sql request
  $assertion = new App\Assertion;

  // Checking if data are set to avoid putting null data into the database
  if(isset($decoded_json['topic'])){
    $assertion -> topic = $decoded_json['topic']; // adding topic data to the topic row in DB 
  }
  if(isset($decoded_json['sentence'])){
    $assertion -> sentence = $decoded_json['sentence']; // adding sentence data to the sentence row in DB 
  }
  if(isset($assertion)){
    $assertion -> save(); // saving the datas into the database
  }
  
  $result_json = json_encode($assertion); // Feedback of the HTTP PUT request by re-encoding to JSON the $assertion object (checking errors)
  

  // define the response content header to json MIME type
  $response = $response->withHeader('Content-type', 'application/json');
  // initialise $body with a raw body from the response
  $body = $response->getBody();
  // write $body with the encoded json
  $body->write($result_json);

  return $response;
}
);

// API PUT Assertion with ID (Modify Assertion)
$app->put('/API/assertions/[{id}]', function(Request $request, Response $response, $args) {

  // insert body of the request into $body
  $body = $request->getBody();

  // decode request body's json into the $decoded_body array
  $decoded_json = json_decode($body, true);

  // prepare the ORM sql request
  $assertion = App\Assertion::find($args['id']);

  // Checking if data are set to avoid putting null data into the database
  if(isset($decoded_json['topic'])){
    $assertion -> topic = $decoded_json['topic']; // adding topic data to the topic row in DB 
  }
  if(isset($decoded_json['sentence'])){
    $assertion -> sentence = $decoded_json['sentence']; // adding sentence data to the sentence row in DB 
  }
  if(isset($assertion)){
    $assertion -> save(); // saving the data into the database
  }

  $result_json = json_encode($assertion); // Feedback of the HTTP PUT request by re-encoding to JSON the $assertion object (checking errors)
  
  // define the response content header to json MIME type
  $response = $response->withHeader('Content-type', 'application/json');
  // initialise $body with a raw body from the response
  $body = $response->getBody();
  // write $body with the encoded json
  $body->write($result_json);

  return $response;
}
);

// API DELETE Assertion with ID
$app->delete('/API/assertions/[{id}]', function(Request $request, Response $response, $args) {

    // Define the response content header to json MIME type
    $response = $response->withHeader('Content-type', 'application/json');

    // Send the existing line if exist to have a proof of what have been destroyed
    $assertion = App\Assertion::find($args['id']);
    if(isset($assertion)){
      $result_json = json_encode($assertion); // Feedback of the HTTP PUT request by re-encoding to JSON the $assertion object (checking errors)
      // initialise $body with a raw body from the response
      $body = $response->getBody();
      // write $body with the encoded json
      $body->write($result_json);
    }
  
  // Delete the entry with given ID in database
  App\Assertion::destroy($args['id']);

  return $response;
}
);