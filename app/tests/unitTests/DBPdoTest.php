<?php
use PHPUnit\Framework\TestCase;
use model\datasource\DBPdo;

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 24/11/2020
 * Time: 19:19
 */
class DBPdoTest extends TestCase {

    private $envVars;
    private $pdo;
    private $testDatabaseData = [
        [   '123',
            1.25,
            'credit',
            '1',
            'test'
        ],[   '456',
            2.25,
            'desc',
            '1',
            'test'
        ],[   '789',
            3.25,
            'desc',
            '1',
            'test'
        ],

    ];

    public function setUp() :void {
        parent::setUp();
        $this->envVars = [
            "HOST" => "127.0.0.1",
            "NAME" => "pocket_money_tracker",
            "USERNAME" => "root",
            "PASSWORD" => "",
            "CHARSET" => "utf8"
        ];
        $this->pdo = new DBPdo($this->envVars);
    }

    public function testCanOpenPdoConnection() {



        $this->assertNotNull($this->pdo);
        $this->assertTrue($this->pdo instanceof DBPdo);

        $this->assertTrue($this->pdo->openConnection());

    }

    public function testCanCloseConnection() {
        $this->pdo->openConnection();

        $this->assertNull($this->pdo->closeConnection());
    }

    public function testCanSetQueryString() {
        $this->pdo->openConnection();

        $this->assertNull($this->pdo->setQuery('QUERY TEST'));
    }

    public function testCanSetQueryData() {
        $this->pdo->openConnection();
        $this->pdo->setQuery("SELECT * FROM table WHERE value_1 = :value_1 AND value_2 = :value_2");

        $this->assertNull($this->pdo->setData(['value1', 'value2']));
    }

    public function testCanBindArrayDataToQuery() {
        $this->pdo->openConnection();
        $this->pdo->setQuery("SELECT * FROM table WHERE id = :id AND name = :name");
        $this->pdo->setData(['12345', 'james']);

        $expectedKeys = [':value_1', ':value_2'];
        $expectedValues = ['12345', 'james'];

        $boundDataArray = $this->pdo->bindData();

        $this->assertEquals(array_keys($boundDataArray), $expectedKeys);
        $this->assertEquals(array_values($boundDataArray), $expectedValues);

    }

    public function testCanBindValidMultidimensionalDataToQuery() {
        $this->pdo->openConnection();
        $this->pdo->setQuery("INSERT INTO `table` (`id`,`name`, `location`) VALUES (:id1, :name1, :location1 ),(:id2, :name2, :location2 ),(:id3, :name3, :location3 ) ");

        $this->pdo->setData([
            ['123', 'james','france'],
            ['456', 'kate','germany'],
            ['789', 'lizzy','ireland']
        ]);

        $expectedKeys = [':value_1', ':value_2',':value_3', ':value_4', ':value_5', ':value_6',':value_7', ':value_8', ':value_9'];
        $expectedValues = ['123', 'james', 'france', '456', 'kate', 'germany', '789', 'lizzy', 'ireland'];

        $boundDataArray = $this->pdo->bindData();

        $this->assertEquals(array_keys($boundDataArray), $expectedKeys);
        $this->assertEquals(array_values($boundDataArray), $expectedValues);

    }

    public function testCanExecuteQuery() {
        $result = $this->pdo->executeQuery("SELECT * FROM `transaction` WHERE UUID = :id AND transaction_type = :transaction_type", [12345, 'james']);

        $this->assertTrue($result);
    }

    public function testCanFetchSingleRow() {
        $this->setDatabaseData();
        $query = "SELECT UUID, transaction_amount, transaction_type, transaction_description, transaction_description_id FROM `transaction` WHERE UUID = :id ";
        $data = $this->testDatabaseData[0];
        $this->pdo->executeQuery($query, [$data[0]]);

        $result = $this->pdo->fetchSingleRow();

        //we set the fetch mode to object, so we need to compare the result as an array and get the values
        $this->assertEquals($data, array_values(get_object_vars($result)));
    }

    public function testCanFetchAllRows() {
        $this->setDatabaseData();
        $query = "SELECT UUID, transaction_amount, transaction_type, transaction_description, transaction_description_id FROM `transaction` ";
        $this->pdo->executeQuery($query, []);

        $result = $this->pdo->fetchAllRows();

        //we set the fetch mode to object, so we need to compare the result as an array and get the values
        $this->assertEquals(count($this->testDatabaseData),  count($result));
        $this->assertEquals($this->testDatabaseData[2][0],  $result[2]->UUID);
    }

    private function setDatabaseData() {
        $this->dropDatabaseData();
        $valuesSql = [];

        foreach($this->testDatabaseData as $row) {

            $placeMarkedValues = array_map(function($val){
                return ':' . $val . rand(1,10000) . " ";
            },$row);

            $placeMarkedString = implode(',', $placeMarkedValues);

            $valuesSql[] = '(' . $placeMarkedString . ')';
        }

        $valuesSql = implode(',', $valuesSql);

        $query = "INSERT INTO `transaction` (UUID, transaction_amount, transaction_type, transaction_description, transaction_description_id) VALUES $valuesSql";

//        $this->pdo->executeQuery($query,$this->testDatabaseData);

    }

    private function dropDatabaseData() {
        $q = "DELETE FROM `transaction` WHERE UUID IN ( :id1, :id2, :id3 )";
        $values = array_column($this->testDatabaseData,0);
//        $this->pdo->executeQuery($q,$values);
    }

    public function tearDown() :void {
        parent::tearDown();
        $this->envVars = null;
        $this->pdo = null;
    }
}
