<?php

class NatGasPricingTable extends DataObject {
    static $db = array(
            'gasgate' => 'Varchar',
            'region' => 'Varchar',
            'district' => 'Varchar',
            'conglomerated' => 'boolean',
            'realname' => 'Varchar',
            'descriptivename' => 'Varchar',
            'standardvariable' => 'Decimal(9,5)',
            'standarddaily' => 'Decimal(9,5)',
            'lowvariable' => 'Decimal(9,5)',
            'lowdaily' => 'Decimal(9,5)',
            'allenergydiscount' => 'Decimal(9,5)',


    );

    static $searchable_fields = array();
    static $summary_fields = array(

            'ID' => 'ID',
            'gasgate' => 'Gas gate',
            'conglomerated' => 'boolean',
            'region' => 'Region',
            'realname' => 'Real Name',
            'descriptivename' => 'Desc Name',
            'standardvariable' => 'S Variable',
            'standarddaily' => 'S Daily',
            'lowvariable' => 'L Variable',
            'lowdaily' => 'L Daily'

    );

}






