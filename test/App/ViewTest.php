<?php

namespace SyachrilRamadhan\StudiKasus\Crud\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testRender()
    {
        View::render('Home/index', [
            "PHP Login Management"
        ]);

        $this->expectOutputRegex('[Login Crud]');
        $this->expectOutputRegex('[html]');
        $this->expectOutputRegex('[body]');
        $this->expectOutputRegex('[Login Management]');
        $this->expectOutputRegex('[Login]');
        $this->expectOutputRegex('[Register]');
    }

}