<?php
namespace ProcessForward;

use DateTime;
use Symfony\Component\Uid\Uuid;

class Process implements ProcessConstInterface{
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
        $date = new DateTime();
        $this->time = $date->getTimestamp();
    }

    public function __toArray(){
        return [
            self::ID => $this->getId(),
            self::IS_RUN => $this->getIsRun(),
            self::OWNED => $this->getOwned(),
            self::PARAMETER => $this->getParameter(),
            self::RESULT => $this->getResult(),
            self::TIME => $this->getTime(),
        ];
    }
}