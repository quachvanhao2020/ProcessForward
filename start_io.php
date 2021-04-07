<?php
use Workerman\Worker;
use PHPSocketIO\SocketIO;
use ProcessForward\Process;
use ProcessForward\ProcessManager;
use ProcessForward\ProcessRequest;

require_once __DIR__."/vendor/autoload.php";
global $pm,$io;
$io = new SocketIO(2020);
$pm = new ProcessManager();
//var_dump(ioProcessManager("{\"id\":3131,\"ff\":33,\"process_forward_aware\":true}"));
$io->on('connection', function($socket){
    $socket->on('request', function ($msg) use($socket){
        $response = ioProcessManager($msg);
        $socket->emit('response', $response);
        $socket->broadcast->emit('user joined', $response);
    });
    $socket->on('disconnect', function () use($socket) {

    });
    $socket->emit('response', 'Ok');
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
