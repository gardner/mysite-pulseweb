<?php

class HighlandersPage extends Page {
   static $db = array(
   );
   static $has_one = array(
   );

//    private static $casting = array(
//            'RequestCallHandler' => 'HTMLText'
//    );
//
//    public static function RequestCallHandler()
//    {
//        $current = Controller::curr();
//        return $current->RequestCallForm()->forTemplate();
//    }

   function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', new TextAreaField('Excerpt'), 'Content');

        return $fields;
    }
}

class HighlandersPage_Controller extends Page_Controller
{

    public function init()
    {
        parent::init();
}

    private static $allowed_actions = array(
            'index',
            'finished',
            'submitRequestCallForm',
            'Submitted',
            'AjaxRequestCallSubmit',
            'RequestCallForm'
    );


}

