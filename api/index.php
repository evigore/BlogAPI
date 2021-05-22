<?php

function get_query() { // Удаляет все кроме первого ?q=...
	$query = null;
	foreach (explode('&', $_SERVER['QUERY_STRING']) as $i) {
		if (explode('=', $i)[0] == 'q') {
			$query = explode('=', $i)[1];
			break;
		}
	}

	return $query;
}

function parse_raw_http_request(array &$a_data)
{
	$input = file_get_contents('php://input');

	preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
	$boundary = $matches[1];

	$a_blocks = preg_split("/-+$boundary/", $input);
	array_pop($a_blocks);

	foreach ($a_blocks as $id => $block)
	{
		if (empty($block))
			continue;

		if (strpos($block, 'application/octet-stream') !== FALSE)
			preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
		else
			preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);

		$a_data[$matches[1]] = $matches[2];
	}
}

function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
}

function get_bearer_token() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}


function get_request_form($method) {
	switch ($method) {
	case 'GET':  return array();
	case 'POST':
		if (!empty($_FILES))
			return $_FILES;
	case 'PATCH':
	case 'DELETE':
		return json_decode(file_get_contents('php://input'), true);
	default:
		return null;
	}
}

$method = $_SERVER['REQUEST_METHOD'];
$form_data = get_request_form($method);

$query = get_query();
$route = explode('/', $query)[0];
$url_data = array_slice(explode('/', $query), 1);

$token = get_bearer_token();
echo 'Token: ' . $token . '<br>';

$filename = 'Routes/' . $route . '.php';
if (file_exists($filename)) {
	include_once($filename);
	route($method, $url_data, $form_data);
} else {
	include_once('codes.php');
	not_found_error();
}
?>
