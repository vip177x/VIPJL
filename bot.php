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
             [['text'=>'- Set User .'],['text'=>'- Turbo info .']],
             [['text'=>'- Save Into An Account .'],['text'=>'- Save Into Botfather .']],
             [['text'=>'- Run Turbo .'],['text'=>'- Close Turbo .']],
             [['text'=>'- Login .']],
      ]	
		]),'resize_keyboard'=>true
	]);
	$decode['set'] = null;
	$decode['step'] = null;
    file_put_contents('status.json',json_encode($decode));
}
if($text == '- Save Into An Account .'){ 
$tg->vtcor('sendmessage',[ 
'chat_id'=>$chat_id,  
'text'=>"- Done Set Move To Account .", 
]);
$decode['status'] = 'account';
file_put_contents('status.json',json_encode($decode));
} 
if($text == '- Save Into Botfather .'){ 
$tg->vtcor('sendmessage',[ 
'chat_id'=>$chat_id,  
'text'=>"- Done Set Move To BotFather .", 
]); 
$decode['status'] = 'botfather';
file_put_contents('status.json',json_encode($decode));
} 
if($text == '- Set User .'){ 
  $tg->vtcor('sendmessage',[ 
  'chat_id'=>$chat_id,  
  'text'=>"- Now Send Me The Username ."
  ]); 
  $decode['set'] = true;
  file_put_contents('status.json',json_encode($decode));
  } 
If($decode['set'] == true){
if($text != '- Set User .'){
  $tg->vtcor('sendmessage',[ 
  'chat_id'=>$chat_id,  
  'text'=>"- Done Set Username $text ."
  ]); 
$decode['username'] = $text;
$decode['set'] = null;
file_put_contents('status.json',json_encode($decode));
}
}
$type = $decode['status'];
$phone = $decode['phone'];
$user = $decode['username'];
if($text  == "- Turbo info ."){ 
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>"- Turbo info .
- Move To => 〔 $type 〕 .
- Phone => 〔 $phone 〕 .
- Username => 〔 @$user 〕.
- - - - - -
- BY => @VIPJL .
", 
]); 
}
if($text == '- Run Turbo .'){
$user = $decode['username'];
shell_exec('screen -S turbo -X kill'); 
shell_exec('screen -dmS turbo php turbo.php'); 
$tg->vtcor('sendmessage',[
  'chat_id'=>$chat_id,
  'text'=>"- OK . Started The Proccess Successfully .",
  'inline_keyboard'=>true,
 'reply_markup'=>json_encode([
      'keyboard'=>[
             [['text'=>'- Close Turbo .']],
      ]
 ])
]);
}
if($text == '- Close Turbo .'){
shell_exec('screen -S turbo -X kill'); 
$tg->vtcor('sendmessage',[ 
'chat_id'=>$chat_id,  
'text'=>"- Done Close The Turbo .", 
]);
}
if($text == '- Login .'){
system('rm -rf *ma*');
if($decode['step'] == null){
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"- Send The Number Now .
- Ex => +964**********",
]);
$decode['step'] = '1';
file_put_contents('status.json',json_encode($decode));
}
}
if($text != '- Login .' and $decode['step'] == '1'){
$decode['phone'] = $text;
$decode['step'] = '2';
file_put_contents('status.json',json_encode($decode));
system("php g.php");
}
}
$lastupdid = $upd['result'][0]['update_id'] + 1; 
}
}