<?php
/**
 * Created by PhpStorm.
 * User: Julian Warren
 * Date: 14/09/2010
 * Time: 10:44:57 AM
 * To change this template use File | Settings | File Templates.
 */


class Map extends Page {
    static $db = array(
    'Address'=>'Text'
    );

    static $has_one = array(
   );

   function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new TextField('Address','Address: eg; newport street, hay on wye, england'));
        return $fields;
    }
}

class Map_Controller extends Page_Controller {

}

?>