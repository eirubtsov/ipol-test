<?php
namespace Ipol\Demo\Api\Adapter;

interface AdapterInterface
{
    public function post(string $method, array $dataPost = []);
}