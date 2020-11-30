<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 29/11/2020
 * Time: 19:49
 */

namespace model\object;


use model\abstractClass\AbstractObject;

class Transaction extends AbstractObject{

    public static $requiredFields = [
        'transaction_amount',
        'transaction_type',
        'transaction_description'];

    public static $optionalFields = [
        'UUID',
        'added_by',
        'transaction_description_id'
    ];
    public $UUID;
    public $transactionAmount;
    public $transactionType;
    public $transactionDescription;
    public $addedBy;
    public $transactionDescriptionId;

    public function __construct(string $transactionAmount, string $transactionType, string $transactionDescription, string $UUID = null, string $addedBy = null, string $transactionDescriptionId = null) {

        $this->transactionAmount = (float) $transactionAmount;
        $this->transactionType = $transactionType;
        $this->transactionDescription = $transactionDescription;
        $this->UUID = $UUID;
        $this->addedBy = $addedBy;
        $this->transactionDescriptionId = $transactionDescriptionId;

    }


}