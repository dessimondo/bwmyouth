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

# Check the token and make sure the request is from our team 
if($token != 'Hj6op4TwR5Sh3yL0JaaXaNtd'){ #replace this with the token from your slash command configuration page
  $msg = "The token for the slash command doesn't match. Check your script.";
  die($msg);
  echo $msg;
}

# Retrieve target name and message from text
if($text == 'myself' || $text == ' myself' || $text == 'myself ' || $text == ''){
    checkDatabase($user, $file, $response_url);
} else if (strpos($text, '@') == false) {
    echo ':interrobang: wah, you forgot to @mention who you want to check sia!';
} else {
    $target = ltrim($text, "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890@?!#$%&()-_+=[]{},.:\ ");
    $target_name = ltrim(rtrim($target, ">"), "<@ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890|");
    //echo 'check 1';
    checkDatabase($target_name, $file, $response_url);
}

function checkDatabase($target_name, $file, $response_url){

    $content = file_get_contents($file);
    $database = json_decode($content, true);
    //echo $content;

    # update user
    if (array_key_exists($target_name,$database)){
        //echo 'check 2';
        $omakcount = (int)$database[$target_name]['omak'];
        $meritcount = (int)$database[$target_name]['merit'];
        reply($omakcount, $meritcount, $target_name, $response_url);
    } else {
        echo ':interrobang: oh oh, I think you type wrongly or the person have not OMAK or gain merits leh...';
    }
}

# Post reply
function reply($omakcount, $meritcount, $target_name, $response_url){

    echo '{"text": ":pray: <@'.$target_name.'> made '.$omakcount.' OMAK and gained '.$meritcount.' merits!", "attachments":[
        {
            "text": " *<http://bit.ly/omakchart|Click here>* to check out your _\"Bank of Buddha\"_ ",
            "mrkdwn_in": ["text", "pretext"],
            "color": "#3AA3E3"
        }]
        }';
    /*
    //Initiate cURL.
    $ch = curl_init($response_url);

    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $reply);

    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

    //Execute the request
    $result = curl_exec($ch);   
    curl_close ($ch);
    */
}

?>