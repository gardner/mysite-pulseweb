<?php

class FAQSection extends DataObject {

      public static $plural_name = 'FAQ Section';
      public static $singular_name = 'FAQ Sections';


    private static $db = array(
        'Content' => 'HTMLText',
        'Description' => 'Text',
        'SortID' => 'Int',
        'Type' => 'Text',
    );
    private static $has_one = array(
        'FAQPage' => 'FAQPage'
    );
    private static $has_many = array(
    );



// Summary fields
    public static $summary_fields = array(
        'SortID' => 'SortID',
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

        $questionField = new HtmlEditorField('Content', 'Content');
        $newFieldsArray_Advanced = array(
            new TextField('SortID'),
        );

 //     $fields->addFieldsToTab("Root.Main",new HeaderField('Instructions', 'Please use H3, H4, H5 in this text'),'CareerContent');
        $fields->addFieldsToTab("Root.Main", $questionField);
        $fields->addFieldsToTab("Root.Advanced", $newFieldsArray_Advanced);
        $fields->removeFieldsFromTab("Root.Main",array("FAQPageID"));
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
        if($this->FAQPage()->ID)$getDataList = $this->FAQPage()->FAQSection();
        else return NULL;
        if (!$getDataList || $getDataList->Count() == 0) {
            return NULL;
        } else {
            return $getDataList->first()->SortID + 1;
        }
    }


}
