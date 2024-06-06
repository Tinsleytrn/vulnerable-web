<?
$user_data = [
    'user_id' => 1,
    'username' => 'admin',
    'role' => 'admin'
];
$payload = base64_encode(serialize($user_data));
echo $payload;
