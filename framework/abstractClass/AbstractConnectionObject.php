<?php
namespace abstractClass;
use interfaces\QueryConnectionManagerInterface;
use interfaces\QueryPrepareInterface;
use interfaces\QueryResultsInterface;

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 12/10/2019
 * Time: 21:11
 */

/**
 * Class AbstractConnectionObject
 * Wrapper class for all the connection interfaces
 */
abstract class AbstractConnectionObject implements QueryPrepareInterface, QueryResultsInterface, QueryConnectionManagerInterface {
    protected $conn;
    private $connectionVars;
    protected $host;
    protected $database;
    protected $username;
    protected $password;
    protected $charset;

    public function __construct(array $connectionVars) {
        $this->connectionVars = $connectionVars['MYSQL'] ?? [];
        $this->setConnectionVars();
    }

    protected function setConnectionVars() {
        $this->host = $this->connectionVars['HOST'] ?? false;
        $this->database = $this->connectionVars['NAME'] ?? false;
        $this->username = $this->connectionVars['USERNAME'] ?? false;
        $this->password = $this->connectionVars['PASSWORD'] ?? false;
        $this->charset = $this->connectionVars['CHARSET'] ?? null;
    }

    public function getConnectionVar(string $var) {
        if(property_exists($this,$var)) {
            if($var == 'password' && isset($this->password)) {
                return '';
            }

            return $this->{$var};
        }

        return false;
    }



}