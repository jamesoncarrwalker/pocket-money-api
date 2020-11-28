<?php
namespace model\datasource;
use abstractClass\AbstractConnectionObject;
use helper\StringFormatter;


/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 12/10/2019
 * Time: 21:02
 */
class DBPdo extends AbstractConnectionObject {

    private $query;
    private $data;
    private $pdoQueryObject;

    public function openConnection() {
        if(!isset($this->conn)) {
            try {

                $connectionString = 'mysql:host=' . $this->host . ';dbname=' . $this->database . ';charset=' . ($this->charset ?? 'utf8') . ';';

                $conn = new \PDO($connectionString, $this->username, $this->password);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);//remove for production
                $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
                $conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
                $this->conn = $conn;
                return $this->conn instanceof \PDO;
            } catch (\PDOException $e) {
                error_log($e->getMessage());
                echo $e->getMessage();
                die();
            }
        }
    }

    public function closeConnection() {
        $this->conn = null;
        if(isset($this->conn)) {
            throw new \Exception('Could not close sql connection ');
        }
    }

    /**
     * @param string $query
     * @throws \Exception
     *
     * sets the query string.  Any placeholder parameteres must be prefixed with a semi-colon,
     * followed by a space and must be unique
     *
     * E.g. 'SELECT * FROM table WHERE id = :user_id AND name = :user_name'
     *      'UPDATE table SET col_1 = :val_1 , col_2 = :val_2
     *
     * The binding process will check the number of occurances, so reusing
     * placeholders is not allowed (and is not considered good practice anyway)
     *
     */
    public function setQuery(string $query) {
        $this->query = StringFormatter::replaceOccurrencesWithTagPrefix($query,':','value');

        if(!isset($this->query)) {
            throw new \Exception('Could not set sql query');
        }
    }

    public function setData(array $data) {
        if(isset($data) && count($data) > 0 ) {
            $this->data = $data;
        } else {
            $this->data = [];
        }

       if(!isset($this->data)) {
           throw new \Exception('Could not set sql data');
       }
    }

    /**
     * @return array
     * @throws \Exception
     *
     * returns a key => value array of the placeholder and the data
     */
    public function bindData() :array {

        if(!isset($this->query) || !isset($this->data)) {
            throw new \Exception('Cannot bind data if query or data are not set');
        }

        if(!$this->checkNumberOfPlaceMarkersMatchesVariableCount()) {
            throw new \Exception('Number of place markers did not match number of variables');
        }

        $boundData = [];
        $counter = 1;
        foreach($this->data as $value) {
            if(!isset($value)) continue;
            if(!is_array($value)) {
                $key = ':value_' . $counter;
                $boundData[$key] = $value;
                $counter++;
            } else {
                foreach($value as $v) {
                    $key = ':value_' . $counter;
                    $boundData[$key] = $v;
                    $counter++;
                }
            }
        }
        return $boundData;
    }

    private function checkNumberOfPlaceMarkersMatchesVariableCount() : bool {
        if(!isset($this->query) || !isset($this->data)) {
            return false;
        }
        $placeMarkerCount = substr_count($this->query,':');

        if(is_array($this->data)) {

            $dataCount = array_map(function($data){
                if(!isset($data)) return 0;
                if(!is_array($data)) return 1;
                return count($data);
            }, $this->data);

            $dataCount = array_sum($dataCount);

        } else {
            $dataCount = 1;
        }

        return $placeMarkerCount == $dataCount;

    }

    public function executeQuery(string $query = null, array $data = null) {
        if(isset($query)) {
            $this->setQuery($query);
        }

        if(isset($data)) {
            $this->setData($data);
        }

        if(!isset($this->query) || !isset($this->data)) {
            throw new \Exception('Cannot execute query if query or data are not set');
        }

        if(!isset($this->conn)) {
            $this->openConnection();
        }

        $data = $this->bindData();
        $query = $this->query;

        //reset the query and data to ensure we don't accidentally pass if we call another query
        $this->resetQueryAndData();
        $this->pdoQueryObject = $this->conn->prepare($query);
        return $this->pdoQueryObject->execute($data);

    }

    private function resetQueryAndData() {
        $this->query = null;
        $this->data = null;
    }

    public function fetchSingleRow() {
        if(!isset($this->conn)) {
           throw new \Exception('No connection object set');
        }

        if(!isset($this->pdoQueryObject)) {
            $this->executeQuery();
        }

        return $this->pdoQueryObject->fetch();
    }

    public function fetchAllRows() {
        if(!isset($this->conn)) {
            throw new \Exception('No connection object set');
        }

        if(!isset($this->pdoQueryObject)) {
            $this->executeQuery();
        }

        return $this->pdoQueryObject->fetchAll();
    }

    public function fetchSingleColumn() {
        // TODO: Implement fetchSingleColumn() method.
    }

    public function fetchKeyedArray(string $key) {
        // TODO: Implement fetchKeyedArray() method.
    }
}