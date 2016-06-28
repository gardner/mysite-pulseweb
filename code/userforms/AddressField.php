<?php
/**
 * Text input field.
 *
 * @package forms
 * @subpackage fields-basic
 */
class AddressField extends FormField {

	/**
	 * @var int
	 */
	protected $maxLength;
	
	/**
	 * Returns an input field, class="text" and type="text" with an optional maxlength
	 */
	public function __construct($name, $title = null, $value = '', $maxLength = null, $form = null) {
		$this->maxLength = $maxLength;
		
		parent::__construct($name, $title, $value, $form);
	}
	
	/**
	 * @param int $length
	 */
	public function setMaxLength($length) {
		$this->maxLength = $length;
		
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getMaxLength() {
		return $this->maxLength;
	}

	public function getAttributes() {
		return array_merge(
			parent::getAttributes(),
			array(
				'maxlength' => $this->getMaxLength(),
				'size' => ($this->getMaxLength()) ? min($this->getMaxLength(), 30) : null
			)
		);
	}

	public function InternallyLabelledField() {
		if(!$this->value) $this->value = $this->Title();
		return $this->Field();
	}

    public function FieldHolder($properties = array()) {
            $html = parent::FieldHolder($properties);
    		$html = $this->onAfterRender($html);
    		return $html;
    }

    public function SmallFieldHolder($properties = array()){
    		$html = parent::SmallFieldHolder($properties);
    		$html = $this->onAfterRender($html);
    		return $html;
    }


    public function onAfterRender($html) {

   		Requirements::javascript('//www.addressfinder.co.nz/assets/v2/widget.js');
        Requirements::customScript(<<<JS

   $(document).ready(function () {
        var setByBranch = false; // for resetting bank number

        var widget = new AddressFinder.Widget(document.getElementById('{$this->ID()}'), "V3YULPXNHT49F8BCE7DG",
                {
                    manual_style: true,
                    empty_content: "",
                    empty_class: "af_empty",
                    show_addresses: true,
                    show_locations: false,
                    address_params: {
                        street: 1,
                        city: 1,
                        region: 1
                    }
                });

        widget.on("result:select", function (value, data) {
            //console.log(data);
            var city = data.city;
            if (data.mailtown && data.city != data.mailtown) {
                city = data.mailtown;
            }
            var RD = (data.rd_number) ? ' ' + data.rd_number : '';
            var Street = (data.postal_line_1 || '' ) + ((data.postal_line_2) ? ', ' + data.postal_line_2 : '');
            var Total = Street + ' ' + data.suburb + ' ' + City + RD + ' ' + data.postcode;

            $("#{$this->ID()}").val(Total || '').trigger('change');



        });

});


JS
);
   		return $html;
   	}
	
}
