<?php


class ManagerTest extends \tests\TestCase
{
    public function testAdminManagerAddMethod()
    {
       $this->visit("/building/index")->assertResponseStatus(200);
    }
}
