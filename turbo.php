<?php
date_default_timezone_set("Asia/Baghdad");
  if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
  }
  define('MADELINE_BRANCH', 'deprecated');
  include 'madeline.php';
$settings['app_info']['api_id'] = 210897;
$settings['app_info']['api_hash'] = 'c7d2d161d83ce18d56c1a8a54437f5ff'; 
  $MadelineProto = new \danog\MadelineProto\API('me.madeline', $settings);
  $MadelineProto->start();
function bot($method, $datas = []) {
	$token = json_decode(file_get_contents('admin.json'),true)['token'];
	$url = "https://api.telegram.org/bot$token/" . $method;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$res = curl_exec($ch);
	curl_close($ch);
	return json_decode($res, true);
}
$type = json_decode(file_get_contents("status.json"),true);
if($type['status'] == "account"){
$x= 0;
while(1){
    $user = json_decode(file_get_contents('status.json'),true)['username'];
            try{
            	$MadelineProto->messages->getPeerDialogs(['peers' => [$user], ]);
            	            	$x++;
            	            	$json = json_decode(file_get_contents('status.json'),true)['loop'] = $x;
            	            	file_put_contents('status.json',json_encode($json));
            } catch (\danog\MadelineProto\Exception | \danog\MadelineProto\RPCErrorException $e) {
                    try{
                        $MadelineProto->account->updateUsername(['username'=>$user]);
bot('sendMessage', ['chat_id' => json_decode(file_get_contents('admin.json'),true)['id'], 'text' => " @$user = $x ."]);
                        exit;
                            }catch(Exception $e){
                        echo $e->getMessage();
                            bot('sendMessage', ['chat_id' => json_decode(file_get_contents('admin.json'),true)['id'], 'text' =>  "- @$user => ".$e->getMessage()
]);exit;
             }
        }
    }
}
if($type['status'] == "botfather"){
  $MadelineProto->messages->sendMessage(['peer' => '@botfather','message'=>'/newbot']);
  sleep(1);
  $MadelineProto->messages->sendMessage(['peer' => '@botfather','message'=>'- 🐟 .']);
  $x = 0;
while(1){
    $user = json_decode(file_get_contents('status.json'),true)['username'];
            try{
            	$MadelineProto->messages->getPeerDialogs(['peers' => [$user]]);
                          $x++;
            	           $json = json_decode(file_get_contents('status.json'),true)['loop'] = $x;
            	           file_put_contents('status.json',json_encode($json));
            } catch (\danog\MadelineProto\Exception | \danog\MadelineProto\RPCErrorException $e) {
                    try{
                      $MadelineProto->messages->sendMessage(['peer' => '@botfather','message'=>$user]);
bot('sendMessage', ['chat_id' => json_decode(file_get_contents('admin.json'),true)['id'], 'text' => " @$user = $x ."]);
                        exit;
                    }catch(Exception $e){
                            bot('sendMessage', ['chat_id' => json_decode(file_get_contents('admin.json'),true)['id'], 'text' =>  "- @$user => ".$e->getMessage()
]);exit;
             }
        }
    }
}