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
class PromoSwitchPage extends Page
{

    private static $db = array(
            'Campaign' => 'Text',
            'EmailRequired' => 'Boolean',
            'AlreadyCustomerShow' => 'Boolean',
            'DontShowEmail' => 'Boolean'
    );

    private static $has_one = array();

    private static $has_many = array(
            'PromoEntrants' => 'PromoEntrant'
    );

    function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new TextField('Campaign', 'Campaign Name'), 'Content');
        $fields->addFieldToTab('Root.Main', new CheckboxField('EmailRequired', 'Email Required'), 'Campaign');
        $fields->addFieldToTab('Root.Main', new CheckboxField('DontShowEmail', 'Do Not Show Email'), 'EmailRequired');
        $fields->addFieldToTab('Root.Main', new CheckboxField('AlreadyCustomerShow', 'Show Already Customer Checkbox'),
                'Content');

        return $fields;
    }


}

class  PromoSwitchPage_Controller extends Page_Controller
{

    private static $allowed_actions = array(
            'PromoSwitchForm',
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

    function  PromoSwitchForm()
    {

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
                                        ->setAttribute('data-parsley-required',
                                                ($this->EmailRequired && !$this->DontShowEmail))
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
                HiddenField::create('User', 'User', Member::currentUser()->FirstName),
                HiddenField::create('Campaign', 'Campaign', $this->Campaign)
        );

        if (!$this->AlreadyCustomerShow) {
            Requirements::customCSS(<<<CSS
        #AlreadyCustomer{display:none;}
CSS
            );
        }

        if ($this->DontShowEmail) {
            Requirements::customCSS(<<<CSS
        #Email{display:none;}
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
                'Phone'
        );
        if ($this->EmailRequired && !$this->DontShowEmail) {
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

        $submission = new PromoEntrant();
        $data_toSave = $form->getData();

        $form->saveInto($submission);
        $record_number = $submission->write();


        $adminEmailBody = "<style type=\"text/css\">table, h1, p{font-family:arial, helevetica, sans-serif}</style>"
                ."<h1>Pulse Energy iPad Promotion Submission ".$record_number."</h1>"
                ."<table cellpadding=\"5\" border=\"1\">"
                ."<tr><td><b>Item</b></td><td><b>Value</b></td></tr>"
                ."<tr><td>Name</td><td>".$data_toSave['Name']."</td></tr>"
                ."<tr><td>Phone Day</td><td>".$data_toSave['Phone']."</td></tr>"
                ."<tr><td>Email Address</td><td>".$data_toSave['Email']."</td></tr>"
                ."<tr><td>Promo User</td><td>".$data_toSave['User']."</td></tr>"
                ."<tr><td>Already Customer</td><td>".$data_toSave['AlreadyCustomer']."</td></tr>"
                ."<tr><td>Campaign</td><td>".$this->Campaign."</td></tr>"
                ."</table>";


        $adminEmail = new Email('website@pulseenergy.co.nz', 'abandon.pulse@pulse.local',
                'Pulse Energy iPad Promo Form '.$record_number, $adminEmailBody);
        $adminEmail->setCc('julian.warren@pulseenergy.co.nz');
        //$adminEmail->attachFile($filePath, $fileName);
        $adminEmail->send();

//            $customerEmailBody = "<style type=\"text/css\">table, h1, h2{font-family:arial, helevetica, sans-serif}</style>"
//                    . "<h1>Confirmation of Pulse Energy Direct Debit Form</h1><h2>For Consumer Number " . $data_toSave['ConsumerNumber'] . "</h2>"
//                    . "<p>This is to confirm your submission of the online Pulse Energy Direct Debit form with the following data. We have also enclosed a PDF version should you wish to print this for your records.</p>"
//                    . "<table cellpadding=\"5\" border=\"1\">"
//                    . "<tr><td><b>Item</b></td><td><b>Value</b></td></tr>"
//                    . "<tr><td>Name</td><td>" . $data_toSave['Name'] . "</td></tr>"
//                    . "<tr><td>Consumer Number</td><td>" . $data_toSave['ConsumerNumber'] . "</td></tr>"
//                    . "<tr><td>Residential Address</td><td>" . $data_toSave['Street'] . "</td></tr>"
//                    . "<tr><td>Residential Address</td><td>" . $data_toSave['Suburb'] . "</td></tr>"
//                    . "<tr><td>Residential Address</td><td>" . $data_toSave['City'] . "" . $data_toSave['Postcode'] . "</td></tr>"
//                    . "<tr><td>Phone Day</td><td>" . $data_toSave['PhoneDay'] . "</td></tr>"
//                    . "<tr><td>Phone Night</td><td>" . $data_toSave['PhoneNight'] . "</td></tr>"
//                    . "<tr><td>Email Address</td><td>" . $data_toSave['Email'] . "</td></tr>"
//                    . "<tr><td>Name of Bank Account</td><td>" . $data_toSave['NameOfAccount'] . "</td></tr>"
//                    . "<tr><td>Bank Code</td><td>" . $data_toSave['BankCodeNum'] . "</td></tr>"
//                    . "<tr><td>Bank Branch</td><td>" . $data_toSave['BankBranchNum'] . "</td></tr>"
//                    . "<tr><td>Bank Account</td><td>" . $data_toSave['BankAccountNum'] . "</td></tr>"
//                    . "<tr><td>Bank Account Suffix</td><td>" . $data_toSave['BankAccountSuffix'] . "</td></tr>"
//                    . "<tr><td>Bank Brand Name</td><td>" . $data_toSave['BankName'] . "</td></tr>"
//                    . "<tr><td>Bank Branch Name</td><td>" . $data_toSave['BankBranchName'] . "</td></tr>"
//                    . "<tr><td>Bank Address</td><td>" . $data_toSave['BankAddress'] . "</td></tr>"
//                    . "<tr><td>Date</td><td>" . $data_toSave['ApplicationDate'] . "</td></tr>"
//                    . "</table>";

//        $customerEmailBody = "<style type=\"text/css\">table, h1, h2, p{font-family:arial, helevetica, sans-serif}</style>"
//                ."<p>Thank you for submitting your request for Consumption Information.</p>"
//                ."<p>We will now proceed to verify your identity according to the Privacy Act. This may take up to 20 business days"
//                ."<p>There will then be a period of up to 5 business days after we have validated your identity to provide the data in accordance with the Electricity Industry Participation Code 2010"
//                ."<p>Kind regards,</p>"
//                ."<p>&nbsp;</p>"
//                ."<p>Pulse Energy Customer Service</p>";

//        if (!empty($data_toSave['Email'])) {
//            $customerEmail = new Email('customer.service@pulseenergy.co.nz', $data_toSave['Email'],
//                    'Consumption Information Request');
//            $customerEmail->setCc('data.requests@pulseenergy.co.nz');
//            $customerEmail->setTemplate('ConsumptionInfoConfEmail');
//            $customerEmailArray = array(
//                    'CustomerName' => $data_toSave['Name'],
//                    'Body' => $customerEmailBody
//
//            );
//            $customerEmail->populateTemplate($customerEmailArray);
//            $customerEmail->send();
//            //file_put_contents(ASSETS_PATH . '/myXmlFile.html', $fullOut);
//        }

//        var_dump(Session::get_all());
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

        $contentArray = array(
                'Content' => $this->Content,
                'SlideImage' => $this->SlideImage(),
        );

        $viewData = new ArrayData($contentArray);

        $viewer = new SSViewer('PromoFormSubmission');
        $viewer->includeRequirements(false);
        $content = $viewer->process($viewData);

        //$content = HTTP::absoluteURLs($content);

        //$content = $this->renderWith('FormSubmission');

        return $this->customise(array(
                'Content' => $content,
                'Title' => ''
        ))->renderWith('PromoFormSubmissionPage');

    }

}