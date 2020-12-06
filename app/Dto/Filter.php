<?php
namespace App\Dto;

class Filter
{
    public int $limit = 10;
    public int $page = 1;
    public string $orderType = "asc";
    public ?string $orderBy = null;
    public array $fieldsFilter = [];
    public bool $match = false;
    public bool $withDetail = false;
}