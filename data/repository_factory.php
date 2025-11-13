<?php

require_once 'repository.php';

class RepositoryFactory
{
    public static function create(): Repository
    {
        return new Repository('sql7.freesqldatabase.com', 'sql7806918', 'GhFE2Dt2jl', 'sql7806918');
        // return new Repository('localhost', 'root', '', 'mydb');
    }
}