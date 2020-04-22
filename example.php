<?php
define("BASE_URL", "https://api.collections.yandex.net/v1/");

function api_request($resource, $method, $args=null) {
  $full_url = BASE_URL . "$resource";
  $options = array(
      CURLOPT_URL => $full_url,
      CURLOPT_HTTPHEADER => array(
          'Host: api.collections.yandex.net',
          'Authorization: OAuth <token>',
          'Content-Type: application/json; charset=utf-8',
          'Accept: application/json',
      ),
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
  );
  if ($args) {
    $json_args = json_encode($args);
    $options[CURLOPT_POSTFIELDS] = $json_args;
  }

  $ch = curl_init();
  curl_setopt_array($ch, $options);

  $content = curl_exec($ch);
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ( $status > 399 ) {
    throw new Exception("Exception $status: $content");
  }
  return json_decode($content);
}

$content =  array("description" => "test", "is_private" => true, "title" => "test");
try {
  $res = api_request("boards/", "POST", $content);
} catch (Exception $e) {
  echo $e->getMessage() . "\n";
}