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
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\Cache\Storage\StorageInterface;

class ProcessManager{
    const PROCESS_MAP = "process_map";

    /**
     * @var StorageInterface
     */
    protected $cache;
        /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(StorageInterface $cache = null)
    {
        if(!$cache){
            $cache = new Filesystem([
                "key_pattern" => "",
                "cache_dir" => __DIR__."/../data/",
                "dir_level"=>0,
                "suffix"=>"json",
                "namespace_separator"=>"",
                "tag_suffix"=>"",
                "namespace"=>"",
                "ttl"=>0,
            ]);
        }
        $this->cache = $cache;
        $this->serializer = $this->getSerializer();
    }

        /**
     * @return Process
     */
    public function getProcessMap(){
        $string = $this->cache->getItem(self::PROCESS_MAP);
        if(!$string){
            $string = "[]";
            $this->cache->setItem(self::PROCESS_MAP,$string);
        }
        $result = \json_decode($string,true);
        return $result;
    }

    public function updateProcessMap(string $id,array $value){
        $map = $this->getProcessMap();
        $map[$id] = $value;
        return $this->cache->setItem(self::PROCESS_MAP,\json_encode($map,JSON_PRETTY_PRINT));
    }

    /**
     * @return Process
     */
    public function get(string $id){
        $string = $this->cache->getItem($id);
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

    public function collect(){
        return $this->getProcessMap();
    }

    public function releaseProcess(string $id){
        $process = $this->get($id);
        $process->setIsRun(false);
        return $this->update($id,$process);
    }

    public function kill(string $id){
        $this->cache->removeItem($id);
    }

    public function handleProcess(string $id){
        $process = $this->get($id);
        $process->setIsRun(true);
        return $this->update($id,$process);
    }

    public function update(string $id,Process $process){
        $string = $this->serializer->serialize($process, 'json');
        $this->updateProcessMap($id,[
            Process::IS_RUN => $process->getIsRun(),
            Process::OWNED => $process->getOwned(),
        ]);
        return $this->cache->setItem($id,$string);
    }

    public function release($data = null){
        header('Content-Type: application/json');
        $data = $this->serializer->serialize($data,'json');
        echo $data;
    }

    /**
     * @return Process
     */
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
                $result = $this->collect();
                break;
            case ProcessActionConst::KILL:
                $result = $this->kill($id);
                break;
            case ProcessActionConst::HANDLE:
                $result = $this->handleProcess($id);
                break;
            case ProcessActionConst::RELEASE:
                $result = $this->releaseProcess($id);
                break;
            case ProcessActionConst::UPDATE:
                $process = new Process($id);
                $process->setIsRun(true);
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
     * @return  StorageInterface
     */ 
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Set the value of cache
     *
     * @param  StorageInterface  $cache
     *
     * @return  self
     */ 
    public function setCache(StorageInterface $cache)
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