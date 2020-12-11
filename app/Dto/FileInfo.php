<?php

namespace App\Dto;

class FileInfo
{
    public ?string $name;
    public ?string $extension;
    public ?string $data;
    public int $size = 0;
}