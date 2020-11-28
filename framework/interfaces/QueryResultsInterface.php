<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 24/07/2019
 * Time: 19:18
 */

namespace interfaces;


interface QueryResultsInterface {

    public function executeQuery(string $query = null, array $data = null);

    public function fetchSingleRow();

    public function fetchAllRows();

    public function fetchSingleColumn();

    public function fetchKeyedArray(string $key);
}