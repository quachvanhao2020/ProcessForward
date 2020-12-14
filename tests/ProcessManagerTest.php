<?php
use PHPUnit\Framework\TestCase;
use ProcessForward\ProcessManager;
use ProcessForward\ProcessRequest;

class ProcessManagerTest extends TestCase
{
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
        //$result = $pm->handle($request,false);
        //$id = $result->getId();
        $id = "24bd855c-1a11-4f34-a141-abfa9a692259";
        $request = new ProcessRequest(
            [],
            [
                "process_forward_aware" => true,
                "action" => "handle",
                "id" => $id
            ],
        );
        $result = $pm->handle($request,false);

        //$pm = new ProcessManager();
        $request = new ProcessRequest(
            [],
            [
                "process_forward_aware" => true,
                "action" => "get",
                "id" => $id
            ],
        );
        $result = $pm->handle($request,false);

        var_dump($result);
    }
}