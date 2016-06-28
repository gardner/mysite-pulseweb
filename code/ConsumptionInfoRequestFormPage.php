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
class ConsumptionInfoRequestFormPage extends Page
{

    private static $db = array();

    private static $has_one = array();


}

class ConsumptionInfoRequestFormPage_Controller extends Page_Controller
{

    private static $allowed_actions = array(
            'ConsumptionInfoRequestForm',
            'Submitted',
            'SpecialCombined'
    );

    public function init()
    {
        parent::init();

        $this->response->addHeader('X-Frame-Options', 'SAMEORIGIN');

        Requirements::javascript('mysite/js/vendor/parsley/parsley2.2.0.min.js');
        Requirements::javascript('mysite/js/ConsumptionInfoRequestPage.js');
        Requirements::customScript(<<<JS

JS
        );

    }


    /*This is a recommended option to secure any controller which displays
    or submits sensitive user input, and is enabled by default in all CMS controllers, as well as the login form see
    http://docs.silverstripe.org/framework/en/topics/security*/

    function ConsumptionInfoRequestForm()
    {

        $fields = new FieldList(

                LiteralField::create('your_account',
                        '<div class="titlepanel wide bg green"><h2>Your Energy Account Details</h2></div><div>&nbsp;</div>'),
                //LiteralField::create('errors', '<div id="errors"></div>'),


                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Name', 'Name/Business Name')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter your Pulse Account Name')
                                        // ->setAttribute('data-parsley-errors-container', '#errors')
                                        )->addColumnClass('columns large-8 ')
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('UnitNumber', 'Unit/Flat Number')
                                                        //  ->addExtraClass("columns large-10")
                                                        ->setAttribute('data-parsley-required', 'false')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter the Address of your Pulse account')
                                                        ->setAttribute('autocomplete', 'off')
                                        )->addColumnClass("columns large-5 small-5"),
                                        ColumnFieldGroup::create(
                                                TextField::create('StreetNumber', 'Street Number/Name')
                                                        //  ->addExtraClass("columns large-10")
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setMaxLength(6)
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter the Street Number or House Name of your Pulse account')
                                                        ->setAttribute('autocomplete', 'off')
                                        )->addColumnClass("columns large-5 small-5")
                                ),

                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Street', 'Street Name')
                                                        //  ->addExtraClass("columns large-10")
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter the Street Name of your Pulse account')
                                                        ->setAttribute('autocomplete', 'off')
                                        )->addColumnClass("columns large-10")
                                ),

                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Suburb', 'Suburb')
                                                        //       ->addExtraClass("columns large-8")
                                                        //        ->setAttribute('data-parsley-trigger', 'change') no longer needed. Global
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter the Suburb Name of your Pulse account')
                                        )->addColumnClass("columns large-8"),
                                        ColumnFieldGroup::create(
                                                TextField::create('POBox', 'PO Box/ RD')
                                        //       ->addExtraClass("columns large-8")
                                        //        ->setAttribute('data-parsley-trigger', 'change') no longer needed. Global
                                        //->setAttribute('data-parsley-required', 'true')
                                        //->setAttribute('data-parsley-required-message',
                                        //        'Please enter the Suburb Name of your Pulse account')
                                        )->addColumnClass("columns large-4")
                                ),
                                RowFieldGroup::create(
                                        TextField::create('City', 'City/Region')
                                                ->addExtraClass("columns large-9")
                                                ->setAttribute('data-parsley-required', 'true')
                                                ->setAttribute('data-parsley-required-message',
                                                        'Please enter the name if your City or Region'),
                                        TextField::create('Postcode', 'Postcode')
                                                ->addExtraClass("columns large-3")
                                                ->setAttribute('data-parsley-required', 'true')
                                                ->setAttribute('data-parsley-required-message',
                                                        'Please enter your postcode')
                                )

                        )->addColumnClass('large-6 columns'),
                        ColumnFieldGroup::create(
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                NumericField::create('ConsumerNumber', 'Consumer Number')
                                                        ->setMaxLength(10)
                                                        //->setCustomValidationMessage('Your 10 Digit Consumer Number')
                                                        ->setAttribute('placeholder', 'Your 10 Digit Consumer Number')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-type', 'integer')
                                                        ->setAttribute('data-parsley-type-message',
                                                                'This should just be number')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter your 10 digit Consumer Number')
                                                        ->setAttribute('data-parsley-minlength', '10')
                                                        ->setAttribute('data-parsley-minlength-message',
                                                                'This number should be 10 digits long')
                                        )->addColumnClass("columns large-8 push-4")
                                ),
                                RowFieldGroup::create(

                                        ColumnFieldGroup::create(
                                                TextField::create('ICPNumber', 'ICP Number')
                                                        ->setMaxLength(15)
                                                        //->setCustomValidationMessage('Your 10 Digit Consumer Number')
                                                        ->setAttribute('placeholder', 'Your 15 Character ICP Number')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        //->setAttribute('data-parsley-type', 'integer')
                                                        //->setAttribute('data-parsley-type-message', 'This should just be number')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter your 15 character ICP Number')
                                                        ->setAttribute('data-parsley-minlength', '15')
                                                        ->setAttribute('data-parsley-minlength-message',
                                                                'This number should be 15 characters long')
                                        )->addColumnClass("columns large-9 push-3")
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Phone', 'Phone Number')
                                        //->setAttribute('data-parsley-required', 'true')
                                        //->setAttribute('data-parsley-required-message', 'Please enter your evening Phone Number')
                                        )->addColumnClass('large-10 columns push-2')
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Email', 'Email Address')
                                                        // ->addExtraClass('large-10 columns')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter your Email Address')
                                                        ->setAttribute('data-parsley-type', 'email')
                                                        ->setAttribute('data-parsley-type-message',
                                                                'This should be a valid Email Address')
                                        )->addColumnClass('large-10 columns push-2')
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                DateField::create('ApplicationDate', 'Application Date',
                                                        date('j M Y', time()))
                                                        ->setReadOnly(true)
                                                        ->setConfig('dateformat', 'd MMM yyyy')
                                                        ->addExtraClass('wide label-boldx input-boldx input-biggerx')

                                        //->setConfig('showcalendar', true)
                                        )->addColumnClass('large-8 columns push-4')


                                )
                        )->addColumnClass('large-5 columns')
                ),


                RowFieldGroup::create(
                        LiteralField::create('accept_authority_front_spacer',
                                '<div class="large-12 columns hide-for-small">&nbsp;</div>'),
                        /*                         LabelField::create('accept_authority_label',
                                                        'Conditions of this Authority')->addExtraClass('large-12 columns bold')->addExtraClass('large-12 columns bold'),*/
                        // LiteralField::create('accept_authority_spacer', '<div class="large-12 columns hide-for-small">&nbsp;</div>'),

                        ColumnFieldGroup::create(
                                OptionsetField::create('accept_authority', 'Authority Declaration',
                                        Array(
                                                1 => "I am the account holder named above and I am authorised to request this information",
                                                2 => "I am an authorised person / agent"

                                        ),
                                        1)
                                        ->setAttribute('data-parsley-required', 'true')
                                        ->setAttribute('data-parsley-required-message',
                                                'You must check one of these boxes to proceed')
                        //->addExtraClass('right inline')
                        )->addColumnClass('large-9 columns pull-3')
                ),
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('ApplicantName', 'Applicant Name  ')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter your full name')
                                                ->addExtraClass('hidden')
                                        // ->setAttribute('data-parsley-errors-container', '#errors')
                                        )->addColumnClass('columns large-6 ')
                                )
                        )
                )
        );

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
                'ConsumerNumber',
                'StreetNumber',
                'Street',
                'Suburb',
                'City',
                'ICPNumber',
                'Phone',
                'mail'
        );


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
        Session::start();
        Session::set('FoundationForm'.$this->ID, $data);


        // At this point, RequiredFields->validate() will have been called already,

        $submission = new ConsumptionInfoRequestSubmission();
        $data_toSave = $form->getData();

        $uniqueID = uniqid();
        $data_toSave['UniqueID'] = $uniqueID;

        //var_dump($data_toSave);
        $form->saveInto($submission);
        $record_number = $submission->write();
//this is a sweat. Thanks PHP versus Zend....
        $applicationDate = DateTime::createFromFormat('Y-m-d', $data_toSave['ApplicationDate']);
        $data_toSave['ApplicationDate'] = $applicationDate->format('d/m/Y');
        $CSVBbody = "HDR,REQCONS,PUNZ,PUNZ,".$data_toSave['ApplicationDate'].",".$uniqueID.",1\r\n";
        $CSVBbody .= "DET,EIEP13B,EMAIL,,"
                .$data_toSave['ConsumerNumber'].","
                .$data_toSave['Name'].","
                .$data_toSave['ICPNumber'].","
                .$data_toSave['Email'].","
                .$data_toSave['UnitNumber'].","
                .$data_toSave['StreetNumber'].","
                .$data_toSave['Street'].","
                .$data_toSave['Suburb'].","
                .$data_toSave['POBox'].","
                .$data_toSave['City'].","
                .$data_toSave['Postcode'].",New Zealand\r\n";


        file_put_contents('/home/EIEP13/csv/PUNZ_E_PUNZ_EIEP13_'.$uniqueID.$record_number.'.csv', $CSVBbody);

//        unlink(ASSETS_PATH.'/PUNZ_E_PUNZ_EIEP13_'.$uniqueID.$record_number.'.csv');

        /*        $adminEmailBody = "<style type=\"text/css\">table, h1, p{font-family:arial, helevetica, sans-serif}</style>"
                        ."<h1>Pulse Energy Direct Debit Form Submission ".$record_number."</h1>"
                        ."<p>To download the PDF version please click <a href=\"https://".$_SERVER['HTTP_HOST']."/assets/generatedPDF/".$fileName."\">here</a></p>"
                        ."<table cellpadding=\"5\" border=\"1\">"
                        ."<tr><td><b>Item</b></td><td><b>Value</b></td></tr>"

                        ."<tr><td>Name</td><td>".$data_toSave['Name']."</td></tr>"
                        ."<tr><td>Consumer Number</td><td>".$data_toSave['ConsumerNumber']."</td></tr>"
                        ."<tr><td>Residential Address</td><td>".$data_toSave['Street']."</td></tr>"
                        ."<tr><td>Residential Address</td><td>".$data_toSave['Suburb']."</td></tr>"
                        ."<tr><td>Residential Address</td><td>".$data_toSave['City']."".$data_toSave['Postcode']."</td></tr>"
                        ."<tr><td>Phone Day</td><td>".$data_toSave['PhoneDay']."</td></tr>"

                        ."<tr><td>Email Address</td><td>".$data_toSave['Email']."</td></tr>"

                        ."<tr><td>Date</td><td>".$data_toSave['ApplicationDate']."</td></tr>"
                        ."</table>";

                //var_dump($adminEmailBody);

                $adminEmail = new Email('signup@pulseenergy.co.nz', $directDebitEmail,
                        'Pulse Energy DD Form '.$record_number.' Consumer '.$data_toSave['ConsumerNumber'], $adminEmailBody);
                $adminEmail->setCc('julian.warren@pulseenergy.co.nz');
                //$adminEmail->attachFile($filePath, $fileName);
                $adminEmail->send();*/

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


        $customerEmailBody = "<style type=\"text/css\">table, h1, h2, p{font-family:arial, helevetica, sans-serif}</style>"
                ."<p>Thank you for submitting your request for Consumption Information.</p>"
                ."<p>We will now proceed to verify your identity according to the Privacy Act. This may take up to 20 business days"
                ."<p>There will then be a period of up to 5 business days after we have validated your identity to provide the data in accordance with the Electricity Industry Participation Code 2010"
                ."<p>Kind regards,</p>"
                ."<p>&nbsp;</p>"
                ."<p>Pulse Energy Customer Service</p>";


        if (!empty($data_toSave['Email'])) {
            $customerEmail = new Email('customer.service@pulseenergy.co.nz', $data_toSave['Email'],
                    'Consumption Information Request');
            $customerEmail->setCc('data.requests@pulseenergy.co.nz');
            $customerEmail->setTemplate('ConsumptionInfoConfEmail');
            $customerEmailArray = array(
                    'CustomerName' => $data_toSave['Name'],
                    'Body' => $customerEmailBody

            );
            $customerEmail->populateTemplate($customerEmailArray);
            $customerEmail->send();
            //file_put_contents(ASSETS_PATH . '/myXmlFile.html', $fullOut);
        }

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

        $contentArray = array('Content' => $this->Content);

        $viewData = new ArrayData($contentArray);

        $viewer = new SSViewer('ConsumptionInfoFormSubmission');
        $viewer->includeRequirements(false);
        $content = $viewer->process($viewData);
        //$content = HTTP::absoluteURLs($content);

        //$content = $this->renderWith('FormSubmission');

        return $this->customise(array(
                'Content' => $content,
                'Title' => ''
        ))->renderWith('Page');

    }

}