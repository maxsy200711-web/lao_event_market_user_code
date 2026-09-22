<?php
session_start();
require_once __DIR__ . "/config.php";

$provider = $_GET['provider'] ?? '';
$role = $_GET['role'] ?? 'user';

$_SESSION['auth_role'] = $role;

if ($provider === 'google') {
    // 1. Google OAuth
    $client_id = 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com';
    $redirect_uri = 'http://localhost/lao_event_market_user_code/google_callback.php';
    
    $url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'response_type' => 'code',
        'scope' => 'email profile',
        'access_type' => 'online'
    ]);
    
    header("Location: " . $url);
    exit;

} elseif ($provider === 'facebook') {
    // 2. Facebook OAuth
    $app_id = 'YOUR_FACEBOOK_APP_ID';
    $redirect_uri = 'http://localhost/lao_event_market_user_code/facebook_callback.php';
    
    $url = "https://www.facebook.com/v18.0/dialog/oauth?" . http_build_query([
        'client_id' => $app_id,
        'redirect_uri' => $redirect_uri,
        'scope' => 'email,public_profile'
    ]);
    
    header("Location: " . $url);
    exit;

} elseif ($provider === 'tiktok') {
    // 3. TikTok OAuth
    $client_key = 'YOUR_TIKTOK_CLIENT_KEY';
    $redirect_uri = 'http://localhost/lao_event_market_user_code/tiktok_callback.php';
    
    $url = "https://www.tiktok.com/v2/auth/authorize/?" . http_build_query([
        'client_key' => $client_key,
        'redirect_uri' => $redirect_uri,
        'response_type' => 'code',
        'scope' => 'user.info.basic'
    ]);
    
    header("Location: " . $url);
    exit;

} else {
    header("Location: login.php");
    exit;
}