<?php

namespace App\Core\Attributes;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route{
    public function __construct(public string $path, public array $method, public bool $auth = false, public array $role = ["GUEST", "ADMIN"]) {}
}