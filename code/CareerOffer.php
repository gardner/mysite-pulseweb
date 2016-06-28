<?php

class CareerOffer extends DataObject {

      public static $plural_name = 'CareerOffers';
      public static $singular_name = 'CareerOffer';


    private static $db = array(
        'CareerContent' => 'HTMLText',
        'CareerContent2' => 'HTMLText',
        'Description' => 'Text',
        'SortID' => 'Int',
        'Active' => 'Boolean'
    );
    private static $has_one = array(
        'Image' => 'Image',
        'CareerPage' => 'CareerPage'
    );
    private static $has_many = array(
    );



// Summary fields
    public static $summary_fields = array(
        'SortID' => 'SortID',
        'Description' => 'Description',
        'ID' => 'ID',
        'Active' => 'Active'
    );
    public static $default_sort = 'SortID Asc';
    public static $defaults = array(
        'Active' => true
    );
    /*
     * CMSFields
     * **************************************
     */

    function getCMSFields() {
        $fields = parent::getCMSFields();

        $imageField =  new UploadField('Image','Choose Images');
        $imageField->setFolderName('Uploads/careers');
        $imageField->setRecord($this);

        $newFieldsArray = array(
            new HtmlEditorField('CareerContent', 'Career Content Left'),
            new HtmlEditorField('CareerContent2', 'Career Content Right'),
        );
        $newFieldsArray_Advanced = array(
            new TextField('SortID'),
        );

        $fields->addFieldsToTab("Root.Main",new HeaderField('Instructions', 'Please use H3, H4, H5 in this text'),'CareerContent');
        $fields->addFieldsToTab("Root.Main", $newFieldsArray);
        $fields->addFieldsToTab("Root.Main", $imageField);
        $fields->addFieldsToTab("Root.Main", new OptionsetField('Active','Career Visibility', array(true => 'Visible', false => 'Hidden')));
        $fields->addFieldsToTab("Root.Advanced", $newFieldsArray_Advanced);
        $fields->removeFieldsFromTab("Root.Main",array("CareerPageID"));
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
        if($this->CareerPage()->ID)$getDataList = $this->CareerPage()->CareerOffer();
        else return NULL;
        if (!$getDataList || $getDataList->Count() == 0) {
            return NULL;
        } else {
            return $getDataList->first()->SortID + 1;
        }
    }


}
