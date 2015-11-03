<?
$params = json_decode(file_get_contents('php://input'), true );

if($params['data']['fn'] == 'movies'){
  getMovies();
}
if($params['data']['fn'] == 'single_movie'){
  getSingleMovie();
}

if($params['data']['fn'] == 'submit_form'){
  submitForm();
}

if($params['data']['fn'] == 'nasapic'){
  nasaPic();
}
if($params['data']['fn'] == 'pass_submit'){
  passSubmit();
}
function nasaPic(){
  $apikey = 'Yw2XghQ0bP5YaF10rz8bsRCTD7pBBXSOKSyZxhuz';
  $endpoint = 'https://api.nasa.gov/planetary/apod?api_key='.$apikey.'&format=JSON';
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
function submitForm(){
  $params = json_decode(file_get_contents('php://input'), true );
  //print_r($params['data']['password']);
  $errors = array();
  $return = array();
  if(!isset($params['data']['email']) || $params['data']['email'] == '' ){
    $errors['errors'] = "Please enter an email address.";
    print_r(json_encode($errors));
    exit();
  }
  if(isset($params['data']['password'])){
    $password = $params['data']['password'];
    $return['e_pass'] = password_hash($password, PASSWORD_DEFAULT);
  }else{
    $errors['errors'] = "Please enter a password.";
    print_r(json_encode($errors));
    exit();
  }
  $email = $params['data']['email'];
  if ($email != "") {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['errors'] = "Email is not a valid email!";
    }
  } 
  if(isset($params['data']['message'])){
    $message = trim($params['data']['message']);
    $return['message'] = filter_var($message, FILTER_SANITIZE_STRING);
  }
  if(isset($errors['errors'])){
    print_r(json_encode($errors));
  }else{
    print_r(json_encode($return));
  }
}
function passSubmit(){
  $params = json_decode(file_get_contents('php://input'), true );
  $pwhash = $params['data']['pwHash'];
  $password = $params['data']['password'];
  $password_check = password_verify($password, $pwhash);
  if($password_check == true){
    $result['pwcheck'] = true;
    print_r(json_encode($result));
  }else{
    $result['pwcheck'] = 'fail';
    print_r(json_encode($result)); 
  }
}
function getMovies(){
  $params = json_decode(file_get_contents('php://input'), true );
  $theterm = $params['data']['searchterm'];
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
function getSingleMovie(){
  $params = json_decode(file_get_contents('php://input'), true );
  $theterm = $params['data']['film_id'];
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