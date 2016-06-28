<?php

class HighlandersPage extends Page {
   static $db = array(
   );
   static $has_one = array(
   );

   function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', new TextAreaField('Excerpt'), 'Content');

        return $fields;
    }
}

class HighlandersPage_Controller extends Page_Controller {
}

?>
