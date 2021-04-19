<?php
use Workerman\Worker;
use PHPSocketIO\SocketIO;
use ProcessForward\Process;
use ProcessForward\ProcessManager;
use ProcessForward\ProcessRequest;

require_once __DIR__."/vendor/autoload.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
global $pm,$io;
$io = new SocketIO(3031,
[
    //'origins'=>"*"
]
);
$pm = new ProcessManager();
//var_dump(ioProcessManager("{\"id\":3131,\"ff\":33,\"process_forward_aware\":true}"));
$io->on('connection', function($socket){
    var_dump("connect");
    $socket->on('request', function ($msg) use($socket){
        var_dump($msg);
        $response = ioProcessManager($msg);
        $socket->emit('response', $response);
        $socket->broadcast->emit('user joined', $response);
    });
    $socket->on('disconnect', function () use($socket) {
        var_dump("disconnect");
    });
    $socket->emit('response', 'Ok');
    $socket->emit('connect', 'Ok');
});

function ioProcessManager(string $msg){
    global $pm;
    $data = @\json_decode($msg,true);
    if(is_array($data)){
        $request = new ProcessRequest($data,$data);
        $response = $pm->handle($request,false);
        if(!$response){
            $response = [
                0
            ];
        }
        if($response instanceof Process){
            $response = $response->__toArray();
        }
        return $response;
    }
    return [];
}

if (!defined('GLOBAL_START')) {
    Worker::runAll();
}
