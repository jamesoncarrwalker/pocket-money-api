<?php

use model\datasource\DBPdo;
use PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 25/11/2020
 * Time: 19:57
 */
class AbstractConnectionObjectTest extends TestCase {

    private $envVars;
    private $conn;

    public function setUp() :void {
        parent::setUp();
        $this->envVars = [
            "HOST" => "localhost",
            "NAME" => "pocket_money_tracker",
            "USERNAME" => "root",
            "PASSWORD" => "password",
            "CHARSET" => "uft8"
        ];

        $this->conn = new DBPdo($this->envVars);
    }

    public function testCanSetConnectionVars() {

        $this->assertEquals('localhost', $this->conn->getConnectionVar('host'));
        $this->assertEquals('pocket_money_tracker', $this->conn->getConnectionVar('database'));
        $this->assertEquals('root', $this->conn->getConnectionVar('username'));
        $this->assertEquals('uft8', $this->conn->getConnectionVar('charset'));
    }

    public function testDoesNotReturnPassword() {

        $this->assertNotEquals('password', $this->conn->getConnectionVar('password'));

    }

    public function tearDown() :void{
        parent::tearDown();
        $this->envVars = null;
        $this->conn = null;
    }


}
