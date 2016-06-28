<?php

class Page extends SiteTree
{

    private static $db = array(
            'DropInContent' => 'HTMLText',
            'MenuExcerpt' => 'HTMLText',
            'RedirectToChildOne' => 'Boolean',
            'RawText' => 'Text',
            'Theme' => "Enum('Freedom,Business,Gas', 'Freedom')",
            'GreenCTA' => 'HTMLText',
    );

    private static $has_one = array(
            'DropInLink' => 'SiteTree',
            'DropInImage' => 'Image',
            'PageIcon' => 'Image'
    );

    private static $has_many = array(
            'SlideImage' => 'SlideImage',
    );

    function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $excerptfield = new HtmlEditorField('MenuExcerpt', 'Menu Excerpt', '');
        $excerptfield->setRows(5);
        $excerptfield->setColumns(15);
        $greenCTAfield = new HtmlEditorField('GreenCTA', 'Green CTA Content', '');
        //$greenCTAfield->setRows(5);
        $greenCTAfield->setColumns(15);
        $fields->addFieldToTab('Root.Main', $greenCTAfield);
        $rawtextfield = new TextareaField('RawText', 'Raw Text Like Javascript', '');
        $rawtextfield->setRows(5);
        $rawtextfield->setColumns(15);
        $fields->addFieldToTab('Root.Main', $rawtextfield);
        $fields->addFieldToTab('Root.Main', new DropdownField('Theme', 'Theme',
                array('Freedom' => 'Freedom', 'Business' => 'Business', 'Gas' => 'Gas')));
        $fields->addFieldToTab('Root.MenuExcerpt', $excerptfield);
        $iconImageField = new UploadField('PageIcon', 'Icon Image');
        $iconImageField->setFolderName('Uploads/doodles');
        $iconImageField->setConfig('allowedMaxFileNumber', 1);
        $fields->addFieldToTab('Root.IconImage', $iconImageField);

        $gridFieldConfig2 = GridFieldConfig_RelationEditor::create();
        $gridFieldConfig2->addComponents(
               new GridFieldSortableRows("SortID") , new GridFieldBulkManager()//,new GridFieldBulkUpload()
        )->removeComponentsByType('GridFieldAddExistingAutocompleter');

        $gridField2 = new GridField("Images", "Slide Images", $this->SlideImage(), $gridFieldConfig2);
        $fields->addFieldToTab('Root.Banners', $gridField2);

        return $fields;
    }

    function getSettingsFields()
    {
        $fields = parent::getSettingsFields();
        $fields->addFieldToTab('Root.Settings',
                new CheckboxField("RedirectToChildOne", "Redirect to first available child of this page, if any."));

        return $fields;
    }

    function versionedcheck()
    {
        $baseClass = ClassInfo::baseDataClass($this->owner->class);
        print_r($baseClass);

        return print_r(DataList::create('SiteTree')->toArray());
    }

}

class Page_Controller extends ContentController
{

    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    /*	public static $allowed_actions = array (
        );*/

    public function init()
    {
        parent::init();

        // Note: you should use SS template require tags inside your templates
        // instead of putting Requirements calls here.  However these are
        // included so that our older themes still work

        Requirements::block(THIRDPARTY_DIR . '/jquery/jquery-1.9.1.min.js');
        Requirements::block(THIRDPARTY_DIR . '/jquery/jquery.js');
        Requirements::block(THIRDPARTY_DIR . '/jquery-ui-themes/smoothness/jquery-ui.css');
        //Requirements::block(THIRDPARTY_DIR . '/jquery-ui/jquery-ui.js');
        Requirements::block(THIRDPARTY_DIR . '/jquery-ui/datepicker/i18n/jquery.ui.datepicker-en-GB.js');

        // avoid reloading the javascript for different pages. Use the cache.
        $rootTheme = 'themes/' . $this->SiteConfig()->Theme;
        //ChromePhp::log($rootTheme.'/javascript/foundation/foundation.forms.js');

        switch ($this->Theme) {

            case "Business":
                Config::inst()->update('SSViewer', 'theme', 'pulse-business');
                break;
            case "Gas":
                Config::inst()->update('SSViewer', 'theme', 'pulse-gas');
                break;
            default:
                Config::inst()->update('SSViewer', 'theme', 'highlanders');
        }

        $theme = SSViewer::get_theme_folder();

        //       if (Director::isLive()){
        // $theme.'/javascript/vendor/jquery.js',
        //Requirements::javascript($theme.'/javascript/vendor/jquery-1.9.1.min.js');
        Requirements::combine_files(
                $theme . '.css',
                array(
                        $theme . '/css/normalize.css',
                   //     $theme.  '/css/foundation-icons.css',
                        $theme . '/css/app.css',
                )
        );
        Requirements::combine_files(
                $theme . '.js',
                array(
                        $rootTheme . '/javascript/vendor/jquery-1.10.2.min.js',
                        $rootTheme . '/javascript/foundation/foundation.js',
                        $rootTheme . '/javascript/foundation/foundation.alerts.js',
                    //  $rootTheme.'/javascript/foundation/foundation.clearing.js',
                        $rootTheme . '/javascript/foundation/foundation.cookie.js',
                    //   $rootTheme.'/javascript/foundation/foundation.dropdown.js',

                        $rootTheme . '/javascript/foundation/foundation.magellan.js',
                        $rootTheme . '/javascript/foundation/foundation.section.js',
                        $rootTheme . '/javascript/foundation/foundation.orbit.js',
                        $rootTheme . '/javascript/foundation/foundation.placeholder.js',
                        $rootTheme . '/javascript/foundation/foundation.reveal.js',
                    //  $rootTheme.'/javascript/foundation/foundation.tooltips.js',
                        $rootTheme . '/javascript/foundation/foundation.topbar.js',
                    //  $rootTheme.'/javascript/foundation/foundation.interchange.js',
                        $rootTheme . '/javascript/foundation/jquery.foundation.mediaQueryToggle.js',
                        $rootTheme . '/javascript/vendor/jquery.maphilight.min.js',
                        $rootTheme . '/javascript/vendor/jquery.hoverIntent.minified.js',
                        $rootTheme . '/javascript/vendor/picturefill.min.js',
                        $rootTheme . '/javascript/vendor/map.js',

                )
        );
        // necessary so it can be blocked on LPG Pricing
        Requirements::javascript($rootTheme . '/javascript/foundation/foundation.forms.js');

        //Requirements::themedCSS('jquit/jquery-ui');
        Requirements::themedCSS('pulseui/jquery-ui-1.10.4.custom');
        Requirements::themedCSS('layout'); //previously before uicustom
        Requirements::themedCSS('foundation-icons');

        Requirements::javascript($theme . '/javascript/vendor/jquery-ui-1.10.3.custom.js');
        Requirements::javascript($rootTheme . '/javascript/vendor/jquery.ui.datepicker-en-GB.js');
        Requirements::javascript($rootTheme . '/javascript/app.js');

        if ($this->RedirectToChildOne && $this->Children()->Count()) {
            $this->redirect($this->Children()->First()->AbsoluteLink());
        }
    }

    public function ChildColumnSizeFeat()
    {
        /*           $count = DB::query("SELECT COUNT(*) FROM SiteTree_Live WHERE ParentID = '$this->ID'")->value();
                   //$size = round(12/$count,0);
                //return $size;
                    switch ($count){
                        case 1: return "twelve";
                        case 2: return "six";
                        case 3: return "four";
                        case 4: return "three";
                        case 5: return "two";  //eek wtf!! This leaves a hole. Dont do it.
                        case 6: return "two";
                    }*/


    }

    public function Business()
    {
        if ($this->PageType == 'Business') {
            return 'Business';
        }
    }

    function CanonicalURL() {
        //Director::protocolAndHost()
       return "<link rel=\"canonical\" href=\"". $this->Link() ."\" />";
    }

}


