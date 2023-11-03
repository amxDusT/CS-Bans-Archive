<?php

if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}

require_once 'madeline.php';

use danog\MadelineProto\EventHandler;
use danog\MadelineProto\API;

class MyEventHandler extends EventHandler {

  public function onUpdateNewChannelMessage($update) {
    yield $this->onUpdateNewMessage($update);
  }
  public function onUpdateNewMessage($update) {
    if(isset($update['message']['out']) && $update['message']['out']) return;
    if(isset($update['message']['message'])) {
      $msg = $update['message']['message'];
    }
    if(isset($msg) and strtolower($msg) === '.check'){
      yield $this->messages->sendMessage(['peer' => $update, 'message' => '<b>Userbot Online✅</b>']);
    }
    if(isset($msg) and strtolower($msg) === ".prova"){
      yield $this->messages->sendMessage(['peer' => $update, 'message' => 'Messaggio 1.']);
      yield $this->sleep(2);
      yield $this->messages->sendMessage(['peer' => $update, 'message' => 'Messaggio 2.']);
      yield $this->sleep(4);
      yield $this->messages->sendMessage(['peer' => $update, 'message' => 'Messaggio 3.']);
    }
  }
}

$settings = [
    'logger' => [
        'logger_level' => 5
    ],
    'serialization' => [
        'serialization_interval' => 30
    ],
];

$MadelineProto = new API('session.madeline', $settings);
$MadelineProto->async(true);
$MadelineProto->loop(function () use ($MadelineProto) {
    yield $MadelineProto->start();
    yield $MadelineProto->setEventHandler('\MyEventHandler');
});
$MadelineProto->loop();
?>
