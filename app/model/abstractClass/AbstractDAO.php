<?php
use abstractClass\AbstractConnectionObject;

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 23/11/2020
 * Time: 22:11
 */
abstract class AbstractDAO {

    protected $conn;

    public function __construct(AbstractConnectionObject $conn) {

        $this->conn = $conn;

    }

}