<?php
class DirectDebitAdmin extends ModelAdmin {
	public static $managed_models = array(
        'DirectDebitFormSubmission'
	);
    static $model_importers = array(
//     'LpgPricingTable' => 'CsvBulkLoader',
//     'NatGasPricingTable' => 'CsvBulkLoader',

    );

	static $url_segment = 'DirectDebit';
	static $menu_title = 'DirectDebitForms';
}