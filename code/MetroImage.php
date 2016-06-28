<?php

class MetroImage extends DataObject {

      public static $plural_name = 'Metro Images';
      public static $singular_name = 'Metro Image';


    private static $db = array(
        'Name' => 'Varchar(255)',
        'ImageLinkHTML' => 'HTMLText',
        'SortID' => 'Int'


    );
    private static $has_one = array(
        'Image' => 'Image',
        'ImageLinkPage' => 'SiteTree',
//        'HeroPage' => 'HeroPage',
        'Page' => 'Page',
    );
    private static $has_many = array(
//        'SGalleryImageTextFields' =>'SGalleryImageTextField'
    );
    private static $belongs_many_many = array(
       'HeroPages' => 'HeroPage'
    );

// Summary fields
    public static $summary_fields = array(
        'SortID' => 'SortID',
        'Image' => 'Image',
        'Title' => 'Title',
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
/*        $fields->removeFieldFromTab('Root.Main','ImageLinkPageID');
        $fields->removeFieldFromTab('Root.Main','HeroPageID');
        $fields->removeFieldFromTab('Root.Main','VehiclePageID');*/

/*        $fields->replaceField('PageID', new HiddenField('PageID', 'PageID', $this->Page()->ID ));*/
        $imageField =  new UploadField('Image','Choose Images');
        $imageField->setFolderName('Uploads/metro');
        $imageField->setRecord($this);

        $newFieldsArray = array(
            new TextField('Name'),
            new HtmlEditorField('ImageLinkHTML'),
            new TreeDropdownField('ImageLinkPageID','Page to link to from Metro Button', 'SiteTree' )

        );
        $newFieldsArray_Advanced = array(
            new TextField('SortID'),
        );
        $fields->removeFieldFromTab("Root.Main", "PageID");
        $fields->addFieldsToTab("Root.Main", $newFieldsArray);
        $fields->addFieldsToTab("Root.Main", $imageField);
        $fields->addFieldsToTab("Root.Advanced", $newFieldsArray_Advanced);


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
 //       if($this->HeroPage()) $getDataList = $this->HeroPage()->MetroImage();
        if($this->Page()->ID) $getDataList = $this->Page()->MetroImage();
        else return NULL;
//        $parentID = $this->HeroPage()->ID;
//        $ParentImages = $this->HeroPage()->Images();
//        $ParentLastImageSortID = $this->HeroPage()->Images()->last()->SortID;

        if (!$getDataList || $getDataList->Count() == 0) {
            return NULL;
        } else {

            return $getDataList->first()->SortID + 1;
        }
    }

    public static function ThumbnailImage() {
        
    }

    public static function FullImage() {
        
    }

     public function test() {
        return print $this->getNextSortID();
    }

}

/*

    function onBeforeWrite() {
        parent::onBeforeWrite();
        if (!$this->SortID) {
            $this->SortID = $this->getNextSortID();
        }
    }


    function getNextSortID() {
        $getDataList = $this->SGallery()->Images();
        if (!$getDataList || $getDataList->Count() == 0) {
            return NULL;
        } else {

            return $getDataList->last()->SortID + 1;
        }
    }
 * 
 */