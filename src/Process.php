<?php
namespace ProcessForward;
use Symfony\Component\Uid\Uuid;

class Process{
    use ProcessTrait;
    public function __construct(string $id = null)
    {
        if(!$id){
            $id = Uuid::v4()."";
        }
        $this->id = $id;
        $this->isRun = false;
        $this->parameter = [];
        $this->result = [];
        $this->error = [];
    }
}