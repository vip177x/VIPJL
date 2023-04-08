<?php
require('conf.php');
if (!file_exists("admin.json")) {
$token =  readline("- Enter Token : ");
$id = readline("- Enter iD : ");
file_put_contents('admin.json',json_encode(['token' => $token, 'id' => $id]));
}
if (!file_exists("status.json")) {
file_put_contents('status.json',json_encode(['status' => null]));
}
$token = json_decode(file_get_contents('admin.json'),true)['token'];
$id = json_decode(file_get_contents('admin.json'),true)['id'];
$tg = new Telegram($token);
$decode = json_decode(file_get_contents('status.json'),true);
$lastupdid = 1;
while(true){
 $upd = $tg->vtcor("getUpdates", ["offset" => $lastupdid]);
 if(isset($upd['result'][0])){
  $text = $upd['result'][0]['message']['text'];
  $chat_id = $upd['result'][0]['message']['chat']['id'];
$from_id = $upd['result'][0]['message']['from']['id'];
$username = $upd['result'][0]['message']['from']['username'];
if($from_id == $id){ 
 if($text == '/start'){
    $tg->vtcor('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"- Hello Bro .\n- Your Can Use These Commands .\n- - - - -\n- BY => @VIPJL .",
    'inline_keyboard'=>true,
 'reply_markup'=>json_encode([
      'keyboard'=>[
             [['text'=>'- تعيين المستخدم .'],['text'=>'- معلومات Turbo .']],
             [['text'=>'- حفظ في حساب .'],['text'=>'- حفظ في Botfather .']],
             [['text'=>'- تشغيل Turbo .'],['text'=>'- أغلق Turbo .']],
             [['text'=>'- تسجيل دخول حسابي .']],
      ]	
		]),'resize_keyboard'=>true
	]);
	$decode['set'] = null;
	$decode['step'] = null;
    file_put_contents('status.json',json_encode($decode));
}
if($text == '- حفظ في حساب .'){
$tg->vtcor('sendmessage',[ 
'chat_id'=>$chat_id,  
'text'=>"- تم تعيين نقل إلى الحساب.", 
]);
$decode['status'] = 'account';
file_put_contents('status.json',json_encode($decode));
} 
if($text == '- حفظ في Botfather .'){ 
$tg->vtcor('sendmessage',[ 
'chat_id'=>$chat_id,  
'text'=>"- ✅ Set Move To BotFather .", 
]); 
$decode['status'] = 'botfather';
file_put_contents('status.json',json_encode($decode));
} 
if($text == '- تعيين المستخدم .'){ 
  $tg->vtcor('sendmessage',[ 
  'chat_id'=>$chat_id,  
  'text'=>"- الآن أرسل لي اسم المستخدم ."
  ]); 
  $decode['set'] = true;
  file_put_contents('status.json',json_encode($decode));
  } 
If($decode['set'] == true){
if($text != '- تعيين المستخدم .'){
  $tg->vtcor('sendmessage',[ 
  'chat_id'=>$chat_id,  
  'text'=>"- ✅ تعيين المستخدمname $text ."
  ]); 
$decode['username'] = $text;
$decode['set'] = null;
file_put_contents('status.json',json_encode($decode));
}
}
$type = $decode['status'];
$phone = $decode['phone'];
$user = $decode['username'];
if($text  == "- معلومات Turbo ."){ 
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>"- معلومات Turbo .
- Move To => 〔 $type 〕 .
- Phone => 〔 $phone 〕 .
- Username => 〔 @$user 〕.
- - - - - -
- BY => @VIPJL .
", 
]); 
}
if($text == '- تشغيل Turbo .'){
$user = $decode['username'];
shell_exec('screen -S turbo -X kill'); 
shell_exec('screen -dmS turbo php turbo.php'); 
$tg->vtcor('sendmessage',[
  'chat_id'=>$chat_id,
  'text'=>"- OK . Started The Proccess Successfully .",
  'inline_keyboard'=>true,
 'reply_markup'=>json_encode([
      'keyboard'=>[
             [['text'=>'- أغلق Turbo .']],
      ]
 ])
]);
}
if($text == '- أغلق Turbo .'){
shell_exec('screen -S turbo -X kill'); 
$tg->vtcor('sendmessage',[ 
'chat_id'=>$chat_id,  
'text'=>"- ✅ اذهب أغلق The Turbo .", 
]);
}
if($text == '- تسجيل دخول حسابي .'){
system('rm -rf *ma*');
if($decode['step'] == null){
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"- أرسل الرقم الآن .
- Ex => +964**********",
]);
$decode['step'] = '1';
file_put_contents('status.json',json_encode($decode));
}
}
if($text != '- تسجيل دخول حسابي .' and $decode['step'] == '1'){
$decode['phone'] = $text;
$decode['step'] = '2';
file_put_contents('status.json',json_encode($decode));
system("php g.php");
}
}
$lastupdid = $upd['result'][0]['update_id'] + 1; 
}
}