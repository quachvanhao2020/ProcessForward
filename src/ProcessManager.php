<?php
namespace ProcessForward;
use Symfony\Contracts\Cache\CacheInterface;
use ProcessForward\Process;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProcessManager{

    /**
     * @var CacheInterface
     */
    protected $cache;
        /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(CacheInterface $cache = null)
    {
        if(!$cache){
            $cache = $cache = new FilesystemAdapter("",0,__DIR__."/../");
        }
        $this->cache = $cache;
        $this->serializer = $this->getSerializer();
    }

    /**
     * @return Process
     */
    public function get(string $id){
        $string = $this->cache->get($id, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return null;
        });
        $process = $this->serializer->deserialize($string, Process::class, 'json');
        return $process;
    }

    /**
     * @return bool
     */
    public function instanceProcess(){
        $process = new Process();
        $this->update($process->getId(),$process);
        return $process;
    }

    public function collect(string $id){

    }

    public function kill(string $id){
        $this->cache->delete($id);
    }

    public function handleProcess(string $id){
        $process = $this->get($id);
        $process->setIsRun(true);
        return $this->update($id,$process);
    }

    public function update(string $id,Process $process){
        $string = $this->serializer->serialize($process, 'json');
        var_dump($string);
        return $this->cache->get($id, function (ItemInterface $item) use($string) {
            //var_dump($string);
            $item->expiresAfter(3600);
            $value = $string;
            $item->set($value);
            return $value;
        });
    }

    public function release($data = null){
        header('Content-Type: application/json');
        $data = $this->serializer->serialize($data,'json');
        echo $data;
    }

    public function handle(ProcessRequest $request = null,bool $release = true){
        if(!$request){
            $request = ProcessRequest::createFromGlobals();
        }
        $action = $request->getAction();
        $id = $request->getId();
        $result = null;
        switch ($action) {
            case ProcessActionConst::GET:
                $result = $this->get($id);
                break;
            case ProcessActionConst::INSTANCE:
                $result = $this->instanceProcess();
                break;
            case ProcessActionConst::COLLECT:
                $result = $this->collect($id);
                break;
            case ProcessActionConst::KILL:
                $result = $this->kill($id);
                break;
            case ProcessActionConst::HANDLE:
                $result = $this->handleProcess($id);
                break;
            case ProcessActionConst::UPDATE:
                $process = new Process($id);
                $process->setParameter($request->getParameter());
                $process->setResult($request->getResult());
                $process->setError($request->getError());
                $result = $this->update($id,$process);
                break;
            default:
                break;
        }
        $release && $this->release($result);
        return $result;
    }

    /**
     * Get the value of cache
     *
     * @return  CacheInterface
     */ 
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Set the value of cache
     *
     * @param  CacheInterface  $cache
     *
     * @return  self
     */ 
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get the value of serializer
     *
     * @return  Serializer
     */ 
    public function getSerializer()
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer;
    }
}