<?php

namespace SyachrilRamadhan\StudiKasus\Crud\App {

    function header(string $value){
        echo $value;
    }

}

namespace SyachrilRamadhan\StudiKasus\Crud\Service {

    function setcookie(string $name, string $value){
        echo "$name: $value";
    }

}