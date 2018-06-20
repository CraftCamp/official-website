<?php

namespace App\Registry;

interface RegistryInterface
{
    public function store(array $items = []);
    
    public function getItems(): array;
}