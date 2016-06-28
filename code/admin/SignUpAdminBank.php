<?php
class SignUpAdminBank extends ModelAdmin {
	public static $managed_models = array(
        'BankBranchRegister'
	);
    static $model_importers = array(
     'BankBranchRegister' => 'CsvBulkLoader'
    );

	static $url_segment = 'bankbranch';
	static $menu_title = 'Bank Branch';
}