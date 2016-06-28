<?php

class SwitchScoreSavePage extends HighlandersPage {
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

class SwitchScoreSavePage_Controller extends HighlandersPage_Controller {
}

?>
