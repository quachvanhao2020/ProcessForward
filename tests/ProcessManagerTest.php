<?php
use PHPUnit\Framework\TestCase;
use ProcessForward\Process;
use ProcessForward\ProcessManager;
use ProcessForward\ProcessRequest;

class ProcessManagerTest extends TestCase
{
    public function testCollect(): void
    {
        $pm = new ProcessManager();
        $request = new ProcessRequest(
            [],
            [
                "process_forward_aware" => true,
                "action" => "collect"
            ],
        );
        $result = $pm->handle($request,false);
        var_dump($result);
    }
    
    /**
     * @group ignore
     */
    public function testPushAndPop(): void
    {
        $pm = new ProcessManager();
        $request = new ProcessRequest(
            [],
            [
                "process_forward_aware" => true,
                "action" => "instance"
            ],
        );
        $result = $pm->handle($request,false);
        $id = $result->getId();
        $request = new ProcessRequest(
            [],
            [
                "process_forward_aware" => true,
                "action" => "handle",
                "id" => $id
            ],
        );
        $result = $pm->handle($request,false);
        $request = new ProcessRequest(
            [],
            [
                "process_forward_aware" => true,
                "action" => "get",
                "id" => $id
            ],
        );
        $result = $pm->handle($request,false);
        if($result instanceof Process){
            $result->setParameter([
                "a" => true,
                "b" => false,
            ]);
            $result->setResult([
                "c" => 1,
                "d" => "d",
            ]);
            $result->setError([
                "error" => true,
            ]);
        }
        $request = new ProcessRequest(
            [],
            [
                "process_forward_aware" => true,
                "action" => "update",
                "id" => $id,
                "parameter" => \json_encode($result->getParameter()),
                "result" => \json_encode($result->getResult()),
                "error" => \json_encode($result->getError()),
            ],
        );
        $result = $pm->handle($request,false);
        if($result == true){
            $request = new ProcessRequest(
                [],
                [
                    "process_forward_aware" => true,
                    "action" => "release",
                    "id" => $id
                ],
            );
            //$result = $pm->handle($request,false);
        }
    }
}