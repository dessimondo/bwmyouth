<?php
header("Content-Type: application/json; charset=UTF-8");

$file = "omak.json";

//var_dump($_POST);

$json = $_POST['payload'];
//echo $json;

$data = json_decode($json, true);
$value = $data['actions'][0]['value'];
$user_name = $data['user']['name'];
$user_id = $data['user']['id'];
//echo '{"response_type": "in_channel","text": "'.$value.'","replace_original": false}]}';

if ($value == 'rejoice'){
    echo '{"response_type": "in_channel","text": "<@'.$user_id.'> says '.$value.'! :pray: (merit +1)","replace_original": false}]}';
    updateDatabase($user_name, $file);
} else if ($value == 'cool'){
    echo '{"response_type": "in_channel","text": "<@'.$user_id.'> thinks this is cool! :sunglasses:","replace_original": false}]}';
} else if ($value == 'love'){
    echo '{"response_type": "in_channel","text": "<@'.$user_id.'> loves this! :heart:","replace_original": false}]}';
} else if ($value == 'thumbsup'){
    echo '{"response_type": "in_channel","text": "<@'.$user_id.'> gives you both a thumbs up! :thumbsup:","replace_original": false}]}';
}



function updateDatabase($user, $file){

    $content = file_get_contents($file);
    $database = json_decode($content, true);
    //echo $content;

    # update user
    if (array_key_exists($user,$database)){
        $meritcount = (int)$database[$user]['merit'];
        $meritcount++;
        $database[$user]['merit'] = $meritcount;
        //echo ' '.$user.' OMAK Count is '.$database[$user]['omak'];
    } else {
        $omakcount = 0;
        $meritcount = 1;
        $database[$user] = array("omak" => $omakcount, "merit" => $meritcount);
        //echo 'added user ';
    }


    uploadChanges('public_html/'.$file, json_encode($database));
}

function uploadChanges($remote_file, $file_string){
    // FTP login
    $ftp_server="files.000webhost.com"; 
    $ftp_user_name="keechia"; 
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

?>