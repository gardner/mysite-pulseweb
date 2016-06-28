<?php

class CareerPage extends Page {

    private static $db = array(
        'Quote' => 'HTMLText',
    );

    private static $has_one = array(
    );

    private static $many_many = array(
    );

    static $has_many = array(
       'CareerOffer' => 'CareerOffer',
    );

    public function populateDefaults() {

    }


    function getCMSFields() {
        $fields = parent::getCMSFields();


                $quotefield = new HtmlEditorField('Quote','Quote','');
                $quotefield->setRows(25);
                $fields->addFieldToTab('Root.Main', $quotefield);

                        /*
                 * Add Jobs Section to the page using has_many relation + GridFeild
                 */

                $gridFieldConfig = GridFieldConfig_RelationEditor::create();
                $gridFieldConfig->addComponents(
                        new GridFieldSortableRows("SortID")
                );

                $gridField = new GridField("CareerOffer", "Careers Offered", $this->CareerOffer(), $gridFieldConfig);
                $fields->addFieldToTab('Root.CareerOffers', $gridField);

        return $fields;
        }
}

class CareerPage_Controller extends Page_Controller {

}

