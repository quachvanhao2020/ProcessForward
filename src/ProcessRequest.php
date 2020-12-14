<?php
namespace ProcessForward;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class ProcessRequest extends Request{
    const PROCESS_FORWARD_AWARE = "process_forward_aware";
    use ProcessTrait;

    /**
     * @var string
     */
    protected $action;

    
    /**
     * @param array                $query      The GET parameters
     * @param array                $request    The POST parameters
     * @param array                $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array                $cookies    The COOKIE parameters
     * @param array                $files      The FILES parameters
     * @param array                $server     The SERVER parameters
     * @param string|resource|null $content    The raw body data
     */
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $query = $this->query;
        $query = $this->request;
        if($query->get(self::PROCESS_FORWARD_AWARE) != true){
            throw new BadRequestException();
        }
        $this->action = $query->get("action");
        switch ($this->action) {
            case ProcessActionConst::GET:
                break;
            case ProcessActionConst::COLLECT:
                return;
                break;
            case ProcessActionConst::INSTANCE:
                return;
                break;
            case ProcessActionConst::KILL:
                break;
            case ProcessActionConst::HANDLE:
                break;
            case ProcessActionConst::RELEASE:
                break;
            case ProcessActionConst::UPDATE:
                $this->setIsRun(true);
                $this->setParameter($this->decodeStringValue($query->get("parameter")));
                $this->setResult($this->decodeStringValue($query->get("result")));
                $this->setError($this->decodeStringValue($query->get("error"))); 
                break;
            default:
                break;
        }
        $this->setId($query->get("id"));
    }

    public function decodeStringValue(string $data){
        return \json_decode($data, true);
    }

    private static function createRequestFromFactory(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null): self
    {
        if (self::$requestFactory) {
            $request = (self::$requestFactory)($query, $request, $attributes, $cookies, $files, $server, $content);

            if (!$request instanceof self) {
                throw new \LogicException('The Request factory must return an instance of Symfony\Component\HttpFoundation\Request.');
            }

            return $request;
        }

        return new static($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Get the value of action
     *
     * @return  string
     */ 
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the value of action
     *
     * @param  string  $action
     *
     * @return  self
     */ 
    public function setAction(string $action)
    {
        $this->action = $action;

        return $this;
    }
}