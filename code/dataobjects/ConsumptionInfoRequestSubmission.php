<?php

class ConsumptionInfoRequestSubmission extends DataObject
{
    static $db = array(
            'Name' => 'Varchar',
            'UnitNumber' => 'Varchar',
            'StreetNumber' => 'Varchar',
            'Street' => 'Varchar',
            'Suburb' => 'Varchar',
            'City' => 'Varchar',
            'Postcode' => 'Varchar',
            'ConsumerNumber' => 'Int(10)',
            'ICPNumber' => 'Varchar(15)',
            'Phone' => 'Varchar',
            'ApplicationDate' => 'SS_Datetime',
            'Email' => 'Varchar',
            'UniqueID' => 'Varchar',
/*            'Applicant' => 'Enum("I am am the account holder named above and am authorised to request this information,I am an authorised person,I am an authorised agent")',*/
            'ApplicantName' => 'Varchar'
    );

    static $field_labels = array(
            'Name' => 'Name',
            'UnitNumber' => 'Unit Number',
            'StreetNumber' => 'Street Number',
            'Street' => 'Street',
            'Suburb' => 'Suburb',
            'City' => 'City',
            'Postcode' => 'Postcode',
            'ConsumerNumber' => 'Consumer Number',
            'PhoneDay' => 'Day Phone',
            'ApplicationDate' => 'Application Date',
            'Email' => 'Email',
            'UniqueID' => 'Unique ID',
            'ApplicantName' => 'Applicant Name'
    );
    static $summary_fields = array(
            'ID',
            'Name',
            'ConsumerNumber',
            'ICPNumber',
            'Email',
            'Phone',
            'ApplicationDate',
            'ApplicantName',
            'UniqueID'
    );
    static $searchable_fields = array();

    static $default_sort = "ID DESC";

    function getCMSFields()
    {
        $fields = parent::getCMSFields();

        return $fields;
    }

    function onBeforeWrite()
    {
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
