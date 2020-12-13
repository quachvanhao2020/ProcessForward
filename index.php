<?php

use ProcessForward\Process;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use ProcessForward\ProcessManager;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

require_once "vendor/autoload.php";
$cache = new FilesystemAdapter("",0,__DIR__);

$value = $cache->get('my_cache_key', function (ItemInterface $item) {
    $item->expiresAfter(3600);
    $computedValue = 'foobar';
    return $computedValue;
});

echo $value; // 'foobar'


$request = Request::createFromGlobals();

//var_dump($request);
new ProcessManager();

$process = new Process();

var_dump($process);
$encoders = [new XmlEncoder(), new JsonEncoder()];
$normalizers = [new ObjectNormalizer()];

$serializer = new Serializer($normalizers, $encoders);
$jsonContent = $serializer->serialize($process, 'json');

var_dump($jsonContent);
$jsonContent = "[22,44]";

$process = $serializer->deserialize($jsonContent, "int[]", 'json');
var_dump($process);
