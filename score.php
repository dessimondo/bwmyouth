<?php
header("Content-Type: application/json; charset=UTF-8");

# Grab some of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];
$user = $_POST['user_name'];
$user_id = $_POST['user_id'];
$response_url = $_POST['response_url'];
$file = "omak.json";

/*
# Check the token and make sure the request is from our team 
if($token != 'Hj6op4TwR5Sh3yL0JaaXaNtd'){ #replace this with the token from your slash command configuration page
  $msg = "The token for the slash command doesn't match. Check your script.";
  die($msg);
  echo $msg;
}
*/

# Post OMAK
$content = file_get_contents($file);
$database = json_decode($content, true);
echo $database;

# Count total users
$totalusers = count($database);

//$reply = '{"text": "Hi '.$user.'>, these are the latest tally","attachments":[';

foreach($usrs as $database) {
    $omak_count = $usrs['omak'];
    echo json_decode($omak_count, true);
    $omakcount = $usrs['merit'];
    $reply .= '{"title": " '.json_decode($usrs, true).' ","color": "#3AA3E3","text": "X OMAK(s) and Y merit(s)!"},';
  
}


//for ($x = 0; $x < $totalusers; $x++) {
//    $reply .= '{"title": " '.$database[$x].' ","color": "#3AA3E3","text": "X OMAK(s) and Y merit(s)!"},';
//} 

//$omak_count = json_encode($database[$user]['omak'], true);
//$merit_count = json_encode($database[$target_name]['merit'], true);
/*
$reply = '{"text": "Hi '.$user.'>, these are the latest tally",
    "attachments":[
        {
            "title": " User ",
            "color": "#3AA3E3",
            "text": "X OMAK(s) and Y merit(s)!"
        },
        {
            "title": " User ",
            "color": "#3AA3E3",
            "text": "X OMAK(s) and Y merit(s)!"
        },
        {
            "fallback": "You are unable to view the message correctly",
            "callback_id": "omak",
            "color": "#3AA3E3",
            "attachment_type": "default",
            "actions": [
                {
                    "name": "thumbsup",
                    "text": ":thumbsup: Like",
                    "type": "button",
                    "value": "thumbsup"
                }
            ]
        }
]}';

//Initiate cURL.
$ch = curl_init($response_url);

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $reply);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 


if (strpos($text, '@') == false) {
    echo ':interrobang: wah, you forgot to @mention who to omak sia!';
} else if (strpos($message, '@') == true) {
    echo ':winliaolor: Win liao lor, @mention must put behind leh!';
} else if ($message == "") {
    echo ':rollsafe: LOL, can\'t OMAK if you never type your message lah!';
} else {
    echo 'OMAK submission is ';
    //Execute the request
    $result = curl_exec($ch);   
}
curl_close ($ch);
*/

?>