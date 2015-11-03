<?
$params = json_decode(file_get_contents('php://input'), true );

function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
    foreach($params as $key=>$value){
        $r[] = "$key=" . rawurlencode($value);
    }
    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}


function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value)
        $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $r .= implode(', ', $values);
    return $r;
}

if($params['fn'] == 'twitter'){
  twitter();
}

if($params['fn'] == 'movies'){
  movies();
}
if($params['fn'] == 'single_movie'){
  single_movie();
}
if($params['fn'] == 'get_review'){
  get_review();
}
if($params['fn'] == 'submit_form'){
  submit_form();
}
if($params['fn'] == 'pass_submit'){
  pass_submit();
}
function twitter(){
  if(isset($params['twitter_name'])){
  $twlink = $params['twitter_name'];
}else{
  $twlink = "LindseySnell";
}
$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$oauth_access_token = "3016137195-cryFJCoax6LEZFTJihrJibgnVYNTw9BzOh4YgcK";
$oauth_access_token_secret = "NqiKWpczD0VXYjCBAxS3AeaMWAEdxHQGAcXVbX7lAuBeL";
$consumer_key = "jujsC5ixoKvMhm7JY3gS8mpZz";
$consumer_secret = "padChlaU0zSxTVG5anKXZz13QXP53B18US9Z8I96JZ2BXiCzI7";
$oauth = array(
  'screen_name' => $twlink,
  'count' => '50',
  'exclude_replies' => 'true',
  'include_rts' => 'false',
  'oauth_consumer_key' => $consumer_key,
  'oauth_nonce' => '1b5e70fbf5e70d0d6cc003f482dcba1e',
  'oauth_signature_method' => 'HMAC-SHA1',
  'oauth_token' => $oauth_access_token,
  'oauth_timestamp' => time(),
  'oauth_version' => '1.0'
);

$base_info = buildBaseString($url, 'GET', $oauth);
$composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
$oauth['oauth_signature'] = $oauth_signature;

// Make Requests
$header = array(buildAuthorizationHeader($oauth), 'Expect:');
$options = array(
  CURLOPT_HTTPHEADER => $header,
  //CURLOPT_POSTFIELDS => $postfields,
  CURLOPT_HEADER => false,
  CURLOPT_URL => $url. '?screen_name='.$twlink.'&count=50&exclude_replies=true&include_rts=false',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => false
);

$feed = curl_init();
curl_setopt_array($feed, $options);
$json = curl_exec($feed);
curl_close($feed);
$twitter_data = json_decode($json);
foreach($twitter_data as $data){

$text = $data->text;
  $date = $data->created_at;
  $date = strtotime($date);
  $format_data['date'][] = date('M d', $date);

  if(isset($data->entities)){
      $img_url = $data->entities;
      if(isset($img_url->media)){
          $img_url = $img_url->media;
          if(isset($img_url[0]->media_url))
            $format_data['img_url'][] = $img_url[0]->media_url;
          //    echo "<img src='".$img_url."'>";
        };
  };
  if(isset($data->entities)){
    $entities = $data->entities;
    
    if(isset($entities->urls)){
      $urls = $entities->urls; 
      foreach($urls as $url){
        $single_url = $url->url;
        $expanded_urls = $url->expanded_url;
        $display_urls = $url->display_url;
        $replacement= '<a href="'.$single_url.'" title="'.$expanded_urls.'">'.$display_urls.'</a>';
        $text = $data->text;
        $format_data['urls'][] = str_replace($single_url, $replacement, $text);
      }
    } 
    if(isset($entities->hashtags)){
        $hashtags = $entities->hashtags;
      foreach($hashtags as $hashtag){
        $hashtag = $hashtag->text;
        $hashtag_search = '#'.$hashtag;
        $link_hash = '<a href="https://twitter.com/hashtag/'.$hashtag.'?/src=hash" title="'.$hashtag_search.'">'.$hashtag_search.'</a>';
        $format_data['hashtags'][] = str_replace($hashtag_search, $link_hash, $text);
      }
    }
    if(isset($entities->user_mentions)){
        $usermentions = $entities->user_mentions;
      foreach($usermentions as $usermention){
        $usermention = $usermention->screen_name;
        $usermention_search = '@'.$usermention;
        $usermention_hash = '<a href="https://twitter.com/'.$usermention.'" title="'.$usermention_search.'">'.$usermention_search.'</a>';
        $format_data['user_mentions'][] = str_replace($usermention_search, $usermention_hash, $text);
      }
    }
  }
  }
  print_r(json_encode($format_data));
}
function movies(){
  $theterm = $params['search_term'];
  //$apikey = 'w96gmegw6ezzwcuf64cgkcyw'; rottentomatoes key
  $apikey = '633723395e17100a2abf106dc9bea686'; //moviedb key
  $q = urlencode($theterm); //url encode query parameters
  // construct the query with apikey and query
  //$endpoint = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey=' . $apikey . '&q=' . $q; rottentomatoes endpoint
  $endpoint = 'https://api.themoviedb.org/3/search/movie?query='.$q.'&api_key='. $apikey;
  // setup curl to make a call to the endpoint
  $session = curl_init($endpoint);
  // indicates that we want the response back
  curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
  // exec curl and get the data back
  $data = curl_exec($session);
  // close curl session once finished retrieveing data
  curl_close($session);
  // the data
  //$movies = $movies['title'];
  print_r($data);
}
function single_movie(){
  $theterm = $params['id'];
  $apikey = '633723395e17100a2abf106dc9bea686'; //moviedb key
  $q = urlencode($theterm); //url encode query parameters
  // construct the query with apikey and query
  $endpoint = 'https://api.themoviedb.org/3/movie/'.$q.'/images?api_key='. $apikey;
  // setup curl to make a call to the endpoint
  $session = curl_init($endpoint);
  // indicates that we want the response back
  curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
  // exec curl and get the data back
  $data = curl_exec($session);
  // close curl session once finished retrieveing data
  curl_close($session);
  // the data
  //$movies = $movies['title'];
  print_r($data);
}
function get_review(){
  $theterm = $params['search_term'];
  //$apikey = 'w96gmegw6ezzwcuf64cgkcyw'; rottentomatoes key
  $apikey = '633723395e17100a2abf106dc9bea686'; //moviedb key
  $q = urlencode($theterm); //url encode query parameters
  // construct the query with apikey and query
  //$endpoint = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey=' . $apikey . '&q=' . $q; rottentomatoes endpoint
  $endpoint = 'https://api.themoviedb.org/3/movie/'.$q.'/similar?api_key='. $apikey;
  // setup curl to make a call to the endpoint
  $session = curl_init($endpoint);
  // indicates that we want the response back
  curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
  // exec curl and get the data back
  $data = curl_exec($session);
  // close curl session once finished retrieveing data
  curl_close($session);
  // the data
  //$movies = $movies['title'];
  print_r($data);
}
function submit_form(){
  $errors = array();
  $return = array();
  print_r($params);
  if(!isset($params['form_data']['email']) || $params['form_data']['email'] == '' ){
    $errors['errors'] = "Please enter an email address.";
    print_r(json_encode($errors));
    exit();
  }
  if(isset($params['form_data']['password'])){
    $password = $params['form_data']['password'];
    $return['e_pass'] = password_hash($password, PASSWORD_DEFAULT);
  }else{
    $errors['errors'] = "Please enter a password.";
    print_r(json_encode($errors));
    exit();
  }
  //print_r($params['form_data']['email']);
  $email = $params['form_data']['email'];
  if ($email != "") {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['errors'] = "Email is not a valid email!";
    }
  }
  if(isset($errors['errors'])){
    print_r(json_encode($errors));
  }else{
    print_r(json_encode($return));
  }
}  
function pass_submit(){
  $pwhash = $params['form_data']['pwHash'];
  $password = $params['form_data']['password'];
  $password_check = password_verify($password, $pwhash);
  if($password_check == true){
    $result['pwcheck'] = true;
    print_r(json_encode($result));
  }else{
    $result['pwcheck'] = 'fail';
    print_r(json_encode($result)); 
  }
} 
/*
//set errors variable
$errors = '';
//set server variables
$ip = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];
$server_data = $ip.', '.$browser;
//sanitize post variables

    if ($params['email'] != "") {
        $email = filter_var($params['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors .= "<p class='text-danger'>Email is not a valid email!!!</p>.";
        }
    } else{ 
            $errors .= "<p class='text-danger'>Please enter an email address.</p>";
    }
    if ($params['message'] != ""){
        $message = filter_var($params['message'], FILTER_SANITIZE_STRING);
    }else{
            $errors .= "<p class='text-danger'>Write a message!</p>";
        } 
    //prepare checkbox data    
    if (isset($params['check'])){
        $check = implode(', ',$params['check']);
    }
    // if form passes validation, submit data
    if($errors == ''){
      setcookie('email', $email, time()+3600);
      setcookie('message', $message, time()+3600);
      setcookie('checks', $check, time()+3600);
      setcookie('server', $server_data, time()+3600);
    } 

if(isset($_POST['delete'])){
  //  $stmt = $db->prepare("DELETE FROM `test_table` WHERE 1");
  //  $stmt->execute();
}
if(isset($_COOKIE['email'])){
    $cookie['email'] = $_COOKIE['email'];
    $cookie['message'] = $_COOKIE['message'];
    $cookie['checks'] = $_COOKIE['checks'];
    $cookie['server'] = $_COOKIE['server'];
}
*/
//}
?>
