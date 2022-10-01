<?php

function getDatabaseConfig(): array
{
    return [
        "database" => [
            "test" => [
                "url" => "mysql:host=localhost:3306;dbname=dblogin-crud-test",
                "username" => "root",
                "password" => ""
            ],
            "prod" => [
                "url" => "mysql:host=localhost:3306;dbname=dblogin-crud",
                "username" => "root",
                "password" => ""
            ]
        ]
    ];
}