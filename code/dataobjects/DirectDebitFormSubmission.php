<?php

class DirectDebitFormSubmission extends DataObject {
    static $db = array(
            'Name' => 'Varchar',
            'SignatoryName' => 'Varchar',
            'ConsumerNumber' => 'Int',
            'ResidentialAddress' => 'Varchar',
            'PhoneDay' => 'Varchar',
            'PhoneNight' => 'Varchar',
            'NameOfAccount' => 'Varchar',
            'BankCodeNum' => 'Int',
            'BankBranchNum' => 'Int',
            'BankAccountNum' => 'Int',
            'BankAccountSuffix' => 'Varchar',
            'BankName' => 'Varchar',
            'BankBranchName' => 'Varchar',
            'BankAddress' => 'Varchar',
            'ApplicationDate' => 'SS_Datetime',
            'Email'=> 'Varchar'
    );

    static $field_labels = array(
            'Name' => 'Name',
            'SignatoryName' => 'Signatory Name',
            'ConsumerNumber' => 'Consumer Number',
            'ResidentialAddress' => 'Residential/Biz Address',
            'PhoneDay' => 'Day Phone',
            'PhoneNight' => 'Night Phone',
            'NameOfAccount' => 'Name of Account',
            'BankCodeNum' => 'Bank Code',
            'BankBranchNum' => 'Bank Branch #',
            'BankAccountNum' => 'Account #',
            'BankAccountSuffix' => 'Account Suffix',
            'BankName' => 'Bank Name',
            'BankBranchName' => 'Bank Branch',
            'BankAddress' => 'Bank Address',
            'ApplicationDate' => 'Application Date',
            'Email' => 'Email'
    );
    static $summary_fields = array(
            'ID',
            'Name',
            'SignatoryName',
            'ConsumerNumber',
            'ResidentialAddress',
            'Email',
            'PhoneDay',
            'PhoneNight',
            'NameOfAccount',
            'BankCodeNum',
            'BankBranchNum',
            'BankAccountNum',
            'BankAccountSuffix',
            'BankName',
            'BankBranch',
            'BankAddress',
            'ApplicationDate'
    );
    static $searchable_fields = array(
    );

    static $default_sort = "ID DESC";

    function getCMSFields() {
        $fields = parent::getCMSFields();
         return $fields;
    }

    function onBeforeWrite() {
        parent::onBeforeWrite();

//        if ($this->ID) {
//            $form_data = array();
//            $all_fields_array = Convert::json2array($this->FormData);
//
//            foreach ($all_fields_array as $key => $value) {
//
//                $form_data[$key] = $this->$key;
//
//            }
//            $this->FormData = Convert::array2json($form_data);
//        }
    }

}
