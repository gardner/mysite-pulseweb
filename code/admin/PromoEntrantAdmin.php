<?php
/**
 * Created by PhpStorm.
 * User: julianw
 * Date: 11/04/2016
 * Time: 1:26 PM
 */


class PromoEntrantAdmin extends ModelAdmin {
	public static $managed_models = array(
        'PromoEntrant'
	);
    static $model_importers = array(
    );

	static $url_segment = 'PromoAdmin';
	static $menu_title = 'Promo Admin';
}