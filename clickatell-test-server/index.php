<?php
$url = parse_url($_SERVER['REQUEST_URI']);
$log = fopen('http.log', 'a');
fwrite($log, $url['path']."?".http_build_query($_REQUEST)."\n");
fclose($log);

switch ($url['path']) {
  case '/http/auth':
    auth();
    break;
  case '/http/sendmsg':
    sendmsg();
    break;
}

function auth(){
  echo 'OK:'. rand();
  exit;
}

function sendmsg(){
  $random=rand(1,100); //randomly simulate a failure one in a 100 times
  if($random!=1){
    $response = 'ID:'. rand();
  }else{
    $response = 'ERR: 114, Cannot route message';
  }
  echo $response;
  $log = fopen('message.log', 'a');
  fwrite($log, preg_replace( '/\s+/', ' ', "{$_REQUEST['to']}:{$_REQUEST['text']}")."[{$response}]\n");
  fclose($log);
  exit;
}

echo "I am the clickatell test server and you asked for this URI: {$_SERVER['REQUEST_URI']}";
