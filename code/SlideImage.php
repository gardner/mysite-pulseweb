<?php

class SlideImage extends DataObject {

      public static $plural_name = 'Slide Images';
      public static $singular_name = 'Slide Image';


    private static $db = array(
        'Name' => 'Varchar(255)',
        'Caption' => 'HTMLText',
        'LinkText' => 'Varchar(255)',
        'NotActive' => 'Boolean',

      //  'LinkHTML' => 'HTMLText',
        'SortID' => 'Int',
        'CaptionPosition' => "Enum('TopRight,BottomRight')"

    );
    private static $has_one = array(
        'Image' => 'Image',
        'MobileImage' => 'Image',
        'Page' => 'Page',
        'LinkURL' => 'SiteTree',
        'OverlayImage' => 'Image'
    );

    private static $has_many = array(
    );

// Summary fields
    private static $summary_fields = array(
        'SortID' => 'SortID',
        'Image.CMSThumbnail' => 'Image',
        'NotActive' => 'Not In Use',
        'MobileImage.CMSThumbnail' => 'MobileImage',
        'OverlayImage.CMSThumbnail' => 'Overlay',
        'Title' => 'Title',
        'ID' => 'ID'
    );
    //private static $default_sort = 'SortID ASC';
    private static $defaults = array(
        'CaptionPosition' => 'BottomRight',
    );
    /*
     * CMSFields
     * **************************************
     */

    function getCMSFields() {

        $fields = parent::getCMSFields();
        $imageField =  new UploadField('Image','Choose Image');
        $imageField->setDisplayFolderName('Uploads/promotionalbanners');
        $imageField->setFolderName('Uploads/promotionalbanners');
        $imageField->setRecord($this);

        $overlayImageField =  new UploadField('OverlayImage','Choose Overlay Image');
        $overlayImageField->setDisplayFolderName('Uploads/promotionalbanners/overlay');
        $overlayImageField->setFolderName('Uploads/promotionalbanners/overlay');
        $overlayImageField->setRecord($this);
//provide a default 2 pixel high image for mobile browsers to collapse the display.
        if(empty($this->MobileImageID)){
          //  $myimages = DataList::create('Image')->filter(array('ID'=> 729));;
          //  $myimages = Image::get()->filter(array('ID'=> 729));;
            $SiteConfig = SiteConfig::current_site_config();
            $myimages = Image::get()->filter(array('ID'=> $SiteConfig->DefaultMobileBannerID));;
        }
        else{
            //$myimages = DataList::create('Image')->filter(array('ID'=> $this->MobileImageID));;
            $myimages = Image::get()->filter(array('ID'=> $this->MobileImageID));;
        }
//this seems ridiculous but I can't seem to get an SS_LIST recognised by the UploadField any other way
        $imagearray = array();
        foreach($myimages as $myimage){
            $imagearray[] = $myimage;
        }
        $myarraylist = new ArrayList($imagearray);

        $mobileImageField =  new UploadField('MobileImage','Choose Mobile Image <br>(default is set in site settings)',$myarraylist);
        $mobileImageField ->setDisplayFolderName('Uploads/promotionalbanners/mobileimages');
        $mobileImageField ->setFolderName('Uploads/promotionalbanners/mobileimages');
        $mobileImageField ->setRecord($this);

        $linkurl =  new TreeDropdownField('LinkURLID','Page to link to from Image', 'SiteTree' );

     //   $this->write();
       // HtmlEditorConfig::set_active('reduced');
        $captionField = new HtmlEditorField('Caption', 'Image Caption');
        $captionField->setRows(5);
        $newFieldsArray = array(
            new TextField('Name'),
            $captionField,
           //new TextField('ImageLinkURL'),
        );

        $newFieldsArray_Advanced = array(
            new TextField('SortID'),
        //    new TextAreaField('ImageLinkHTML'),
        );
        $fields->removeFieldFromTab('Root.Main','NotActive');
        $activeField = new FieldGroup("Is Slide in Use?",
           new CheckboxField("NotActive", "Slide is Inactive")
        );

        $fields->removeFieldFromTab('Root.Main','PageID');
        $fields->addFieldsToTab("Root.Main", $newFieldsArray);
        $fields->addFieldsToTab("Root.Main", $mobileImageField);//last one first!
        $fields->addFieldsToTab("Root.Main", $overlayImageField, 'MobileImage');
        $fields->addFieldsToTab("Root.Main", $imageField, 'OverlayImage');
        $fields->addFieldsToTab("Root.Main", $linkurl, 'Image');
        $fields->addFieldsToTab("Root.Main", $activeField, 'LinkText');
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
        $getDataList = $this->Page()->SlideImage();
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