<?php

/*For Userforms 4.x*/

class EditableMultiFileField extends EditableFormField
{
    private static $singular_name = 'Multi File Upload Field';

    private static $plural_name = 'Multi File Upload Fields';

    private static $db = array(
         'MultipleUploads' => 'Boolean',
         'Obfuscate' => 'Boolean',
    	);

    private static $has_one = array(
   		'Folder' => 'Folder', // From CustomFields
   	);

    public function Icon()
    {
        return 'userforms/images/editablefilefield.png';
    }

    public function getCMSFields() {
   		$fields = parent::getCMSFields();

   		$fields->addFieldToTab(
   			'Root.Main',
   			TreeDropdownField::create(
   				'FolderID',
   				_t('EditableUploadField.SELECTUPLOADFOLDER', 'Select upload folder'),
   				'Folder'
   			)
   		);

   		$fields->addFieldToTab("Root.Main", new LiteralField("FileUploadWarning",
   				"<p class=\"message notice\">" . _t("UserDefinedForm.FileUploadWarning",
   				"Files uploaded through this field could be publicly accessible if the exact URL is known")
   				. "</p>"), "Type");

        $randomise = ($this->Obfuscate) ? $this->Obfuscate : null;
        $fields->addFieldToTab("Root.Main", new CheckboxField('Obfuscate', 'Obfuscate upload folder - provides some hiding of uploaded files', $randomise));
        $fields->addFieldToTab("Root.Main", new CheckboxField('MultipleUploads', 'Allow multiple uploads' , $this->MultipleUploads));

   		return $fields;
   	}



    public function getFormField()
    {
        $field = FileAttachmentField::create($this->Name, $this->Title);

 //       $field->setFieldHolderTemplate('UserFormsField_holder')
 //     				->setTemplate('UserFormsFileField');

//        $field->getValidator()->setAllowedExtensions(
//      			array_diff(
//      				// filter out '' since this would be a regex problem on JS end
//      				array_filter(Config::inst()->get('File', 'allowed_extensions')),
//      				$this->config()->allowed_extensions_blacklist
//      			)
//      	);

        $folder = $this->Folder();
      		if($folder && $folder->exists()) {
      			$field->setFolderName(
      				preg_replace("/^assets\//","", $folder->Filename)
      			);
      		}


        if ($this->Obfuscate) {
            $folder = rtrim($field->getFolderName(), '/');
            $folder .= '/' . md5(time() + mt_rand());
            $field->setFolderName($folder);
        }

        if ($this->MultipleUploads) {
            $field->setMultiple(true);
        }

        if ($this->Required) {
            // Required validation can conflict so add the Required validation messages
            // as input attributes
            $errorMessage = $this->getErrorMessage()->HTML();
            $field->setAttribute('data-rule-required', 'true');
            $field->setAttribute('data-msg-required', $errorMessage);
        }

        return $field;
    }

    /**
     * Return the value for the database, link to the file is stored as a
     * relation so value for the field can be null.
     *
     * @return string
     */
    public function getValueFromData($data)
    {
        $val = isset($data[$this->Name]) ? $data[$this->Name] : null;
        return is_array($val) ? implode(',', $val) : $val;
    }

    public function getSubmittedFormField()
    {
        return new SubmittedMultiFileField();
    }

}
