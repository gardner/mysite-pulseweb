<?php

//require_once BASE_PATH . '/silverstripe-pdf/thirdparty/snappy-master/src/autoload.php';
use Knp\Snappy\Pdf;


/**
 * FoundationFormPage
 * Displays a Form with various Fields to see its Foundation appearance
 *
 * @author Martijn van Nieuwenhoven <info@axyrmedia.nl>
 * @package foundation_forms
 */
class DirectDebitFormPage extends Page {

    private static $db = array();

    private static $has_one = array();


}

class DirectDebitFormPage_Controller extends Page_Controller {

    private static $allowed_actions = array(
            'DirectDebitForm', 'Submitted', 'getBankData', 'getBankBranch', 'getBank', 'SpecialCombined'
    );

    public function init() {
        parent::init();

        $this->response->addHeader('X-Frame-Options', 'SAMEORIGIN');

        Requirements::javascript('mysite/js/vendor/parsley/parsley2.2.0.min.js');
        Requirements::javascript('mysite/js/DirectDebitPage.js');
        Requirements::customScript(<<<JS

JS
        );

    }

    public function SpecialCombined() {
        $theme = THEMES_PATH . '/' . SSViewer::current_custom_theme();
        return file_get_contents($theme . '/css/normalize.css') . file_get_contents($theme . '/css/app.css') . file_get_contents($theme . '/css/layout-pdf.css');

    }


    /*This is a recommended option to secure any controller which displays
    or submits sensitive user input, and is enabled by default in all CMS controllers, as well as the login form see
    http://docs.silverstripe.org/framework/en/topics/security*/

    function DirectDebitForm() {

        $fields = new FieldList(

                LiteralField::create('your_account', '<div class="titlepanel wide bg green"><h2>Your Energy Account Details</h2></div><div>&nbsp;</div>'),
                //LiteralField::create('errors', '<div id="errors"></div>'),
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                TextField::create('Name', 'Name/Business Name')
                                        ->setAttribute('data-parsley-required', 'true')
                                        ->setAttribute('data-parsley-required-message', 'Please enter your Pulse Account Name')
                        // ->setAttribute('data-parsley-errors-container', '#errors')
                        )->addColumnClass('large-6'),
                        ColumnFieldGroup::create(
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                NumericField::create('ConsumerNumber', 'Consumer Number')
                                                        ->setMaxLength(10)
                                                        //->setCustomValidationMessage('Your 10 Digit Consumer Number')
                                                        ->setAttribute('placeholder', 'Your 10 Digit Consumer Number')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-type', 'integer')
                                                        ->setAttribute('data-parsley-type-message', 'This should just be number')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter your 10 digit Consumer Number')
                                                        ->setAttribute('data-parsley-minlength', '10')
                                                        ->setAttribute('data-parsley-minlength-message', 'This number should be 10 digits long')
                                        )->addColumnClass("columns large-8 push-4")
                                )
                        // ->setAttribute('data-parsley-errors-container', '#errors')
                        //->setError('Your 10 Digit Consumer Number', 'text')
                        )->addColumnClass('large-5')
                ),
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Street', 'Residential/Business Address')
                                                        //  ->addExtraClass("columns large-10")
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter the Address of your Pulse account')
                                                        ->setAttribute('autocomplete', 'off')
                                        )->addColumnClass("columns large-10")
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Suburb', 'Suburb')
                                                        //       ->addExtraClass("columns large-8")
                                                        //        ->setAttribute('data-parsley-trigger', 'change') no longer needed. Global
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter the Address of your Pulse account')
                                        )->addColumnClass("columns large-8")
                                ),
                                RowFieldGroup::create(
                                        TextField::create('City', 'City/Region')
                                                ->addExtraClass("columns large-9")
                                                ->setAttribute('data-parsley-required', 'true')
                                                ->setAttribute('data-parsley-required-message', 'Please enter the name if your City or Region'),
                                        TextField::create('Postcode', 'Postcode')
                                                ->addExtraClass("columns large-3")
                                                ->setAttribute('data-parsley-required', 'true')
                                                ->setAttribute('data-parsley-required-message', 'Please enter your postcode')
                                )

                        )->addColumnClass('large-6 columns'),
                        ColumnFieldGroup::create(
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('PhoneDay', 'Daytime Phone Number')
                                        //->setAttribute('data-parsley-required', 'true')
                                        //->setAttribute('data-parsley-required-message', 'Please enter your day Phone Number')
                                        )->addColumnClass('large-10 columns push-2')
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('PhoneNight', 'Night Phone Number')
                                        //->setAttribute('data-parsley-required', 'true')
                                        //->setAttribute('data-parsley-required-message', 'Please enter your evening Phone Number')
                                        )->addColumnClass('large-10 columns push-2')
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Email', 'Email Address')
                                                        // ->addExtraClass('large-10 columns')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter your Email Address')
                                                        ->setAttribute('data-parsley-type', 'email')
                                                        ->setAttribute('data-parsley-type-message', 'This should be a valid Email Address')
                                        )->addColumnClass('large-10 columns push-2')
                                )
                        )->addColumnClass('large-5 columns')
                ),
                LiteralField::create('your_account', '<div class="titlepanel wide bg green"><h2>Your Bank Account Details</h2></div><div>&nbsp;</div>'),
                // HeaderField::create('bank_account', '2. Your Bank Account Details', 2)->addExtraClass('titlepanel wide bg green'),
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                TextField::create('NameOfAccount', 'Name of account to be debited')
                                        ->setAttribute('data-parsley-required', 'true')
                                        ->setAttribute('data-parsley-required-message', 'Please enter your Account Name')
                        )->addColumnClass('large-6 columns'),
                        ColumnFieldGroup::create( // right half
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('SignatoryName', 'Signatory name (if different)')

                                        )->addColumnClass('large-9 push-3')
                                )
                        )->addColumnClass('large-6 columns')
                ),
                //)->addExtraClass('large-12 columns'),
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                RowFieldGroup::create( // left half
                                        LabelField::create('bank_label', 'Account to be Debited')->addExtraClass('large-12 columns bold'),
                                        ColumnFieldGroup::create(
                                                NumericField::create('BankCodeNum', 'Bank')
                                                        ->setMaxLength(2)
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-type', 'integer')
                                                        ->setAttribute('data-parsley-type-message', 'This should just be a number')
                                                        ->setAttribute('data-parsley-min', '01')
                                                        ->setAttribute('data-parsley-min-message', 'This number should be greater than 00')
                                                        ->setAttribute('data-parsley-max', '99')
                                                        ->setAttribute('data-parsley-minlength', '2')
                                                        ->setAttribute('data-parsley-minlength-message', 'This number should be 2 digits long')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter your 2 digit Bank Code')
                                                        ->setAttribute('data-parsley-errors-container', '#accerrors')
                                                        ->setAttribute('data-parsley-trigger', 'keyup')
                                                        ->setAttribute('data-parsley-validation-threshold', '1')


                                        )->addColumnClass('small-2'),
                                        ColumnFieldGroup::create(
                                                NumericField::create('BankBranchNum', 'Branch')
                                                        ->setMaxLength(4)
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-type', 'integer')
                                                        ->setAttribute('data-parsley-type-message', 'This should just be a number')
                                                        ->setAttribute('data-parsley-min', '0001')
                                                        ->setAttribute('data-parsley-min-message', 'This number should be greater than 0000')
                                                        ->setAttribute('data-parsley-max', '9999')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter your 4 digit Bank Branch Number')
                                                        ->setAttribute('data-parsley-minlength', '4')
                                                        ->setAttribute('data-parsley-minlength-message', 'This number should be 4 digits long')
                                                        //->setAttribute('data-parsley-maxlength', '4')
                                                        ->setAttribute('data-parsley-errors-container', '#accerrors')
                                                        ->setAttribute('data-parsley-trigger', 'keyup')
                                                        ->setAttribute('data-parsley-validation-threshold', '3')
                                        )->addColumnClass('small-2'),
                                        ColumnFieldGroup::create(
                                                NumericField::create('BankAccountNum', 'Account')
                                                        ->setMaxLength(7)
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-type', 'integer')
                                                        ->setAttribute('data-parsley-type-message', 'This should just be a number')
                                                        ->setAttribute('data-parsley-min', '0000001')
                                                        ->setAttribute('data-parsley-min-message', 'This number should be greater than 0000000')
                                                        ->setAttribute('data-parsley-max', '9999999')
                                                        ->setAttribute('data-parsley-minlength', '7')
                                                        ->setAttribute('data-parsley-minlength-message', 'This number should be 7 digits long')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter your 7 digit Account Number')
                                                        ->setAttribute('data-parsley-errors-container', '#accerrors')
                                        )->addColumnClass('small-4'),
                                        ColumnFieldGroup::create(
                                                NumericField::create('BankAccountSuffix', 'Suffix')
                                                        ->setMaxLength(3)
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-type', 'integer')
                                                        ->setAttribute('data-parsley-type-message', 'This should just be a number')
                                                        ->setAttribute('data-parsley-min', '000')
                                                        ->setAttribute('data-parsley-max', '999')
                                                        ->setAttribute('data-parsley-minlength', '2')
                                                        ->setAttribute('data-parsley-minlength-message', 'This number should be a minimum of 2digits long')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter your 2 or 3 digit Account Suffix')
                                                        ->setAttribute('data-parsley-errors-container', '#accerrors')
                                        )->addColumnClass('small-2'),
                                        LiteralField::create('errors', '<div class="columns large-12" id="accerrors"></div>')
                                )
                        )->addColumnClass('large-6 columns')
                ),

                RowFieldGroup::create(
                        ColumnFieldGroup::create( // left half
                                HiddenField::create('DummyBankServerValidation','')
                        )->addColumnClass('large-6 columns')
                ),

                RowFieldGroup::create(
                        ColumnFieldGroup::create( // left half
                                RowFieldGroup::create(
                                        LabelField::create('bank_address_label', 'To the Manager')->addExtraClass('large-12 columns bold'),
                                        ColumnFieldGroup::create(
                                                TextField::create('BankName', 'Bank')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter your Bank Name eg. "ANZ"')
                                                        ->setAttribute('placeholder', 'The name of your Bank eg. "ANZ"')
                                        )->addColumnClass('large-12'),
                                        ColumnFieldGroup::create(
                                                TextField::create('BankBranchName', 'Branch')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('placeholder', 'Your Bank branch eg. "Timaru"')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter your Bank Branch')
                                        )->addColumnClass('large-12'),
                                        ColumnFieldGroup::create(
                                                TextField::create('BankAddress', 'Address of Bank')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('placeholder', 'Your Bank Street Address')
                                                        ->setAttribute('data-parsley-required-message', 'Please enter your the Address of your Bank')
                                        )->addColumnClass('large-12')
                                )
                        )->addColumnClass('large-6 columns'),

                        ColumnFieldGroup::create( // right half
                                RowFieldGroup::create(
                                        LiteralField::create('blank', '<div class="columns hide-for-small">&nbsp;</div>')
                                ),
                                RowFieldGroup::create(

                                        ColumnFieldGroup::create(
                                                NumericField::create('auth_code', 'Authorisation Code', '0227637')->setReadOnly(true)->addExtraClass('label-bold input-bold input-bigger')

                                        )->addColumnClass('large-5 push-7')
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                DateField::create('ApplicationDate', 'Application Date', date('j M Y', time()))
                                                        //->setReadOnly(true)
                                                        ->setConfig('dateformat', 'd MMM yyyy')
                                                        ->addExtraClass('wide label-bold input-bold input-bigger')

                                        //->setConfig('showcalendar', true)
                                        )->addColumnClass('large-4 push-7')
                                )
                        )->addColumnClass('large-6 columns')

                ),
                RowFieldGroup::create(
                                LiteralField::create('accept_application_front_spacer', '<div class="large-12 columns hide-for-small">&nbsp;</div>')
                ),

                RowFieldGroup::create(
                                //LiteralField::create('accept_application_front_spacer', '<div class="large-12 columns hidjje-for-small">&nbsp;jhkhkjhkjhkjhkjh</div>'),
                        LiteralField::create('Author', '<div class="titlepanel bg white wide large-centered radius" style="text-align: center">'),
                        CheckboxField::create('accept_application_confirm', 'I/We authorise Pulse Energy Alliance LP (hereinafter referred to as the Initiator), using Authorisation Code 0227637, until further notice to debit my/our account with all amounts the Initiator may initiate by Direct Debit.')
                                ->setAttribute('data-parsley-required', 'true')
                                ->setAttribute('data-parsley-required-message', 'You must check this box to proceed'),
                        LiteralField::create('Author2', '</div>')

//                        <p style="margin-bottom:0px">(hereinafter referred to as the Initiator), using Authorisation Code 0227637, until further notice to debit my/our account with all amounts the Initiator may initiate by Direct Debit.</p></div>
//                )->addExtraClass('large-9 columns large-centered')

                ),
                RowFieldGroup::create(
                                LiteralField::create('accept_application_front_spacer', '<div class="large-12 columns hide-for-small">&nbsp;</div>')
                ),
                RowFieldGroup::create(
                         LabelField::create('info_to_appear', 'Information which will appear on your Bank Statement')->addExtraClass('large-12 columns bold'),
                        LabelField::create('info_to_appear_more', 'For your information only. These particulars are fixed.')->addExtraClass('large-12 columns'),
                        LiteralField::create('info_to_appear_spacer', '<div class="large-12 columns hide-for-small">&nbsp;</div>')

                // HeaderField::create('info_to_appear', 'INFORMATION WHICH WILL APPEAR ON YOUR BANK STATEMENT', 3)->addExtraClass('large-12 columns')
                ),
                RowFieldGroup::create(
                        LiteralField::create('payer_particulars_label', '<div class="large-2 columns"><label for="payer_particulars">Payer Particulars</div>'),
                        ColumnFieldGroup::create(
                                TextField::create('payer_particulars', '', 'PULSE ENERGY')->setReadOnly(true)
                        )->addColumnClass('large-5 columns pull-5')
                ),
                RowFieldGroup::create(
                        LiteralField::create('payer_code_label', '<div class="large-2 columns"><label for="payer_code">Payer Code</div>'),
                        ColumnFieldGroup::create(
                                TextField::create('payer_code', '')->setAttribute('placeholder', 'We will insert a unique payment code here')->setReadOnly(true)
                        )->addColumnClass('large-5 columns pull-5')//,
                //  LiteralField::create('payer_code_desc', '<div class="large-4 pull-1 columns">We will insert a unique payment code here</div>')
                ),
                RowFieldGroup::create(
                        LiteralField::create('payer_ref_label', '<div class="large-2 columns"><label for="payer_ref">Payer Reference</label></div>'),
                        ColumnFieldGroup::create(
                                TextField::create('payer_ref', '')->setAttribute('placeholder', 'We will insert your Consumer Number here')->setReadOnly(true)
                        )->addColumnClass('large-5 columns pull-5')//,
                //  LiteralField::create('payer_reference_desc', '<div class="large-4 pull-1 columns">We will insert your Customer Number here</div>')
                ),

               RowFieldGroup::create(
                        LiteralField::create('accept_authority_front_spacer', '<div class="large-12 columns hide-for-small">&nbsp;</div>'),
                        LabelField::create('accept_authority_label', 'Conditions of this Authority')->addExtraClass('large-12 columns bold')->addExtraClass('large-12 columns bold'),
                        // LiteralField::create('accept_authority_spacer', '<div class="large-12 columns hide-for-small">&nbsp;</div>'),

                        ColumnFieldGroup::create(
                                CheckboxField::create('accept_authority_confirm', 'I confirm that I have the sole authority for the bank account submitted in this application')
                                        ->setAttribute('data-parsley-required', 'true')
                                        ->setAttribute('data-parsley-required-message', 'You must check this box to proceed'),
                                LiteralField::create('accept_authority_confirm_dualsig', '<div class="" style="margin-bottom:1em">If dual signatories are actually required, you cannot proceed with this paperless authority setup.
                                            A direct debit authority form is required to be signed by both signatories and returned. Please download and print the form <a style="color:#5A8C00;font-weight:bold;font-style:italic" target="_blank" href="/directdebitform">here</a> or contact us at 0800 785 733 to request a printed direct debit form and send it back to us at: <b>Pulse Energy, P.O. BOX 10044, Dominion Road, Auckland 1446.</b></div>'),
                                CheckboxField::create('accept_authority', 'I accept the Conditions of this Authority which can be read <a style="color:#5A8C00;font-weight:bold;font-style: italic" href="#" data-reveal-id="DDTC">here</a>')
                                        ->setAttribute('data-parsley-required', 'true')
                                        ->setAttribute('data-parsley-required-message', 'You must check this box to proceed')
                        //->addExtraClass('right inline')
                        )->addColumnClass('large-12 columns ')
                )
//                ,
//                RowFieldGroup::create(
//                        LiteralField::create('copy_me_front_spacer', '<div class="large-12 columns hide-for-small">&nbsp;</div>'),
//                        LabelField::create('copy_me_label', 'Email Copy')->addExtraClass('large-12 columns bold')->addExtraClass('large-12 columns bold'),
//                        LiteralField::create('copy_me_spacer', '<div class="large-12 columns hide-for-small">&nbsp;</div>'),
//
//                        ColumnFieldGroup::create(
//                                CheckboxField::create('copy_me', 'Send confirmation to my email address')
//                        //->addExtraClass('right inline')
//                        )->addColumnClass('large-7 columns pull-5')
//                )

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
        $required = array('Name',
                'ConsumerNumber',
                'ResidentialAddress',
            //'PhoneDay',
            // 'PhoneNight',
                'NameOfAccount',
                'BankCodeNum',
                'BankBranchNum',
                'BankAccountNum',
                'BankAccountSuffix',
                'BankName',
                'BankBranchName',
                'BankAddress',
                'ApplicationDate',
                'accept_application_confirm',
                'accept_authority_confirm',
                'accept_authority');


        $validator = new RequiredFields($required);

        $form = new FoundationForm($this, __FUNCTION__, $fields, $actions, $validator);

        $form->enableSpamProtection();
        //$form->setAttribute('data-parsley-validate', 'true');

        // load submitted data, and clear them from session
        if ($data = Session::get('FoundationForm' . $this->ID)) {
            $form->loadDataFrom($data);
            Session::clear('FoundationForm' . $this->ID);
        }
        return $form;
    }

    // submit the form and redirect back to the form
    function submitFoundationForm($data, $form) {
        if (isset($data['SecurityID'])) {
            unset($data['SecurityID']);
        }
        Session::start();
        Session::set('FoundationForm' . $this->ID, $data);

        Session::clear('confirmationpdf');
        $directDebitEmail = Config::inst()->get('DirectDebitForm', 'directdebitemail');
        // At this point, RequiredFields->validate() will have been called already,
        // so we can assume that the values exist.
        // Make sure bank codes are valid because I cant inject final fail into parsley.

        $results = DataList::create('BankBranchRegister')->filter(array('Branch_Number' => $data['BankBranchNum']))->limit(1);

        if($results->count()<1) {
            $form->addErrorMessage('DummyBankServerValidation', 'Your Branch Number does not appear to be a valid one', 'bad');
            Session::set('FormInfo.' . $form->FormName() . '.data', $data);
            return $this->redirectBack();
        }
        else if ($data['BankCodeNum'] != $results->first()->Bank_Number){
            $form->addErrorMessage('DummyBankServerValidation', 'The Bank Branch Number does not go with this Bank Number', 'bad');
            Session::set('FormInfo.' . $form->FormName() . '.data', $data);

        }

        $submission = new DirectDebitFormSubmission();
        $data_toSave = $form->getData();

        $form->saveInto($submission);
        $submission->setField('BankAccountNum', 0000000);
        $record_number = $submission->write();
//this is a sweat. Thanks PHP versus Zend....
        $applicationDate = DateTime::createFromFormat('Y-m-d', $data_toSave['ApplicationDate']);
        $data_toSave['ApplicationDate'] = $applicationDate->format('j M Y');

//PDF Generation
        $data_toSave['CombinedCSS'] = $this->SpecialCombined();
        $data_toSave['SiteConfig'] = SiteConfig::current_site_config();
        $data_toSave['NoRevealModal'] = true; // to
        //var_dump($data_toSave);
        $viewData = new ArrayData($data_toSave);


        $viewer = new SSViewer('DirectDebitPDF');
        // Config::inst()->update('SSViewer', 'theme', 'pulse');
        $viewer->includeRequirements(false);
        $fullOut = $viewer->process($viewData);
        $fullOut = HTTP::absoluteURLs($fullOut);

        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');

        $snappyOptions = array(
                'disable-javascript' => true,
                'no-background' => false,
                'toc' => false,
                'dpi' => 120,
                'margin-left' => 0,
                'margin-right' => 0,
                'margin-top' => 5,
                'margin-bottom' => 0
        );
        $fileName = 'dd' . $record_number . '-' . $data_toSave['ConsumerNumber'] . '-' . uniqid() . '.pdf';
        $filePath = BASE_PATH . '/assets/generatedPDF/' . $fileName;
        file_put_contents(ASSETS_PATH . '/myXmlFile-' . $record_number . '.html', $fullOut);

        $snappy->generateFromHtml($fullOut, $filePath, $snappyOptions, true);
        //$customerEmail->attachFile($fileName, 'dd-' . $data_toSave['ConsumerNumber'] . '.pdf', 'application/pdf');
        //unlink(ASSETS_PATH . '/myXmlFile-'.$record_number.'.html');

        $adminEmailBody = "<style type=\"text/css\">table, h1, p{font-family:arial, helevetica, sans-serif}</style>"
                . "<h1>Pulse Energy Direct Debit Form Submission " . $record_number . "</h1>"
                . "<p>To download the PDF version please click <a href=\"https://" . $_SERVER['HTTP_HOST'] . "/assets/generatedPDF/" . $fileName . "\">here</a></p>"
                . "<table cellpadding=\"5\" border=\"1\">"
                . "<tr><td><b>Item</b></td><td><b>Value</b></td></tr>"

                . "<tr><td>Name</td><td>" . $data_toSave['Name'] . "</td></tr>"
                . "<tr><td>Signatory Name</td><td>" . $data_toSave['SignatoryName'] . "</td></tr>"
                . "<tr><td>Consumer Number</td><td>" . $data_toSave['ConsumerNumber'] . "</td></tr>"
                . "<tr><td>Residential Address</td><td>" . $data_toSave['Street'] . "</td></tr>"
                . "<tr><td>Residential Address</td><td>" . $data_toSave['Suburb'] . "</td></tr>"
                . "<tr><td>Residential Address</td><td>" . $data_toSave['City'] . "" . $data_toSave['Postcode'] . "</td></tr>"
                . "<tr><td>Phone Day</td><td>" . $data_toSave['PhoneDay'] . "</td></tr>"
                . "<tr><td>Phone Night</td><td>" . $data_toSave['PhoneNight'] . "</td></tr>"
                . "<tr><td>Email Address</td><td>" . $data_toSave['Email'] . "</td></tr>"
                . "<tr><td>Name of Bank Account</td><td>" . $data_toSave['NameOfAccount'] . "</td></tr>"
                . "<tr><td>Bank Code</td><td>" . $data_toSave['BankCodeNum'] . "</td></tr>"
                . "<tr><td>Bank Branch</td><td>" . $data_toSave['BankBranchNum'] . "</td></tr>"
                . "<tr><td>Bank Account</td><td>" . $data_toSave['BankAccountNum'] . "</td></tr>"
                . "<tr><td>Bank Account Suffix</td><td>" . $data_toSave['BankAccountSuffix'] . "</td></tr>"
                . "<tr><td>Bank Brand Name</td><td>" . $data_toSave['BankName'] . "</td></tr>"
                . "<tr><td>Bank Branch Name</td><td>" . $data_toSave['BankBranchName'] . "</td></tr>"
                . "<tr><td>Bank Address</td><td>" . $data_toSave['BankAddress'] . "</td></tr>"
                . "<tr><td>Date</td><td>" . $data_toSave['ApplicationDate'] . "</td></tr>"
                . "</table>";

        //var_dump($adminEmailBody);

        $adminEmail = new Email('signup@pulseenergy.co.nz', $directDebitEmail, 'Pulse Energy DD Form ' . $record_number . ' Consumer ' . $data_toSave['ConsumerNumber'], $adminEmailBody);
        $adminEmail->setCc('julian.warren@pulseenergy.co.nz');
        //$adminEmail->attachFile($filePath, $fileName);
        $adminEmail->send();
//       if ($data_toSave['copy_me'] == 1) {
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
                . "<p>Thank you for submitting your Direct Debit Form online.</p>"
                . "<p>If you wish to download a prinitablr PDF copy of your Direct Debit application, please click <a href=\"https://" . $_SERVER['HTTP_HOST'] . "/assets/generatedPDF/" . $fileName . "\">here</a></p>"
                . "<p>If you have any questions about your Direct Debit form you can contact our Customer Service Team on 0800 785 733.</p>"
                . "<p>Kind regards,</p>"
                . "<p>&nbsp;</p>"
                . "<p>Pulse Energy Customer Service</p>";


        if (!empty($data_toSave['Email'])) {
            $customerEmail = new Email('customer.service@pulseenergy.co.nz', $data_toSave['Email'], 'Online Direct Debit Form');
            $customerEmail->setTemplate('DDConf');
            $customerEmailArray = array(
                    'CustomerName' => $data_toSave['Name'],
                    'PDFLink' => $_SERVER['HTTP_HOST'] . "/assets/generatedPDF/" . $fileName,
            );
            $customerEmail->populateTemplate($customerEmailArray);
            $customerEmail->send();
            //file_put_contents(ASSETS_PATH . '/myXmlFile.html', $fullOut);
        }

        //}
        Session::set('Confirmationpdf', $fileName);
//        var_dump(Session::get_all());
        return $this->redirect($this->Link() . 'Submitted');
    }


    /**
     * This action handles rendering the "finished" message, which is
     * customizable by editing the FormSubmission template.
     *
     * @return ViewableData
     */
    public function Submitted() {
        //var_dump(Session::get('Confirmationpdf'));
        $d = Session::get('Confirmationpdf');
        if (!empty($d)) {
            $pdfLink = '/assets/generatedPDF/' . Session::get('Confirmationpdf');
            //var_dump(BASE_PATH.$pdfLink);
            if (!(file_exists(BASE_PATH . $pdfLink))) $pdfLink = false; // dont display the link if for some reason the file isnt there.
        }
        else {
            $pdfLink = false;
        }
        $pdfArray = array('PDFLink' => $pdfLink, 'Content' => $this->Content);

        $viewData = new ArrayData($pdfArray);

        $viewer = new SSViewer('DDFormSubmission');
        $viewer->includeRequirements(false);
        $content = $viewer->process($viewData);
        //$content = HTTP::absoluteURLs($content);

        //$content = $this->renderWith('FormSubmission');

        Session::clear('Confirmationpdf');
        return $this->customise(array(
                'Content' => $content,
                'Title' => ''
        ))->renderWith('Page');

    }

    public function getBankBranch() {
        $request = $this->getRequest();

        $decrequest = json_decode($request->getVar("BankBranchNum"), true);
        $decrequest = $request->getVar("BankBranchNum");

//        // results array initialisation

        $results = DataList::create('BankBranchRegister')->filter(array('Branch_Number' => $decrequest));

        //$branchArray = array();
        foreach ($results as $result) {
            //ChromePhp::log($result);
            $branch = (object)array(
                    'Bank_Number' => $result->Bank_Number,
                    'Branch_Number' => $result->Branch_Number,
                    'Bank_Name' => $result->Bank_Name,
                    'City' => $result->City,
                    'Physical_Address1' => $result->Physical_Address1,
                    'Physical_Address2' => $result->Physical_Address2,
                    'Physical_Address3' => $result->Physical_Address3,
                    'Physical_Address4' => $result->Physical_Address4
            );
        }
        print $result_st = Convert::array2json($branch);
    }

    public function getBank() {
        $request = $this->getRequest();
        //ChromePhp::log($request);
        //
        $decrequest = json_decode($request->getVar("BankCodeNum"), true);
        $decrequest = $request->getVar("BankCodeNum");
        //ChromePhp::log($decrequest);
        //        // results array initialisation

        $results = DataList::create('BankBranchRegister')->filter(array('Bank_Number' => $decrequest))->limit(1);

        //$branchArray = array();
        foreach ($results as $result) {
            //ChromePhp::log($result);
            $branch = (object)array(
                    'Bank_Number' => $result->Bank_Number,
                    'Branch_Number' => $result->Branch_Number,
                    'Bank_Name' => $result->Bank_Name,
                    'City' => $result->City,
                    'Physical_Address1' => $result->Physical_Address1,
                    'Physical_Address2' => $result->Physical_Address2,
                    'Physical_Address3' => $result->Physical_Address3,
                    'Physical_Address4' => $result->Physical_Address4
            );
        }
        print $result_st = Convert::array2json($branch);
    }



}