<?php
namespace model\datasource;
use abstractClass\AbstractConnectionObject;


/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 12/10/2019
 * Time: 21:02
 */
class DBPdo extends AbstractConnectionObject {

    public function openConnection() {
        // TODO: Implement openConnection() method.
        return 'opening connection';
    }

    public function closeConnection() {
        // TODO: Implement closeConnection() method.
    }

    public function setQuery(string $query) {
        // TODO: Implement setQuery() method.
    }

    public function setData(array $data) {
        // TODO: Implement setData() method.
    }

    public function bindData() {
        // TODO: Implement bindData() method.
    }

    public function executeQuery() {
        // TODO: Implement executeQuery() method.
    }

    public function fetchSingleRow() {
        // TODO: Implement fetchSingleRow() method.
    }

    public function fetchAllRows() {
        // TODO: Implement fetchAllRows() method.
    }

    public function fetchSingleColumn() {
        // TODO: Implement fetchSingleColumn() method.
    }

    public function fetchKeyedArray(string $key) {
        // TODO: Implement fetchKeyedArray() method.
    }
}