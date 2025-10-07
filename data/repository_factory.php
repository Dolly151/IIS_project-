<?php

require_once 'repository.php';

class RepositoryFactory
{
    public static function getRepository(): Repository
    {
        return new Repository('localhost', 'root', '', 'mydb');
    }
}