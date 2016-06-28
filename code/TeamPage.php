<?php

class TeamPage extends Page {

    private static $db = array(
        'Quote' => 'HTMLText',
    );

    private static $has_one = array(
    );

    private static $many_many = array(
    );

    static $has_many = array(
       'TeamMember' => 'TeamMember',
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

                $gridField = new GridField("TeamMember", "Team Members", $this->TeamMember(), $gridFieldConfig);
                $fields->addFieldToTab('Root.TeamMembers', $gridField);

        return $fields;
        }
}

class TeamPage_Controller extends Page_Controller {

}

