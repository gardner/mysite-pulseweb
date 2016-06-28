<?php

class NatGasPricing extends UserDefinedForm {

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

class NatGasPricing_Controller extends Page_Controller {
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
                    'pulse-NatGasPricing.js',
                    array(
                        //                <!-- form validation -->
                            $this->ThemeDir() . '/javascript/vendor/jquery.raphael-min.js',
                        // $this->ThemeDir() . '/javascript/vendor/raphael-zpd.js',
                            $this->ThemeDir() . '/javascript/vendor/jquery.cascadingdropdown.js',
                            $this->ThemeDir() . '/javascript/NatGasPricing.js',

                    )
            );*/
            Requirements::javascript($this->ThemeDir() . '/javascript/vendor/jquery.raphael-min.js');
            Requirements::javascript($this->ThemeDir() . '/javascript/vendor/jquery.cascadingdropdown.js');
            Requirements::javascript($this->ThemeDir() . '/javascript/NatGasPricing.js');
        }

    }


    /*
    * Sign Up form
    */
    public function NatGasPricingForm() {
        $request = $this->getRequest();
        $promo = (string)$request['credits'];
        $validator = RequiredFields::create();
        $regions = NatGasPricingTable::get()->sort('region')->column('region'); //->map('ID', 'TerritorialAuthority');//column('Region');//->columns('Region')->map('Region', 'Region', 'Please choose a Region');

        $mapArray = array();
        foreach ($regions as $region) {
            $regionName = trim(str_replace("-", " ", $region));
            $mapArray[$region] = $regionName;
        }

//        ChromePhp::log('$myMap');
//        ChromePhp::log($regions);
//        ChromePhp::log($mapArray);
        $fields = FieldList::create(
                DropdownField::create('District', 'Region or City', $mapArray)->addExtraClass('step1')->setEmptyString("Please Choose a Region or City"),
                DropdownField::create('Suburb', 'Nearest Suburb')->setAttribute('placeholder', 'Suburb')->addExtraClass('step2')->setEmptyString("Please Choose a District")
        );
        $actions = new FieldList(
        //new FormAction('doNatGasPricing', 'Submit')
        );
        $form = new Form($this, 'NatGasPricingForm', $fields, $actions, $validator);
        $form->setAttribute('data-api-url', $this->Link());
        return $form;
    }

    public function GetZones() {

        $request = $this->getRequest();
        $regionDO = new NatGasPricingTable;
        $regionList = $regionDO->get()->sort('realname')->filter('region', $request['District'])->toArray();
        $returnstr = "[";
        $separator = "";
        foreach ($regionList as $region) {
            $objstr = $separator . "{\"label\":\"" . $region->realname . "\",\"value\":\"" . $region->ID . "\"}";

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
        $regionDO = new NatGasPricingTable;
        $regionList = $regionDO::get()->filter(array('ID' => $request['Suburb']))->toNestedArray();



        $source = array();
        return json_encode($regionList);
    }




}