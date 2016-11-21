<?php
/**
 * Simple Food API
 * @version 1.0.0
 */

require_once __DIR__ . '/vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // return only the headers and not the content
    // only allow CORS if we're doing a GET - i.e. no saving for now.
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'GET') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
    }
    exit;
}

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host'] = "localhost";
$config['db']['user'] = "root";
$config['db']['pass'] = "";
$config['db']['dbname'] = "swagger";


$app = new \Slim\App(["settings" => $config]);

// Add the middleware
$app->add(function ($request, $response, $next) {
    // add media parser
    $request->registerMediaTypeParser(
        "text/plain",
        function () {
            return json_decode(file_get_contents('php://input'), true);
        }
    );

    return $next($request, $response);
});

$container = $app->getContainer();

$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'],
        $settings['user'], $settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};


/**
 * POST addfood
 * Summary: adds a food
 * Notes: Adds an item to the system
 * Output-Formats: [application/json]
 */
$app->POST('/', function ($request, $response, $args) {
    $input = $request->getParsedBody();
    $sql = "INSERT INTO foods (food_name) VALUES (:food_name)";
    $sth = $this->db->prepare($sql);
    $o = $sth->bindParam("food_name", $input['food_name']);
//    print_r($sth->debugDumpParams());die();

    $sth->execute();
    //$input['food_id'] = $this->db->lastInsertId();
    return $this->response->withJson($input);
});


/**
 * DELETE deletefood
 * Summary: deletes a food
 * Notes: Deletes an item from the system
 * Output-Formats: [application/json]
 */
$app->DELETE('/{id}', function ($request, $response, $args) {

    $sth = $this->db->prepare("DELETE FROM foods WHERE food_id=:food_id");

    $sth->bindParam("food_id", $args['id']);
    $status = $sth->execute()? 200: 404;
    return $this->response->withJson(['1'],$status);

});


/**
 * PUT editfood
 * Summary: edit a food
 * Notes: Edits an item of the system
 * Output-Formats: [application/json]
 */
$app->PUT('/', function ($request, $response, $args) {
    $input = $request->getParsedBody();
    $sql = "UPDATE foods SET food_name=:food_name WHERE food_id=:food_id";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("food_id", $input['food_id']);
    $sth->bindParam("food_name", $input['food_name']);

    $result = $sth->execute();

    return $this->response->withJson($result);
});


/**
 * GET getfood
 * Summary: gets foods
 * Notes: Gets items from the system
 * Output-Formats: [application/json]
 */
$app->GET('/', function ($request, $response, $args) {

    $sth = $this->db->prepare("SELECT * FROM foods");
    $sth->execute();
    $foods = $sth->fetchAll();
    return $this->response->withJson($foods);
});


$app->run();
