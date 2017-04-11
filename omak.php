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

$message = getMessage($text);
$targets = getTarget($text);


# Retrieve target name and message from text
function getTarget($text){
    # Remove chinese characters
    $processchinese = preg_replace("/\p{Han}+/u", '', $text);

    # Remove message
    $target = ltrim($processchinese, "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890@?!#$%&()-_+=[]{},.:\’' ");
    return $target;
}

function getMessage($text){
    # Remove spacing between tags
    $removespace = preg_replace("/> </", '><', $text);
    return rtrim($removespace, "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890@_<|>");
}

# Change the values in local database
function updateDatabase($user, $target_name, $file){

    $content = file_get_contents($file);
    $database = json_decode($content, true);
    //echo $content;

    # update user
    if (array_key_exists($user,$database)){
        $omakcount = (int)$database[$user]['omak'];
        $omakcount++;
        $database[$user]['omak'] = $omakcount;
        //echo ' '.$user.' OMAK Count is '.$database[$user]['omak'];
    } else {
        $omakcount = 1;
        $meritcount = 0;
        $database[$user] = array("omak" => $omakcount, "merit" => $meritcount);
        //echo 'added user ';
    }

    # update target
    if (array_key_exists($target_name,$database)){
        $meritcount = (int)$database[$target_name]['merit'];
        $meritcount++;
        $database[$target_name]['merit'] = $meritcount;
        //echo ' '.$target_name.' Merit Count is '.$database[$target_name]['merit'];
    } else {
        $omakcount = 0;
        $meritcount = 1;
        $database[$target_name] = array("omak" => $omakcount, "merit" => $meritcount);
        //echo 'added target ';
    }
    
    //echo json_encode($database);

    uploadChanges('public_html/'.$file, json_encode($database));
}

# Connect to FTP to upload changes to server database
function uploadChanges($remote_file, $file_string){
    // FTP login
    $ftp_server="files.000webhost.com"; 
    $ftp_user_name="bwmy-developers"; 
    $ftp_user_pass="des-chel-sea";

    // Create temporary file
    $local_file=fopen('php://temp', 'r+');
    //echo 'no error in creating temp file';
    fwrite($local_file, $file_string);
    rewind($local_file);       

    // Create FTP connection
    $ftp_conn=ftp_connect($ftp_server); 

    // FTP login
    $login_result=ftp_login($ftp_conn, $ftp_user_name, $ftp_user_pass); 

    // FTP upload
    if($login_result) 
    {
        //echo 'login successfully';
        $upload_result=ftp_fput($ftp_conn, $remote_file, $local_file, FTP_ASCII);
    }

    // Error handling
    if(!$login_result or !$upload_result)
    {
        echo 'FTP error: The file could not be written on the remote server.';
    }

    // Close FTP connection
    ftp_close($ftp_conn);

    // Close file handle
    fclose($local_file);
}

# Change the json database
if (strpos($text, '@') == true && strpos($message, '@') == false && $message != "") {
    //echo 'text has mentions, message has no @ and message is not empty';
    $keywords = preg_split("/[\s,]+/", $targets);
    //print_r($keywords);
    foreach ($keywords as &$value) {
        
        $target_name = ltrim(rtrim($value, ">"), "<@ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890|");
        //echo 'update for'.$target_name;
        updateDatabase($user, $target_name, $file);
    }
}

# Post OMAK
$content = file_get_contents($file);
$database = json_decode($content, true);
$omak_count = json_encode($database[$user]['omak'], true);

$reply = '{"response_type": "in_channel","text": ":pray: <@'.$user_id.'> just OMAK '.$targets.'!",
    "attachments":[
        {
            "title": " \" '.$message.'\" ",
            "color": "#3AA3E3"
        },
        {
            "text": "'.$user.' has made '.$omak_count.' OMAK(s) \n';

$names = preg_split("/[\s,]+/", $targets);
foreach ($names as &$value){
    $target_name = ltrim(rtrim($value, ">"), "<@ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890|");
    $merit_count = json_encode($database[$target_name]['merit'], true);
    $reply .= ''.$target_name.' has accumulated '.$merit_count.' merit(s)! \n';           
}

$reply .= ' ",
        "color": "#3ae3dd"
        },
        {
            "text": "*<http://bit.ly/omakchart|Click here>* to check the _\"Bank of Buddha\"_ or submit your own `/omak message @someone`",
            "mrkdwn_in": ["text", "pretext"],
            "color": "#3AA3E3"
        },
        {
            "fallback": "You are unable to view the message correctly",
            "callback_id": "omak",
            "color": "#3ae3dd",
            "attachment_type": "default",
            "actions": [
                {
                    "name": "thumbsup",
                    "text": ":thumbsup: Like",
                    "type": "button",
                    "value": "thumbsup"
                },
                {
                    "name": "rejoice",
                    "text": ":pray: Rejoice",
                    "type": "button",
                    "value": "rejoice"
                },
                {
                    "name": "love",
                    "text": ":heart: Love",
                    "type": "button",
                    "value": "love"
                },
                {
                    "name": "cool",
                    "text": ":sunglasses: Cool",
                    "type": "button",
                    "value": "cool"
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
    echo ':winliaolor: Win liao lor, @mention must put behind leh! Multiple mentions should be at the back too with only space in between';
} else if ($message == "") {
    echo ':rollsafe: LOL, can\'t OMAK if you never type your message lah!';
} else {
    echo 'OMAK submission is ';
    //Execute the request
    $result = curl_exec($ch);   
}
curl_close ($ch);

?>