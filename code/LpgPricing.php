<?php

class LpgPricing extends UserDefinedForm {

    //private static $signupemail = null;
    //private static $failemail = null;


    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $editor = new HtmlEditorField("OnCompleteMessage", "Confirmation Message", $this->OnCompleteMessage);

        $fields->addFieldToTab("Root.Main", $editor, 'Content');


        //   $fields->removeFieldFromTab('Root.Main', 'Content');
        //   $fields->removeFieldfromTab('Root', 'FormOptions');
        //   $fields->removeFieldfromTab('Root', 'Submissions');

        return $fields;
    }
    //$MainContent= SiteTree::get()->filter(array('ID' => $this->ID));
//    public function MainContent(){
//
//          $mainContent = SiteTree::get()->byID($this->ID);
//        //var_dump($mainContent);
//           return $mainContent->Content;
//    }

}

class LpgPricing_Controller extends Page_Controller {
    private static $allowed_actions = array(
            'SignUpForm', 'doSignUp', 'doPhoneSubmit', 'Submitted', 'getZones', 'getPrice', 'submit', 'getNetworkProvider', 'getMonthsThresholds'
    );

    public function init() {
        parent::init();
//        require_once('FirePHPCore/fb.php');
        $rootTheme = $this->SiteConfig()->Theme;

        Requirements::block('themes/'.$rootTheme .'/javascript/foundation/foundation.forms.js');
        $routing = $this->getURLParams(); //$this->getAction();
        if (empty($routing['Action'])) {

            //Raphael will not tolerate Silverstripe Minification

/*            Requirements::combine_files(
                    'pulse-lpgpricing.js',
                    array(
                        //                <!-- form validation -->
                            $this->ThemeDir() . '/javascript/vendor/jquery.raphael-min.js',
                        // $this->ThemeDir() . '/javascript/vendor/raphael-zpd.js',
                            $this->ThemeDir() . '/javascript/vendor/jquery.cascadingdropdown.js',
                            $this->ThemeDir() . '/javascript/LPGPricing.js',

                    )
            );*/
            Requirements::javascript($this->ThemeDir() . '/javascript/vendor/jquery.raphael-min.js');
            Requirements::javascript($this->ThemeDir() . '/javascript/vendor/jquery.cascadingdropdown.js');
            Requirements::javascript($this->ThemeDir() . '/javascript/LPGPricing.js');
        }

    }


    /*
    * Sign Up form
    */
    public function LPGPricingForm() {
        $request = $this->getRequest();
        $promo = (string)$request['credits'];
        $validator = RequiredFields::create();
        $regions = LpgPricingTable::get()->sort('TerritorialAuthority')->column('TerritorialAuthority'); //->map('ID', 'TerritorialAuthority');//column('Region');//->columns('Region')->map('Region', 'Region', 'Please choose a Region');

        $mapArray = array();
        foreach ($regions as $region) {
            $regionName = trim(str_replace("-", " ", $region));
            $mapArray[$region] = $regionName;
        }

//        ChromePhp::log('$myMap');
//        ChromePhp::log($regions);
//        ChromePhp::log($mapArray);
        $fields = FieldList::create(
                DropdownField::create('District', 'District or City', $mapArray)->addExtraClass('step1')->setEmptyString("Please Choose a District"),
                DropdownField::create('Suburb', 'Nearest Suburb')->setAttribute('placeholder', 'Suburb')->addExtraClass('step2')->setEmptyString("Please Choose a Suburb")
        );
        $actions = new FieldList(
        //new FormAction('doLPGPricing', 'Submit')
        );
        $form = new Form($this, 'LPGPricingForm', $fields, $actions, $validator);
        $form->setAttribute('data-api-url', $this->Link());
        return $form;
    }

    public function GetZones() {

        $request = $this->getRequest();
        $regionDO = new LpgPricingTable;
        $regionList = $regionDO->get()->sort('ZoneName')->filter('TerritorialAuthority', $request['District'])->toArray();
        $returnstr = "[";
        $separator = "";
        foreach ($regionList as $region) {
            $objstr = $separator . "{\"label\":\"" . $region->ZoneName . "\",\"value\":\"" . $region->ID . "\"}";

            //$source .= "{ label ".$region.", value ".$region. "},";
            // $source .= "<option value=\"" . $region . "\">" . $region . "</option>\n";
            //$source[] = array($region->ID => $region->ZoneName);
            $returnstr .= $objstr;
            $separator = ",";
        }
        $returnstr .= "]";
        return $returnstr;

    }

    public function GetPrice() {
        $region = "Dunedin";
        $request = $this->getRequest();
        $regionDO = new LpgPricingTable;
        $regionList = $regionDO::get()->filter(array('ID' => $request['Suburb']))->toNestedArray();



        $source = array();
        return json_encode($regionList);
    }




}