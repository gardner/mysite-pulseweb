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
            'Campaign' => 'Varchar(64)',
//            'DontShowModal' => 'Boolean',
            'ShowModal'=> 'Boolean',
            'ModalTemplate'=>'Varchar(64)'
    );

    private static $defaults = array(
            'Campaign' => 'none',
            'ShowModal' => '1',
            'ModalTemplate'=>'SpeakToTeamForm'
);

    private static $has_one = array(
            'DropInLink' => 'SiteTree',
            'DropInImage' => 'Image',
            'PageIcon' => 'Image'
    );

    private static $has_many = array(
            'SlideImage' => 'SlideImage',
    );

    private static $casting = array(
            'RequestCallHandler' => 'HTMLText'
    );

    public static function RequestCallHandler($arguments, $content = null, $parser = null, $tagName)
    {
        $current = Controller::curr();
        if (($current->class != 'CMSPageEditController') && ($current->class != 'CMSPageSettingsController')) {
            $test = false;
            if(isset($arguments['template'])) {
                $template = $arguments['template'];
                $test = SSViewer::hasTemplate($template);
            }
            //reset template from stock HeroForm to whatever you want after testing it exists above

            if ($test) {
                return $current->RequestCallForm()->setTemplate($template)->forTemplate();
            } else {
                return $current->RequestCallForm()->forTemplate();
            }
        }
    }

    function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $excerptfield = new HtmlEditorField('MenuExcerpt', 'Menu Excerpt', '');
        $excerptfield->setRows(5);
        $excerptfield->setColumns(15);
 //       $dontshowmodal = new CheckboxField('DontShowModal', 'Dont show modal on pages where the form is already in use');
 //       $fields->addFieldToTab('Root.Main', $dontshowmodal, 'Content');
        $showmodal = new CheckboxField('ShowModal', 'Show modal nagware');
        $fields->addFieldToTab('Root.Main', $showmodal, 'Content');
        $modaltemplate = new TextField('ModalTemplate', 'Modal nagware template name');
        $fields->addFieldToTab('Root.Main', $modaltemplate, 'Content');
        $campaignfield = new TextareaField('Campaign', 'Campaign');
        $campaignfield->setRows(1)->setColumns(30);
        $fields->addFieldToTab('Root.Main', $campaignfield, 'Content');
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
                        $theme.'/css/responsive-tables.css',
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
        Requirements::javascript($rootTheme.'/javascript/vendor/parsley/dist/parsley.min.js');
        // respond to form submission
        $url = Director::absoluteURL($this->Link().'RequestCallForm?isAjax=1');

        Requirements::customScript(<<<JS
(function ($) {
    $(document).ready(function () {
        formObj = $("#FoundationForm_RequestCallForm");
        formObj.submit(function (e) {
            //$(this).click(function (e) {
            e.preventDefault();
            formData = $(this).serialize() + "&action_submitRequestCallForm=Submit";
            //var myjson_data = JSON.stringify(($(this).serializeArray()), null, 0);
            if (!formObj.parsley().isValid()) return;

            $.ajax({
                url: '$url',
                type: 'POST',
                dataType: 'html',
                data: formData,
                success: function(data, status, jqXHR){
                //$('#RequestACallbackAjax').hide(400);
                $('#RequestCallBackAjax').html("<h3 class=\"green-text\">Thanks for your message.</h3><p>One of our team will be in contact with you soon</p>");
                var windowheight = $('#SpeakToTeamForm').height();
                $('#SpeakToTeamForm').css('height',windowheight)
                formObj.hide(400);
                setTimeout(function () {
                    $("#SpeakToTeamForm").foundation('reveal', 'close');
                    formFilled = true;
                }, 2500);
                //$('#RequestACallbackAjax').show(400);
                },
                beforeSend: function () {
                    //addressInput.closest('.section').after();
                    console.log("REQUEST IS SENT");
                }
            }).done(function (data) {
                var result = data.result
            });
            // });
        });

        $("#FoundationForm_RequestCallForm").parsley();
    });
})(jQuery);

JS
        );


    }

    private static $allowed_actions = array(
            'index',
            'finished',
            'submitRequestCallForm',
            'Submitted',
            'AjaxRequestCallSubmit',
            'RequestCallForm'

    );

    public function RequestCallForm()
    {
        $fields = new FieldList(
                RowFieldGroup::create(
                        ColumnFieldGroup::create(
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Name', 'Name')
                                                        ->setAttribute('placeholder', 'Your Name')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter your Name')
                                        // ->setAttribute('data-parsley-errors-container', '#errors')
                                        )->addColumnClass('columns large-12 ')
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextField::create('Phone', 'Phone Number')
                                                        ->setAttribute('placeholder', 'Phone Number')
                                                        ->setAttribute('data-parsley-required', 'true')
                                                        ->setAttribute('data-parsley-required-message',
                                                                'Please enter Phone Number')
                                        )->addColumnClass('large-12 columns ')
                                ),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                TextareaField::create('Comments', 'Comments')
                                                        ->setAttribute('placeholder', 'Comments')
                                                        ->setRows(2)
                                        //->setAttribute('data-parsley-required', 'true')
                                        //->setAttribute('data-parsley-required-message', 'Please enter your Phone Number')
                                        )->addColumnClass('large-12 columns ')
                                ),
//                                RowFieldGroup::create(
//                                        ColumnFieldGroup::create(
//                                                FileAttachmentField::create('Files', 'Files')
//                                                        ->setMultiple(true)
//                                                        ->setFolderName('Uploads/bmbattachments/'.md5(session_id() + 'abcdef'))
//                                                        ->setSmallFieldHolderTemplate('FileAttachmentField_holder')
//                                        )->addColumnClass('large-12'),
                                RowFieldGroup::create(
                                        ColumnFieldGroup::create(
                                                OptionsetField::create('NewCurrent',
                                                        'Who would you like to speak to?',
                                                        array('New' => 'Sales Team', 'Current' => 'Customer Care Team'),
                                                        'New')
                                        )->addColumnClass('large-12 columns optionsetcontainer')
                                ),
                                HiddenField::create('PageTitle', 'PageTitle', $this->Title),
                                HiddenField::create('Campaign', 'Campaign', $this->Campaign)
                        )->addColumnClass('large-12 columns')
                )


        );

        $actions = new FieldList(
                new FormAction('submitRequestCallForm', 'Send Request')
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
                'Email',
                'Phone'
        );


        $validator = new RequiredFields($required);

        $form = new FoundationForm(Controller::curr(), __FUNCTION__, $fields, $actions, $validator);

        $form->enableSpamProtection();
        $form->setAttribute('data-parsley-validate', 'true');
        $form->addExtraClass('requestcallform');

        // load submitted data, and clear them from session
        if ($data = Session::get('FoundationForm'.$this->ID)) {
            $form->loadDataFrom($data);
            Session::clear('FoundationForm'.$this->ID);
        }
        $template = $this->ModalTemplate;
        $test = SSViewer::hasTemplate($template);
        if($this->ShowModal && $test){
            $form->setTemplate($template);
        }
        else{
            $form->setTemplate('HeroForm');
        }
        return $form;
    }


    // submit the form and redirect back to the form
    function submitRequestCallForm($data, $form)
    {
        if (isset($data['SecurityID'])) {
            unset($data['SecurityID']);
        }
        Session::start();
        Session::set('FoundationForm'.$this->ID, $data);
        // At this point, RequiredFields->validate() will have been called already,
        $submission = new RequestCallback();
        $data_toSave = $form->getData();
        $form->saveInto($submission);
        $record_number = $submission->write();
        $adminEmailBody = "<style type=\"text/css\">table, h1, p{font-family:arial, helevetica, sans-serif}</style>"
                ."<h1>Pulse Request a Callback ".$record_number."</h1>"
                ."<table cellpadding=\"5\" border=\"1\">"
                ."<tr><td><b>Item</b></td><td><b>Value</b></td></tr>"
                ."<tr><td>Name</td><td>".$data_toSave['Name']."</td></tr>"
                ."<tr><td>Phone Number</td><td>".$data_toSave['Phone']."</td></tr>"
                ."<tr><td>New or Current</td><td>".$data_toSave['NewCurrent']."</td></tr>"
                ."<tr><td>Comments</td><td>".$data_toSave['Comments']."</td></tr>"
                ."<tr><td>Page Title</td><td>".$data_toSave['PageTitle']."</td></tr>"
                ."<tr><td>Campaign</td><td>".$data_toSave['Campaign']."</td></tr>"
                ."<tr><td>Files</td><td>";
        if (is_array($data_toSave['Files'])) {
            $count = 1;
            foreach ($data_toSave['Files'] as $filenumber) {
                $myFile = File::get()->filter(array('ID' => $filenumber))->limit(1);
                $adminEmailBody .= "<a href=\"".$myFile->first()->getAbsoluteURL()."\">Download ".$count."</a><br>";
                $count++;
            }
        }
        $adminEmailBody .= "</td></tr></table>";

        if ($data_toSave['NewCurrent'] == 'Current') {
            $adminAddress = Config::inst()->get('RequestCallBack', 'requestacallbackemailcurrent');
        } else {
            $adminAddress = Config::inst()->get('RequestCallBack', 'requestacallbackemailnew');
        }

        //$adminAddress = Config::inst()->get('RequestCallBack', 'requestacallbackemail');
        $adminEmail = new Email('website@pulseenergy.co.nz', $adminAddress,
                'Request a Callback '.$record_number, $adminEmailBody);
        $adminEmail->setCc('julian.warren@pulseenergy.co.nz');
        //$adminEmail->setBcc('aimee.preest@pulseenergy.co.nz');
        //$adminEmail->attachFile($filePath, $fileName);
        ChromePhp::log('Email present');
        ChromePhp::log($adminAddress);
        $adminEmail->send();
        ChromePhp::log('Email sent');
        if (Director::is_ajax()) {

            Session::clear('FoundationForm'.$this->ID);

            return "success";
        } else {
            return $this->redirect($this->Link().'Submitted');
        }
    }


    /**
     * This action handles rendering the "finished" message, which is
     * customizable by editing the ReceivedFormSubmission template.
     *
     * @return ViewableData
     */
    public function finished()
    {
        $submission = Session::get('userformssubmission'.$this->ID);

        if ($submission) {
            $submission = SubmittedForm::get()->byId($submission);
        }

        $referrer = isset($_GET['referrer']) ? urldecode($_GET['referrer']) : null;

        if (!$this->DisableAuthenicatedFinishAction) {
            $formProcessed = Session::get('FormProcessed');

            if (!isset($formProcessed)) {
                return $this->redirect($this->Link().$referrer);
            } else {
                $securityID = Session::get('SecurityID');
                // make sure the session matches the SecurityID and is not left over from another form
                if ($formProcessed != $securityID) {
                    // they may have disabled tokens on the form
                    $securityID = md5(Session::get('FormProcessedNum'));
                    if ($formProcessed != $securityID) {
                        return $this->redirect($this->Link().$referrer);
    }
                }
            }

            Session::clear('FormProcessed');
        }

        if ($this->LightBlueFormContent && $form = $this->HeroForm()) {
            $hasLocation = stristr($this->LightBlueFormContent, '$HeroForm');
            if ($hasLocation) {
                //var_dump($this->LightBlueFormContent);
                $content = preg_replace('/(<p[^>]*>)?\\$HeroForm(<\\/p>)?/i', $this->OnCompleteMessage,
                        $this->LightBlueFormContent);

                return array(
                        'LightBlueFormContent' => DBField::create_field('HTMLText', $content),
                        'Form' => ""
                );
            }
        } else {
            $me = $this->customise(array(
                    'LightBlueFormContent' => $this->customise(array(
                            'Submission' => $submission,
                            'Link' => $referrer
                    ))->renderWith('ReceivedFormSubmission'),
                    'Form' => '',
            ));
            return $me;
        }
    }


    function AjaxRequestCallSubmit($data, $form)
    {
        if (isset($data['SecurityID'])) {
            unset($data['SecurityID']);
        }
        Session::start();
        Session::set('FoundationForm'.$this->ID, $data);


        // At this point, RequiredFields->validate() will have been called already,

        $submission = new RequestCallBack();
        $data_toSave = $form->getData();


        //var_dump($data_toSave);
        $form->saveInto($submission);
        $record_number = $submission->write();
        $adminEmailBody = "<style type=\"text/css\">table, h1, p{font-family:arial, helevetica, sans-serif}</style>"
                ."<h1>Pulse Request a Callback ".$record_number."</h1>"
                ."<table cellpadding=\"5\" border=\"1\">"
                ."<tr><td><b>Item</b></td><td><b>Value</b></td></tr>"
                ."<tr><td>Name</td><td>".$data_toSave['Name']."</td></tr>"
                ."<tr><td>Phone Number</td><td>".$data_toSave['Phone']."</td></tr>"
                ."<tr><td>Email Address</td><td>".$data_toSave['Email']."</td></tr>"
                ."<tr><td>Message</td><td>".$data_toSave['Message']."</td></tr>"
                ."<tr><td>Campaign</td><td>".$data_toSave['Campaign']."</td></tr>"
                ."<tr><td>Files</td><td>";
        if (is_array($data_toSave['Files'])) {
            $count = 1;
            var_dump($data_toSave['Files']);

            foreach ($data_toSave['Files'] as $filenumber) {
                var_dump($filenumber);
                $myFile = File::get()->filter(array('ID' => $filenumber))->limit(1);
                $adminEmailBody .= "<a href=\"".$myFile->first()->getAbsoluteURL()."\">Download ".$count."</a><br>";
                $count++;
            }
        }
        $adminEmailBody .= "</td></tr></table>";
        $adminAddress = Config::inst()->get('RequestCallBack', 'requestacallbackemail');
        $adminEmail = new Email('website@pulseenergy.co.nz', $adminAddress,
                'Request a Callback '.$record_number, $adminEmailBody);
        $adminEmail->setCc('julian.warren@pulseenergy.co.nz');
        // $adminEmail->setBcc('aimee.preest@pulseenergy.co.nz');
        //$adminEmail->attachFile($filePath, $fileName);
        $adminEmail->send();

//        var_dump(Session::get_all());
        return 'success';//$this->redirect($this->Link().'Submitted');
    }


    public function Business()
    {
        if ($this->PageType == 'Business') {
            return 'Business';
        }
    }

    function CanonicalURL()
    {
        //Director::protocolAndHost()
        return "<link rel=\"canonical\" href=\"".$this->Link()."\" />";
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

}


