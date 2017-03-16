<?php
echo "XenForo is no longer used. Click <a href='https://www.tregmine.com/'>me</a> to go back to Tregmine.";
exit;
/*require_once '../include/tregmine_api.php';
require_once '../include/functions.php';
function loadConfiguration()
{
    return array(
        'api_root' => 'https://rabil.org/forum/api',
        'api_key' => 'im8x98nvbu',
        'api_secret' => 's968lyxycps7ovx',
        'api_scope' => 'read'
    );
}
function displaySetup()
{
    require(dirname(__FILE__) . '/setup.php');
    exit;
}
function getBaseUrl()
{
    // idea from http://stackoverflow.com/questions/6768793/get-the-full-url-in-php
    $ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true : false;
    $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $_SERVER['SERVER_PORT'];
    $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
    // using HTTP_POST may have some security implication
    $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $_SERVER['SERVER_NAME'] . $port;
    $baseUrl = $protocol . '://' . $host . $_SERVER['REQUEST_URI'];
    $baseUrl = preg_replace('#\?.*$#', '', $baseUrl);
    $baseUrl = rtrim($baseUrl, '/');
    return $baseUrl;
}
function getCallbackUrl()
{
    return sprintf(
        '%s?action=callback',
        getBaseUrl()
    );
}
function generateJsSdkUrl($apiRoot)
{
    $url = sprintf(
        '%s/index.php?assets/sdk.js',
        $apiRoot
    );
    return $url;
}
function generateOneTimeToken($apiKey, $apiSecret, $userId = 0, $accessToken = '', $ttl = 86400)
{
    $timestamp = time() + $ttl;
    $once = md5($userId . $timestamp . $accessToken . $apiSecret);
    return sprintf('%d,%d,%s,%s', $userId, $timestamp, $once, $apiKey);
}
function makeRequest($url, $apiRoot, $accessToken)
{
    if (strpos($url, $apiRoot) === false) {
        $url = sprintf(
            '%s/index.php?%s&oauth_token=%s',
            $apiRoot,
            $url,
            rawurlencode($accessToken)
        );
    }
    $body = @file_get_contents($url);
    $json = @json_decode($body, true);
    return array($body, $json);
}
function makeSubscriptionRequest($config, $topic, $fwd, $accessToken = null)
{
    $subscriptionUrl = sprintf(
        '%s/index.php?subscriptions',
        $config['api_root']
    );
    $callbackUrl = sprintf(
        '%s/subscriptions.php?fwd=%s',
        rtrim(preg_replace('#index.php$#', '', getBaseUrl()), '/'),
        rawurlencode($fwd)
    );
    $postFields = array(
        'hub.callback' => $callbackUrl,
        'hub.mode' => !empty($accessToken) ? 'subscribe' : 'unsubscribe',
        'hub.topic' => $topic,
        'oauth_token' => $accessToken,
        'client_id' => $config['api_key'],
    );
    return array('response' => makeCurlPost($subscriptionUrl, $postFields, false));
}
function makeCurlPost($url, $postFields, $getJson = true)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $body = curl_exec($ch);
    curl_close($ch);
    if (!$getJson) {
        return $body;
    }
    $json = @json_decode($body, true);
    if (empty($json)) {
        die('Unexpected response from server: ' . $body);
    }
    return $json;
}
function makeCurl($url, $getJson = true)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $body = curl_exec($ch);
    curl_close($ch);
    if (!$getJson) {
        return $body;
    }
    $json = @json_decode($body, true);
    if (empty($json)) {
        die('Unexpected response from server: ' . $body);
    }
    return $json;
}
function renderMessageForPostRequest($url, array $postFields)
{
    $message = 'It looks like you are testing a local installation. ';
    $message .= 'Since this test server cannot reach yours, please run this command in your terminal ';
    $message .= '(or equivalent) please:<br /><br />';
    $message .= '<div class="code">curl -XPOST "' . $url . '" \\</div>';
    $postFieldKeys = array_keys($postFields);
    $lastFieldKey = array_pop($postFieldKeys);
    foreach ($postFields as $postFieldKey => $postFieldValue) {
        $message .= sprintf(
            '<div class="code"> -F %s=%s%s</div>',
            $postFieldKey,
            $postFieldValue,
            $postFieldKey === $lastFieldKey ? '' : ' \\'
        );
    }
    return $message;
}
function renderMessageForJson($url, array $json)
{
    global $accessToken;
    $html = str_replace(' ', '&nbsp;&nbsp;', var_export($json, true));
    if (!empty($accessToken)) {
        $offset = 0;
        while (true) {
            if (preg_match('#\'(?<link>http[^\']+)\'#', $html, $matches, PREG_OFFSET_CAPTURE, $offset)) {
                $offset = $matches[0][1] + strlen($matches[0][0]);
                $link = $matches['link'][0];
                $replacement = null;
                if (strpos($link, $accessToken) !== false) {
                    // found a link
                    $targetUrl = sprintf(
                        '%s?action=request&url=%s&access_token=%s',
                        getBaseUrl(),
                        rawurlencode($link),
                        rawurlencode($accessToken)
                    );
                    $replacement = sprintf('<a href="%s">%s</a>', $targetUrl, $link);
                } elseif (substr($link, 0, 4) === 'http') {
                    $replacement = sprintf('<a href="%1$s" target="_blank">%1$s</a>', $link);
                }
                if (!empty($replacement)) {
                    $html = substr_replace(
                        $html,
                        $replacement,
                        $matches['link'][1],
                        strlen($matches['link'][0])
                    );
                    $offset = $matches[0][1] + strlen($replacement);
                }
            } else {
                break;
            }
        }
    }
    return sprintf(
        '<div class="request">Sent Request: %s</div><div class="response">Received Response: %s</div>',
        $url,
        nl2br($html)
    );
}
function renderAccessTokenMessage($tokenUrl, array $json)
{
    global $config, $accessToken;
    if (!empty($json['access_token'])) {
        $accessToken = $json['access_token'];
        $message = sprintf(
            'Obtained access token successfully!<br />'
            . 'Scopes: %s<br />'
            . 'Expires At: %s<br />',
            $json['scope'],
            date('c', time() + $json['expires_in'])
        );
        if (!empty($json['refresh_token'])) {
            $message .= sprintf('Refresh Token: <a href="index.php?action=refresh&refresh_token=%1$s">%1$s</a><br />', $json['refresh_token']);
        } else {
            $message .= sprintf('Refresh Token: N/A<br />');
        }
        list($body, $json) = makeRequest('index', $config['api_root'], $accessToken);
        if (!empty($json['links'])) {
            $message .= '<hr />' . renderMessageForJson('index', $json);
        }
    } else {
        $message = renderMessageForJson($tokenUrl, $json);
    }
    return $message;
}
function getArrayFromServer($tokenUrl, array $json)
{
	global $config, $accessToken;
    if (!empty($json['access_token'])) {
        $accessToken = $json['access_token'];
        list($body, $json) = makeRequest('index', $config['api_root'], $accessToken);
        if (!empty($json['links'])) {
            $message .= '<hr />' . renderMessageForJson('index', $json);
        }
    } else {
        $message = renderMessageForJson($tokenUrl, $json);
    }
    return $message;
}
function isLocal($apiRoot) {
    $apiRootHost = parse_url($apiRoot, PHP_URL_HOST);
    $isLocal = in_array($apiRootHost, array(
        'localhost',
        '127.0.0.1',
        'local.dev',
    ));
    return $isLocal;
}
function bitlyShorten($token, $url)
{
    $bitlyUrl = sprintf(
        '%s?access_token=%s&longUrl=%s&domain=j.mp&format=txt',
        'https://api-ssl.bitly.com/v3/shorten',
        rawurlencode($token),
        rawurlencode($url)
    );
    $body = @file_get_contents($bitlyUrl);
    if (!empty($body)) {
        $url = $body;
    }
    return $url;
}
//Begin script
$config = loadConfiguration();
$action = 'callback';
switch($action){
	case 'callback':
		if (empty($_REQUEST['code'])) {
            $message = 'Callback request must have `code` query parameter!';
            break;
        }
        $tokenUrl = sprintf(
            '%s/index.php?oauth/token',
            $config['api_root']
        );
        $postFields = array(
            'grant_type' => 'authorization_code',
            'client_id' => $config['api_key'],
            'client_secret' => $config['api_secret'],
            'code' => $_GET['code'],
            'redirect_uri' => 'https://rabil.org/index.php/xfauth/auth',
        );
        if (isLocal($config['api_root']) && !isLocal(getBaseUrl())) {
            $message = renderMessageForPostRequest($tokenUrl, $postFields);
            break;
        }
        // step 4
        $json = makeCurlPost($tokenUrl, $postFields);
        $userprofile = "https://rabil.org/forum/api/index.php?users/".$json['user_id']."/";
        $userdatajson = makeCurl($userprofile);
        $profile = $userdatajson['user'];
        if(!isset($profile['user_id'])){
        	header('Location: https://rabil.org/');
        }
        //Good, now update xenforo with the players username
        $link = new mysqli("127.0.0.1", "tregmine_api", "MeGqZyNhpTd5HVVy", "tregmine_xf");
        if($link->connect_error){
        	die("Connection failed: ".$conn->connect_error);
        }
		$stmt = $link->prepare("UPDATE xf_user_field_value SET field_value = ? WHERE user_id = ? AND field_id = ?");
		
		$table = "mc_username";
      	$stmt->bind_param("sis", $profile['username'], $profile['user_id'], $table);
      	$status = $stmt->execute();
      	$insertion = $link->prepare("INSERT INTO xf_user_field_value (field_value, user_id, field_id) VALUES (?, ?, ?)");
      	$insertion->bind_param("sis", $profile['username'], $profile['user_id'], $table);
      	$insertion->execute();
      	$link->close();
      	mysqli_report(MYSQLI_REPORT_ALL);
      	$link = new mysqli("127.0.0.1", "tregmine_api", "MeGqZyNhpTd5HVVy", "tregmine_db");
      	$stmt = $link->prepare("UPDATE xf_map SET ign=?,xfname=? WHERE playerid=?");
      	$stmt->bind_param("ssi", $_SESSION['name'], $profile['username'], $_SESSION['id']);
      	$stmt->execute();
      	$insertion = $link->prepare("INSERT INTO xf_map (playerid, ign, xfname) VALUES (?, ?, ?)");
      	$insertion->bind_param("iss", $_SESSION['id'], $_SESSION['name'], $profile['username']);
      	try{
      	$insertion->execute();
      	}catch(mysqli_sql_exception $e){
      		
      	}
      	$xenLink = array();
      	$xenLink['ign'] = $_SESSION['name'];
      	$xenLink['xfname'] = $profile['username'];
      	$xenLink['playerid'] = $_SESSION['id'];
      	$_SESSION['xenLink'] = $xenLink;
      	$_SESSION['xenLinked'] = true;
      	$link->close();
      	$context = array();
      	$context['success'] = true;
      	$context['xenforoprofile'] = $profile;
      	$styles = array();
		$scripts = array("/js/start.js");
		render('xfauth_success.phtml', 'XenForo Connection Successful', $context, $styles, $scripts);
        break;
}
*/