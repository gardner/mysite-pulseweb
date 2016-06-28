<?php
/**
 * TextareaField creates a multi-line text field,
 * allowing more data to be entered than a standard
 * text field. It creates the <textarea> tag in the
 * form HTML.
 * 
 * <b>Usage</b>
 * 
 * <code>
 * new TextareaField(
 *    $name = "description",
 *    $title = "Description",
 *    $value = "This is the default description"
 * );
 * </code>
 * 
 * @package forms
 * @subpackage fields-basic
 */
class AddressAreaField extends FormField {
	
	/**
	 * @var int Visible number of text lines.
	 */
	protected $rows = 5;

	/**
	 * @var int Width of the text area (in average character widths)
	 */
	protected $cols = 20;

	public function getAttributes() {
		return array_merge(
			parent::getAttributes(),
			array(
				'rows' => $this->rows,
				'cols' => $this->cols,
				'value' => null,
				'type' => null
			)
		);
	}

	public function Type() {
		return parent::Type() . ($this->readonly ? ' readonly' : '');
	}
	
	/**
	 * Set the number of rows in the textarea
	 *
	 * @param int
	 */
	public function setRows($rows) {
		$this->rows = $rows;
		return $this;
	}
	
	/**
	 * Set the number of columns in the textarea
	 *
	 * @return int
	 */
	public function setColumns($cols) {
		$this->cols = $cols;
		return $this;
	}

	public function Value() {
		return htmlentities($this->value, ENT_COMPAT, 'UTF-8');
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
         // this is to get the address on multiple lines and looking something like https://www.nzpost.co.nz/sites/default/files/uploads/shared/standards-guides/adv357-a-quick-guide-letters-and-parcels.pdf
         widget.on("result:select", function (value, data) {
         //console.log(data);
         var city = data.city;
         if (data.mailtown && data.city != data.mailtown) {
            city = data.mailtown;
         }
         var RD = (data.rd_number) ? ' ' + data.rd_number : '';
         var Street = (data.postal_line_1 || '' )
            + ((data.postal_line_2) ? ',\\n' + data.postal_line_2 : '')
            + ((data.rd_number && (data.suburb && !(data.suburb == city)))? ',\\n' + data.suburb : '')
            + ((data.postal_line_3)?  ',\\n' + data.postal_line_3 : '')
            + ((data.postal_line_4)? ',\\n' + data.postal_line_4  : '');
//         var LastLine = ((data.suburb && !(data.suburb == city)) ? data.suburb + ',\\n': '') + city + ' ' + data.postcode;
//         var Checkline = city + ' ' + data.postcode;
//         console.log(LastLine);
//         console.log(Checkline);
//         console.log(data.postal_line_4);
//         if(!((Checkline == data.postal_line_2) || Checkline == data.postal_line_3 || Checkline == data.postal_line_4)){
//           var Total = Street + ',\\n' + LastLine;
//           console.log('here');
//         }
//         else{
//           var Total = Street;
//           console.log('there');
//         }

         $("#{$this->ID()}").val(Street || '').trigger('change');
        });

//        widget.on("results:empty", function (value, data) {
//         $("#{$this->ID()}").blur().focus(); //monumental fudge so I can keep typing CRs
//        });

 });


JS
);
    		return $html;
    	}


}
