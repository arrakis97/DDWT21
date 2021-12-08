<?php
/**
 * Controller
 *
 * Database-driven Webtechnology
 * Taught by Stijn Eikelboom
 * Based on code by Reinard van Dalen
 */

/* Require composer autoloader */
require __DIR__ . '/vendor/autoload.php';

/* Include model.php */
include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'ddwt21_week3', 'ddwt21', 'ddwt21');

/* Create Router instance */
$router = new \Bramus\Router\Router();

/* Set credentials for authentication */
$cred = set_cred('ddwt21', 'ddwt21');

/* Check if the credentials are correct */
$router->before('GET|POST|PUT|DELETE', '/api/.*', function () use ($cred) {
    if (!check_cred($cred)) {
        echo 'Authentication required';
        http_response_code(401);
        die();
    }
    echo "Successfully authenticated";
});

// Add routes here
$router->mount('/api', function () use ($router, $db){
    http_type_content();

    /* GET reading for all series */
    $router->get('/series', function () use ($db) {
        $series = get_series($db);
        echo json_encode($series, JSON_PRETTY_PRINT);
    });

    /* GET for reading individual series */
    $router->get('/series/(\d+)', function ($series_id) use ($db) {
        $series_info = get_series_info($db, $series_id);
        echo json_encode($series_info, JSON_PRETTY_PRINT);
    });

    /* DELETE for deleting individual series */
    $router->delete('/series/(\d+)', function ($series_id) use ($db) {
        $feedback = remove_series($db, $series_id);
        echo json_encode($feedback, JSON_PRETTY_PRINT);
    });

    /* POST for creating series */
    $router->post('/series/', function () use ($db) {
        $feedback = add_series($db, $_POST);
        echo json_encode($feedback, JSON_PRETTY_PRINT);
    });

    /* PUT for updating series */
    $router->put('/series/(\d+)', function ($series_id) use ($db) {
        $_PUT = array();
        parse_str(file_get_contents('php://input'), $_PUT);
        $series_info = $_PUT + ["series_id" => $series_id];
        $feedback = update_series($db, $series_info);
        echo json_encode($feedback, JSON_PRETTY_PRINT);
    });
});

/* Give a custom error message for the 404 error */
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo "404 error: page was not found.";
});

/* Run the router */
$router->run();
