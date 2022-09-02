<?php
function sendInvoice($sendInvoice_url,$chat_id,$title,$description,$payload,$provider_token,$start_parameter,$currency,$LabeledPrice,$photoUrl,$product_keyboard,$height,$width,$need_name,$need_phone_number,$need_email,$need_shipping_address,$is_flexible) {

$postfields = array(
'chat_id' => $chat_id,
'title' => $title,
'description' => $description,
'photo_url' => $photoUrl,
'photo_width' => $width,
'photo_height' => $height,
'payload' => $payload,
'provider_token' => $provider_token,
'start_parameter' => $start_parameter,
'currency' => $currency,
'prices' => json_encode($LabeledPrice),
'reply_markup' => json_encode($product_keyboard),
'need_name' => $need_name,
'need_phone_number' => $need_phone_number,
'need_email' => $need_email,
'need_shipping_address' => $need_shipping_address,
'is_flexible' => $is_flexible
);


if (!$curld = curl_init()) {
exit;
}

curl_setopt($curld, CURLOPT_POST, true);
curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($curld, CURLOPT_URL,$sendInvoice_url);
curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($curld);

curl_close ($curld);
    
}

function shareContact($sendMessage_url,$chat_id,$text,$contact_keyboard) {
    $postfields = array(
        'chat_id' => $chat_id,
        'text' => $text,
        'reply_markup' => json_encode($contact_keyboard),
        'parse_mode' => 'HTML'
);
if (!$curld = curl_init()) {
exit;
}

curl_setopt($curld, CURLOPT_POST, true);
curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($curld, CURLOPT_URL,$sendMessage_url);
curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($curld);

curl_close ($curld);
}

function startShopping($sendMessage_url,$chat_id,$text,$keyboard) {
    $postfields = array(
        'chat_id' => $chat_id,
        'text' => $text,
        'reply_markup' => json_encode($keyboard)
);
if (!$curld = curl_init()) {
exit;
}

curl_setopt($curld, CURLOPT_POST, true);
curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($curld, CURLOPT_URL,$sendMessage_url);
curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($curld);

curl_close ($curld);
}

function showItems($sendMessage_url,$chat_id,$text,$cat_keyboard) {
    $postfields = array(
        'chat_id' => $chat_id,
        'text' => $text,
        'reply_markup' => json_encode($cat_keyboard)
        
);
if (!$curld = curl_init()) {
exit;
}

curl_setopt($curld, CURLOPT_POST, true);
curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($curld, CURLOPT_URL,$sendMessage_url);
curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($curld);

curl_close ($curld);
}

function chatidExit($sendMessage_url,$chat_id,$notification) {
    $postfields = array(
        'chat_id' => $chat_id,
        'text' => $notification
);
if (!$curld = curl_init()) {
exit;
}

curl_setopt($curld, CURLOPT_POST, true);
curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($curld, CURLOPT_URL,$sendMessage_url);
curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($curld);

curl_close ($curld);
}


function noproduct($sendMessage_url,$chat_id,$text) {
    $postfields = array(
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
);
if (!$curld = curl_init()) {
exit;
}

curl_setopt($curld, CURLOPT_POST, true);
curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($curld, CURLOPT_URL,$sendMessage_url);
curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($curld);

curl_close ($curld);
}
?>