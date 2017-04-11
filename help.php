<?php
header("Content-Type: application/json; charset=UTF-8");
$token = $_POST['token'];
$user = $_POST['user_name'];
# Check the token and make sure the request is from our team 
if($token != 'Hj6op4TwR5Sh3yL0JaaXaNtd'){ #replace this with the token from your slash command configuration page
  $msg = "The token for the slash command doesn't match. Check your script.";
  die($msg);
  echo $msg;
}

echo '{"text": "*Hi <@'.$user.'>, my name is <@omakbot>, these are things i can help you*",
        "attachments":[
        {
            "pretext": "1. Submit your OMAK for someone",
            "title": "/omak [your message] @someone",
            "text": "type the slash command, followed by your message and end by @mentioning the person",
            "mrkdwn_in": ["text", "pretext", "title"],
            "color": "#3AA3E3"
        },
        {
            "pretext": "2. Kaypoh on someone",
            "title": "/kaypoh @someone",
            "text": "type the slash command and @mention the person you want to kaypoh (leave blank or type _myself_ to ownself check ownself)",
            "mrkdwn_in": ["text", "pretext"],
            "color": "#3AE3DD"
        },
        {
            "pretext": "_*Want to know how much OMAK is given by the team?*_",
            "text": "*<http://bit.ly/omakchart|Click here>* to check out your _\"Bank of Buddha\"_",
            "mrkdwn_in": ["text", "pretext"],
            "color": "#3AA3E3"
        }
    ]}';
?>