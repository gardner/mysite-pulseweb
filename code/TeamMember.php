<?php

class TeamMember extends DataObject {

      public static $plural_name = 'Team Member';
      public static $singular_name = 'Team Members';


    private static $db = array(
        'MemberContent' => 'HTMLText',
        'Description' => 'Text',
        'Name' => 'Text',
        'SortID' => 'Int'
    );
    private static $has_one = array(
        'Image' => 'Image',
        'TeamPage' => 'TeamPage'
    );
    private static $has_many = array(
    );



// Summary fields
    public static $summary_fields = array(
        'SortID' => 'SortID',
        'Name' => 'Name',
        'Description' => 'Description',
        'ID' => 'ID'
    );
    public static $default_sort = 'SortID Asc';
    public static $defaults = array(

    );
    /*
     * CMSFields
     * **************************************
     */

    function getCMSFields() {
        $fields = parent::getCMSFields();

        $imageField =  new UploadField('Image','Choose Images');
        $imageField->setFolderName('Uploads/teams');
        $imageField->setRecord($this);

        $newFieldsArray = array(
            new HtmlEditorField('MemberContent', 'Member Content'),
        );
        $newFieldsArray_Advanced = array(
            new TextField('SortID'),
        );

 //       $fields->addFieldsToTab("Root.Main",new HeaderField('Instructions', 'Please use H3, H4, H5 in this text'),'CareerContent');
        $fields->addFieldsToTab("Root.Main", $newFieldsArray);
        $fields->addFieldsToTab("Root.Main", $imageField);
        $fields->addFieldsToTab("Root.Advanced", $newFieldsArray_Advanced);
        $fields->removeFieldsFromTab("Root.Main",array("TeamPageID"));
        return $fields;
    }

    /*
     * Extra Functions
     * **************************************
     */

    function onBeforeWrite() {
        parent::onBeforeWrite();
        if (!$this->SortID) {
           $this->SortID = $this->getNextSortID();
        }
    }

    function onAfterWrite() {
        parent::onAfterWrite();
        /*
          $getDataList = $this->SGallery()->Images();
          if (!$getDataList || $getDataList->Count() == 0) {
          $nextSortID = 22;
          } else {

          $nextSortID = $getDataList->last()->SortID+1;
          }
          if (!$this->SortID && $this->SGalleryID) {
          $this->SortID = $nextSortID;
          $this->write();
          }
         * 
         */
    }

    function getNextSortID() {
        //print_r($this);
        if($this->TeamPage()->ID)$getDataList = $this->TeamPage()->TeamMember();
        else return NULL;
        if (!$getDataList || $getDataList->Count() == 0) {
            return NULL;
        } else {
            return $getDataList->first()->SortID + 1;
        }
    }


}
