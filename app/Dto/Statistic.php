<?php
namespace App\Dto;

class Statistic
{
    public int $topic_closed_count = 0;
    public int $topic_not_closed_count = 0;
    public int $topic_count = 0;
    //issue
    public int $issue_closed_count = 0;
    public int $issue_not_closed_count = 0;
    public int $issue_count = 0;
    public ?array $departements;
    public $departement_id;
}