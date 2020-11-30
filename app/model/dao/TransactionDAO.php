<?php
namespace model\dao;
use model\abstractClass\AbstractDAO;
use model\object\Transaction;

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 23/11/2020
 * Time: 22:00
 */

class TransactionDAO extends AbstractDAO {

    public function getAllTransactions() {
        $q = "SELECT * FROM `transaction` ";
        $this->conn->executeQuery($q,[]);

        return $this->conn->fetchAllRows();
    }

    public function saveTransaction(Transaction $transaction) {

        $data = $transaction->getObjectVars();
        $querySql = 'SET @uuid = (SELECT uuid());';
        $querySql .= "INSERT INTO `transaction` (UUID, transaction_amount, transaction_type, transaction_description) VALUES ( ";
        $valuesSql = ' @uuid, :transaction_amount , :transaction_type , :transaction_description ';
        if(isset($data['transaction_description_id'])) {
            $querySql .= ', transaction_description_id ';
            $valuesSql .= ' , :transaction_description_id ';
        }

        if(isset($data['added_by'])) {
            $querySql .= ', added_by ';
            $valuesSql .= ' , :added_by ';
        }

        $querySql .= $valuesSql . ') ; ';
        $querySql .= ' SELECT * FROM `transaction` WHERE UUID = @uuid; ';
        $this->conn->executeQuery($querySql, $data);

        /**
         *
         * TODO:: do a  get last_insert_id in teh database object and just return that
         *
         */

        $this->conn->executeQuery("SELECT * FROM `transaction` WHERE UUID = @uuid",[]);

        return $this->conn->fetchSingleRow();
    }

}