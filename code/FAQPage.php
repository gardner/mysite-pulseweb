<?php

class FAQPage extends Page {

    private static $db = array(
            );

    private static $has_one = array(
    );

    private static $many_many = array(
    );

    static $has_many = array(
       'FAQSection' => 'FAQSection',
    );

    public function populateDefaults() {

    }


    function getCMSFields() {
        $fields = parent::getCMSFields();

                 /*
                 * Add FAQ Section to the page using has_many relation + GridFeild
                 */

                $gridFieldConfig = GridFieldConfig_RelationEditor::create(40);
                $gridFieldConfig->addComponents(
                        new GridFieldSortableRows("SortID")
                );

                $gridField = new GridField("FAQSection", "FAQ Sections", $this->FAQSection(), $gridFieldConfig);
                $fields->addFieldToTab('Root.FAQ Sections', $gridField);

        return $fields;
        }
}

class FAQPage_Controller extends Page_Controller {

}

