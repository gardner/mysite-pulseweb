<?php

//require_once BASE_PATH.'/silverstripe-pdf/thirdparty/snappy-master/src/autoload.php';
//use Knp\Snappy\Pdf;


/**
 * FoundationFormPage
 * Displays a Form with various Fields to see its Foundation appearance
 *
 * @author Martijn van Nieuwenhoven <info@axyrmedia.nl>
 * @package foundation_forms
 */
class PricePromisePage extends Page
{

    private static $db = array(
            'Campaign' => 'Text',
            'EmailRequired' => 'Boolean',
            'AlreadyCustomerShow' => 'Boolean'
    );

    private static $has_one = array();

    private static $has_many = array(
            'PromoEntrants' => 'PromoEntrant'
    );

    function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new TextField('Campaign', 'Campaign Name'), 'Content');
        $fields->addFieldToTab('Root.Main', new CheckboxField('EmailRequired', 'Email Required'), 'Content');
        $fields->addFieldToTab('Root.Main', new CheckboxField('AlreadyCustomerShow', 'Show Already Customer Checkbox'),
                'Content');

        return $fields;
    }


}

class  PricePromisePage_Controller extends Page_Controller
{

    private static $allowed_actions = array(
            'PricePromiseForm',
            'Submitted',
    );

    public function init()
    {
        parent::init();

        $this->response->addHeader('X-Frame-Options', 'SAMEORIGIN');

        Requirements::javascript(FOUNDATION_FORM_DIR.'/javascript/parsley/parsley2.2.0.min.js');
        Requirements::javascript('mysite/js/PromoSwitchPage.js');
        Requirements::customScript(<<<JS

JS
        );

        Requirements::themedCSS('promoswitchpage');

    }


    /*This is a recommended option to secure any controller which displays
    or submits sensitive user input, and is enabled by default in all CMS controllers, as well as the login form see
    http://docs.silverstripe.org/framework/en/topics/security*/

    function  PricePromiseForm()
    {

        $member = Member::currentUser();
        $name = ($member)? $member->FirstName : 'Nobody';
        $fields = new FieldList(

                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                TextField::create('Name', 'Name')
                                        ->setAttribute('data-parsley-required', 'true')
                                        ->setAttribute('data-parsley-required-message',
                                                'Please enter your  Name')
                        // ->setAttribute('data-parsley-errors-container', '#errors')
                        )->addColumnClass('columns small-9 ')
                ),
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                TextField::create('PriceData', 'Pricing Data')
                                        ->setAttribute('data-parsley-required', 'true')
                                        ->setAttribute('data-parsley-required-message',
                                                'Please enter the pricing data')
                        )->addColumnClass('small-9 columns')
                ),

                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                NumericField::create('Phone', 'Phone Number')
                                        ->setAttribute('data-parsley-required', 'true')
                                        ->setAttribute('data-parsley-required-message',
                                                'Please enter your Phone Number')
                        )->addColumnClass('small-9 columns')
                ),
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                EmailField::create('Email', 'Email Address')
                                        // ->addExtraClass('large-10 columns')
                                        ->setAttribute('data-parsley-required', $this->EmailRequired)
                                        ->setAttribute('data-parsley-required-message',
                                                'Please enter your Email Address')
                                        ->setAttribute('data-parsley-type', 'email')
                                        ->setAttribute('data-parsley-type-message',
                                                'This should be a valid Email Address')
                        )->addColumnClass('small-9 columns')
                ),
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                CheckboxField::create('give_permissiom',
                                        'I give permission for Pulse Energy to contact me.')
                                        ->setAttribute('data-parsley-required', 'true')
                                        ->setAttribute('data-parsley-required-message',
                                                'You must accept this condition')
                                        ->setValue('1')
                        //->addExtraClass('right inline')
                        )->addColumnClass('small-9 columns')

                ),
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                CheckboxField::create('AlreadyCustomer',
                                        'I am already a Customer.')
                        //->addExtraClass('right inline')
                        )->addColumnClass('small-9 columns')

                ),
                HiddenField::create('User', 'User', $name),
                HiddenField::create('Campaign', 'Campaign', $this->Campaign)
        );

        if (!$this->AlreadyCustomerShow) {
            Requirements::customCSS(<<<CSS
        #AlreadyCustomer{display:none;}
CSS
               );


        }

        $actions = new FieldList(
                new FormAction('submitFoundationForm', 'Submit')
        );

        // set all to required to see the validation message appearance
//        $required = array();
//        if ($dataFields = $fields->dataFields()) {
//            foreach ($dataFields as $child) {
//                $required[] = $child->getName();
//            }
//        }
        $required = array(
                'Name',
                'Phone',
                'PricingData',
        );
        if ($this->EmailRequired) {
            $required[] = 'Email';
        }

        $validator = new RequiredFields($required);

        $form = new FoundationForm($this, __FUNCTION__, $fields, $actions, $validator);

        $form->enableSpamProtection();
        //$form->setAttribute('data-parsley-validate', 'true');

        // load submitted data, and clear them from session
        if ($data = Session::get('FoundationForm'.$this->ID)) {
            $form->loadDataFrom($data);
            Session::clear('FoundationForm'.$this->ID);
        }
        return $form;
    }

    // submit the form and redirect back to the form
    function submitFoundationForm($data, $form)
    {
        if (isset($data['SecurityID'])) {
            unset($data['SecurityID']);
        }
        //Session::start();
        //Session::set('FoundationForm'.$this->ID, $data);


        // At this point, RequiredFields->validate() will have been called already,

        $submission = new PricePromise();
        $data_toSave = $form->getData();

        $form->saveInto($submission);
        //var_dump($submission->getField('PriceData'));
        $array = preg_split("/[\t]/", $submission->getField('PriceData'));
        //var_dump(json_encode($array));
        var_dump($array[0]);
        $submission->update(array(
           'PriceData' =>  json_encode($array)
        ));
        $record_number = $submission->write();
        Session::set('record_number', $record_number);

        $adminEmailBody = "<style type=\"text/css\">table, h1, p{font-family:arial, helevetica, sans-serif}</style>"
                ."<h1>Pulse Energy iPad Promotion Submission ".$record_number."</h1>"
                ."<table cellpadding=\"5\" border=\"1\">"
                ."<tr><td><b>Item</b></td><td><b>Value</b></td></tr>"
                ."<tr><td>Name</td><td>".$data_toSave['Name']."</td></tr>"
                ."<tr><td>Phone Day</td><td>".$data_toSave['Phone']."</td></tr>"
                ."<tr><td>Email Address</td><td>".$data_toSave['Email']."</td></tr>"
                ."<tr><td>Already Customer</td><td>".$data_toSave['AlreadyCustomer']."</td></tr>"
                ."<tr><td>PDF Sent</td><td>".$array[0]."</td></tr>"
                ."<tr><td>Price Data</td><td>".$data_toSave['PriceData']."</td></tr>"
                ."<tr><td>Price Data JSON</td><td>".$submission->getField('PriceData')."</td></tr>"
                ."<tr><td>Campaign</td><td>".$this->Campaign."</td></tr>"
                ."<tr><td>Promo Operator Number</td><td>".$data_toSave['User']."</td></tr>"
                ."</table>";


        $adminEmail = new Email('website@pulseenergy.co.nz', 'julian.warren@pulseenergy.co.nz',
                'Pulse Energy iPad Price Promise Form '.$record_number, $adminEmailBody);
        $adminEmail->setCc('julian.warren@pulseenergy.co.nz');
        //$adminEmail->attachFile($filePath, $fileName);
        $adminEmail->send();



        if (!empty($data_toSave['Email'])) {
            $customerEmail = new Email('customer.care@pulseenergy.co.nz', $data_toSave['Email'],
                    'Price Promise Confirmation Request');
            $customerEmail->setTemplate('inlinePulseEnergyPricePromisePriceList');
            $customerEmailArray = array(
                    'PDFName' => $array[0].'.pdf',
            );
            $customerEmail->populateTemplate($customerEmailArray);
            $customerEmail->send();
            //file_put_contents(ASSETS_PATH . '/myXmlFile.html', $fullOut);
        }

        //var_dump($this->Link());//Session::get_all());
        return $this->redirect($this->Link().'Submitted');
    }


    /**
     * This action handles rendering the "finished" message, which is
     * customizable by editing the FormSubmission template.
     *
     * @return ViewableData
     */
    public function Submitted()
    {

        $record = Session::get('record_number');
        //Session::clear('record_number');
        $contentArray = array(
                'Content' => $this->Content,
                'SlideImage' => $this->SlideImage(),
                'Record'=> $record
        );

        $viewData = new ArrayData($contentArray);

        $viewer = new SSViewer('PricePromiseSubmission');
        $viewer->includeRequirements(false);
        $content = $viewer->process($viewData);

        //$content = HTTP::absoluteURLs($content);

        //$content = $this->renderWith('FormSubmission');

        return $this->customise(array(
                'Content' => $content,
                'Title' => ''
        ))->renderWith('PricePromiseSubmissionPage');

    }

}