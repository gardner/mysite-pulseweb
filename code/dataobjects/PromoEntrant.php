<?php

class PromoEntrant extends DataObject
{
    static $db = array(
            'Name' => 'Varchar',
            'Phone' => 'Varchar',
            'Email' => 'Varchar',
            'Campaign' => 'Varchar',
            'User'=> 'Varchar',
            'AlreadyCustomer'=> 'Boolean'
    );

    static $field_labels = array(
            'Name' => 'Name',
            'Phone' => 'Phone',
            'Email' => 'Email',
            'Campaign' => 'Campaign',
            'User' => 'User',
            'AlreadyCustomer'=> 'AlreadyCustomer'

    );
    static $summary_fields = array(
            'ID',
            'Name',
            'Email',
            'Phone',
            'Campaign',
            'User',
            'AlreadyCustomer',
            'Created'


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


    }

}
