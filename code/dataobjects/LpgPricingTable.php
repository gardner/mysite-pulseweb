<?php

class LpgPricingTable extends DataObject {
    static $db = array(
            'PriceRegion' => 'Varchar', //Register content code (eg UN) plus Period of availability (eg 24)
            'TerritorialAuthority' => 'Varchar',
            'ZoneName' => 'Varchar',
            'DeliveryZone' => 'Varchar',
            'CylinderRental' => 'Varchar',
            'AllEnergyDiscount' => 'Varchar',
            'CylinderRefill' => 'Varchar',
            'WeekdayUrgentRefill' => 'Varchar',
            'WeekendUrgentRefill' => 'Varchar',
            'PublicHolidayUrgentRefill' => 'Varchar',
            'PricingPdf' => 'Varchar'


    );

    static $searchable_fields = array(
            'PriceRegion',
            'DeliveryZone',
            'ZoneName'
    );
    static $summary_fields = array(

            'ID' => 'ID',
            'PriceRegion' => ' Price Region',
            'TerritorialAuthority' => 'Territorial Authority',
            'ZoneName' => 'Zone Name',
            'DeliveryZone' => 'Delivery Zone',
            'CylinderRental' => 'Cylinder Rental',
            'AllEnergyDiscount' => 'Discount',
            'CylinderRefill' => 'Cylinder Refill',
            'WeekdayUrgentRefill' => 'W/D Urgent Refill',
            'WeekendUrgentRefill' => 'W/E Urgent Refill',
            'PublicHolidayUrgentRefill' => 'P/H Urgent Refill',
            'PricingPdf' => 'Pricing Pdf'

    );

}






