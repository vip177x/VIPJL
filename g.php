<?php
// BY BRoK - @x_BRK - @i_BRK //
date_default_timezone_set("Asia/Baghdad");
if (!file_exists('madeline.php')) {
 copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
define('MADELINE_BRANCH', 'deprecated');
include 'madeline.php';  
$settings['app_info']['api_id'] = 210897;
$settings['app_info']['api_hash'] = 'c7d2d161d83ce18d56c1a8a54437f5ff'; 
$MadelineProto = new \danog\MadelineProto\API('me.madeline', $settings);  
require("conf.php"); 
$TT = json_decode(file_get_contents('admin.json'),true)['token'];
$tg = new Telegram("$TT");
$lastupdid = 1; 
while(true){ 
 $upd = $tg->vtcor("getUpdates", ["offset" => $lastupdid]); 
 if(isset($upd['result'][0])){ 
  $text = $upd['result'][0]['message']['text']; 
  $chat_id = $upd['result'][0]['message']['chat']['id']; 
$from_id = $upd['result'][0]['message']['from']['id']; 
$sudo = json_decode(file_get_contents('admin.json'),true)['id'];;
$decode = json_decode(file_get_contents('status.json'),true);
$phone = $decode['phone'];
if($from_id == $sudo){
try{
if(json_decode(file_get_contents('status.json'),true)['step'] == "2"){
$MadelineProto->phonelogin($phone);
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"- ارسل الكود الان .",
]);
$decode['step'] = "3";
file_put_contents('status.json',json_encode($decode));
}
elseif(json_decode(file_get_contents('status.json'),true)['step'] == "3"){
if($text){
$authorization = $MadelineProto->completephonelogin($text);
if ($authorization['_'] === 'account.password') {
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"- ارسل رمز التحقق الان .",
]);
$decode['step'] = "4";
file_put_contents('status.json',json_encode($decode));
}else{
$decode['step'] = null;
file_put_contents('status.json',json_encode($decode));
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"- تم التسجيل بنجاح .",
]);
$decode['step'] = null;
file_put_contents('status.json',json_encode($decode));
$B = $MadelineProto->get_self();
file_put_contents('ses1.json',json_encode($B));
exit;
}
}
}elseif(json_decode(file_get_contents('status.json'),true)['step'] == "4"){
if($text){
$authorization = $MadelineProto->complete2falogin($text);
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"- تم التسجيل بنجاح .",
]);

exit;
$B = $MadelineProto->get_self();
file_put_contents('ses1.json',json_encode($B));
}
}
}catch(Exception $e) {
  $tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"- $e .",
]);
exit;
}}
$lastupdid = $upd['result'][0]['update_id'] + 1;
}
}