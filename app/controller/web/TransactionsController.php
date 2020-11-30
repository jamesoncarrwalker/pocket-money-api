<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 28/11/2020
 * Time: 22:04
 */

namespace controller\web;


use abstractClass\AbstractWebController;
use enum\ContainerContentsEnum;
use helper\DataValidator;
use model\container\WebContainer;
use model\dao\TransactionDAO;
use model\helper\DumpVars;
use model\object\Transaction;

class TransactionsController extends AbstractWebController{

    const TRANSACTION_MODEL_FQN = 'model\object\Transaction';

    private $transactionDao;
    private $transaction;


    public function __construct(WebContainer $container, $requestAction) {
        parent::__construct($container, $requestAction);

        $this->transactionDao = new TransactionDAO($this->container->getStateVariable(ContainerContentsEnum::CONN));
    }

    public function get() {

        $this->setData('transactions',$this->transactionDao->getAllTransactions());

    }

    public function post() {
        $requestData = $this->getAllRequestData();
        if(DataValidator::checkRequiredDataExists(self::TRANSACTION_MODEL_FQN, array_keys($requestData))) {
            $data = DataValidator::getRequiredDataForClassFromArray(self::TRANSACTION_MODEL_FQN, $requestData);

            $this->transaction = new Transaction(...array_values($data));
            $this->setData('transaction', $this->transactionDao->saveTransaction($this->transaction));


        } else {
            throw new \Exception('Did not have all the required data.');
        }



    }

}