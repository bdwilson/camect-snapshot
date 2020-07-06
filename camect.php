<?php
#
# Camect Snapshot Proxy - obtain snapshots from your internal Camect cameras from
# outside your network.
#
# this tool will allow you to connect to your local camect device via API and
# take a snapshot of any camera. To get the CamId for your camera, go to
# home.camect.com, then pop out a camera to a new window and the end of the URL should
# appear as this: /camera?id=caef913b5149abcef135  < this alpha-numeric code is
# the camera ID for your camera.
#
### Setup
# You'll need to then navigate to https://local.home.camect.com and accept the Terms of Service.
# You'll end up on your local server and the name will be xxxxxx.l.home.camect.com.
# This beginning part is considered your Camect Code.
$camcode = "abc1234";
#
# You'll then need to determine your username and password - the username in the default case
# is admin and the password is the first part of your email address that you used to register
# your camect device - for instance, bob@gmail.com would give you the password "bob".
$user = "admin";
$pass = "bob";
#
# defaults if not passed.
$width = "1024";
$height = "768";
#
# optional argument to authenticate your request. If &auth=whatever matches
# the code below. Not really needed as CamId's are not exactly guessable.
$auth_code = "";
###

if ($_REQUEST['auth'] && (!preg_match('/^[a-zA-Z0-9]+$/',$_REQUEST['auth']))) {
    exit;
}
if ($_REQUEST['snapshot'] && (!preg_match('/^[a-zA-Z0-9]+$/',$_REQUEST['snapshot']))) {
    exit;
}
if ($_REQUEST['width'] && (!preg_match('/^[0-9]+$/',$_REQUEST['width']))) {
    exit;
}
if ($_REQUEST['height'] && (!preg_match('/^[0-9]+$/',$_REQUEST['height']))) {
    exit;
}

# auth_code is optional if empty
if ($auth_code) {
    if ($_REQUEST['auth'] != $auth_code) {
        exit;
    }
}

if ($_REQUEST['snapshot']) {
    if ($_REQUEST['width']) {
      $width = $_REQUEST['width'];
    }
    if ($_REQUEST['height']) {

"image-camect.php" 104L, 3394C
    }
    if ($_REQUEST['height']) {
      $height = $_REQUEST['height'];
    }
    $image = getCamect($camcode, $width, $height, $_REQUEST['snapshot'], $user, $pass);
    if ($image) {
        header("Content-type: image/jpeg");
        echo $image;
    } else {
        echo "Error";
    }
}

function getCamect($camcode, $width, $height, $camId, $user, $pass) {

  $url = "https://" . $camcode . ".l.home.camect.com/api/SnapshotCamera?CamId=" . $camId. "&Width=" . $width . "&Height=" . $height;

  $options = array(
          CURLOPT_URL            => $url,
          CURLOPT_HEADER         => false,
          CURLOPT_VERBOSE        => false,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_SSL_VERIFYPEER => false,    // for https
          CURLOPT_USERPWD        => $user . ":" . $pass,
          CURLOPT_HTTPAUTH       => CURLAUTH_BASIC
  );

  $ch = curl_init();
  curl_setopt_array($ch, $options);

  try {
    $raw  = curl_exec( $ch );

    // validate CURL status
    if(curl_errno($ch))
        throw new Exception(curl_error($ch), 500);

    // validate HTTP status code (user/password credential issues)
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($status_code != 200)
        throw new Exception("Response with Status Code [" . $status_code . "].", 500);

  } catch(Exception $ex) {
      if ($ch != null) curl_close($ch);
      throw new Exception($ex);
  }
  if ($ch != null) curl_close($ch);
  if ($raw) {
    $data = json_decode($raw);
    return base64_decode($data->jpeg_data);
  }
}
