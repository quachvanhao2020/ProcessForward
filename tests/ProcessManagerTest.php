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
                "process_forward_aware" => "",
                "action" => "instance"
            ],
        );
        var_dump($request);
        $result = $pm->handle($request);
        var_dump($result);
    }
}