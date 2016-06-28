<?php

class WidePage extends Page {
   static $db = array(
           'WidePageWhiteText' => 'HTMLText',
   );
   static $has_one = array(
   );

   function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', new HtmlEditorField('WidePageWhiteText'), 'Content');

        return $fields;
    }
}

class WidePage_Controller extends Page_Controller {
}

