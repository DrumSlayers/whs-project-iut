<?php
// Routes

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

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
      CURLOPT_URL => $this->get('settings')['API_settings']['url'] . $this->get('settings')['API_settings']['api_assertion'] . $search
    ));

    // set $assertions with JSON data from curl
    $assertions = curl_exec($curl);
    curl_close($curl);

    // prepare data for the template
    $args['assertions'] = json_decode($assertions);
    return $this->renderer->render($response, 'assertions.phtml', $args);
  }
);