<?php

require_once 'repository.php';

class RepositoryFactory
{
    public static function create(): Repository
    {
        return new Repository('localhost', 'root', '', 'mydb');
    }
}