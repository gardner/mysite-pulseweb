<?php
class SignUpAdmin extends ModelAdmin {
	public static $managed_models = array(
        'LpgPricingTable',
        'NatGasPricingTable'
	);
    static $model_importers = array(
     'LpgPricingTable' => 'CsvBulkLoader',
     'NatGasPricingTable' => 'CsvBulkLoader',

    );

	static $url_segment = 'Gas';
	static $menu_title = 'Gas Pricing';
}