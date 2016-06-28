<?php
class ConsumptionInfoRequestAdmin extends ModelAdmin {
	public static $managed_models = array(
        'ConsumptionInfoRequestSubmission'
	);
    static $model_importers = array(
//     'LpgPricingTable' => 'CsvBulkLoader',
//     'NatGasPricingTable' => 'CsvBulkLoader',

    );

	static $url_segment = 'EIEP13Request';
	static $menu_title = 'EIEP13 Request';
}