<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of School
 *
 * @author Amir
 */
class BankBranchRegister extends DataObject {
    static $db = array(
            'Bank_Number' => 'Varchar',
            'Branch_Number' => 'Varchar',
            'Bank_Name' => 'Varchar',
            'City' => 'Varchar',
            'Physical_Address1' => 'Varchar',
            'Physical_Address1' => 'Varchar',
            'Physical_Address2' => 'Varchar',
            'Physical_Address3' => 'Varchar',
            'PostCode' => 'Varchar',
    );


    static $summary_fields = array(
            'Bank_Number' => 'Bank Number',
            'Branch_Number' => 'Branch Number',
            'Bank_Name' => 'Bank Name',
            'City' => 'City',
            'Physical_Address1' => 'Address1',
            'Physical_Address1' => 'Address2',
            'Physical_Address2' => 'Address3',
            'Physical_Address3' => 'Address3',
            'PostCode' => 'Postcode',
    );

}

?>
