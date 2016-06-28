<?php
class AddressPage extends Page
{

    private static $db = array();

    private static $has_one = array();

    private static $has_many = array();


}

class AddressPage_Controller extends Page_Controller
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
    public static $allowed_actions = array('AJAXComplete', 'finalize', 'AddressSearchForm');

    public function init()
    {
        parent::init();

        Requirements::javascript('http://www.addressfinder.co.nz/assets/v2/widget.js');

        $ajaxLoad = $this->Link() . 'AJAXComplete';

        // respond to form submission
        $url = $this->Link() . 'AJAXComplete';

        Requirements::customScript(<<<JS
                    (function($) {
                      //  $(document).ready(function() {
                      //      $("#Form_AjaxForm").submit(function(){
                      //          $('#result').load(
                       //             '{$url}',
                       //             {values: $(this).serialize()}
                      //          );
                      //          return false;
                     //       });
                    //    });
                        })(jQuery);
                    var widget = new AddressFinder.Widget(document.getElementById("Form_AddressSearchForm_Address"), "V3YULPXNHT49F8BCE7DG");
                    widget.on("result:select", function (value, item) {
                    /* clear address fields */
                    $("input.address-field").val("");

                    /* populate postal line fields */
                    $("#Form_AddressSearchForm_street_number").val(item.number || '');
                    $("#Form_AddressSearchForm_street_alpha").val(item.alpha || '');
                    $("#Form_AddressSearchForm_unit_identifier").val(item.unit_identifier || '');
                    $("#Form_AddressSearchForm_street").val(item.street || '');
                    $("#Form_AddressSearchForm_street_type").val(item.street_type || '');
                    $("#Form_AddressSearchForm_suburb").val(item.suburb || '');
                    $("#Form_AddressSearchForm_postal_suburb").val(item.postal_suburb || '');
                    $("#Form_AddressSearchForm_city").val(item.city || '');
                    $("#Form_AddressSearchForm_region").val(item.region || '');
                    $("#Form_AddressSearchForm_postcode").val(item.postcode || '');
                    $("#x").val(item.x || '');
                    $("#y").val(item.y || '');
                    });

JS
        );
    }

    function AJAXComplete()
    {
        $this->response->addHeader('Content-Type', 'application/json');
        // if (Director::is_ajax()) {

        //  if ($this->requestParams['SecurityID'] == Session::get('SecurityID')) {
        $service = new RestfulService("https://api.esam.co.nz/", 0);
        //$service->basicAuth('username', 'password');
        $service->setQueryString(array('access_token' => 'X28L0Ovlq0', 'SearchFor' => $this->requestParams['term']));
        $results = $service->request('/paf/address/v01/rest/SuggestAddress');
//print_r($service);
//        print_r($this->requestParams['SearchFor']);
//print_r($results);
        return $results->getBody();
//     return Convert::array2json($results->getBody());

        // }
        // else return false;
        //}
        // return array();
    }

    function finalize()
    {

        return 'do something clever with the form';

    }

    public function AddressSearchForm()
    {
       include_once("chromephp/ChromePhp.php");
        $fields = new FieldList(
            new TextField('Address', 'Address to Look for'),
            new HiddenField('street_number'),
            new HiddenField('street_alpha'),
            new HiddenField('unit_identifier'),
            new HiddenField('street'),
            new HiddenField('street_type'),
            new HiddenField('suburb'),
            new HiddenField('postal_suburb'),
            new HiddenField('city'),
            new HiddenField('region'),
            new HiddenField('postcode')

        );
        $actions = new FieldList(
            FormAction::create("doSayHello")->setTitle("Say hello")
        );
        $form = new Form($this, 'AddressSearchForm', $fields, $actions);
        // Load the form with previously sent data
        $form->loadDataFrom($this->request->postVars());
        return $form;
    }

    public function doSayHello($data, Form $form)
    {
        include_once('chromephp/ChromePhp.php');

        ChromePhp::log($data);
        //   $service = new RestfulService("https://www.electricityregistry.co.nz/", 0);
     //   $service = new SoapClient("https://www.electricityregistry.co.nz/bin_public/Jadehttp.dll?WebService&serviceName=WSRegistry&listName=WSP_Registry2&wsdl=wsdl");
        $service = new SoapClient("https://www.electricityregistry.co.nz/bin_public/Jadehttp.dll?WebService&serviceName=WSRegistry&listName=WSP_Registry2&serviceName=WSRegistry&wsdl=wsdl", array('features' => SOAP_SINGLE_ELEMENT_ARRAYS));
//       echo "<h1>Functions</h1>";
//
//        var_dump($service->__getFunctions());
//        echo "<h1>Types</h1>";
//        var_dump($service->__getTypes());
        //      $service = new RestfulService("http://staging.pulseenergy.co.nz/", 0);
        //$service->basicAuth('username', 'password');
        $region = substr($data['region'], 0, -7);
//print_r($region);

        $parameters = [
            'userName' => 'julianw',
            'password' => '17harper',
            'unitOrNumber' => $data['street_number'],
            'streetOrPropertyName' => $data['street'],
            'streetOrPropertyFilter' => '',
            'suburbOrTown' => $data['suburb'],
            'suburb' => $data['suburb'],
            'region' => '',//$region,//substr($data['region'], 0, -7),
            'isExactMatch' => null,
            'ownIcpsOnly' => null,
            'commissionedOnly' => null
        ];
        $validresult = 0;
        ChromePhp::log('parameters');
        ChromePhp::log($parameters);

       // ChromePhp::table($parameters);
        $results = $service->icpSearch_v1($parameters);
        $resultarray = (array) $results; //Bloody hell casting that easy?
        ChromePhp::log($results->message);
    print_r('<pre>');
    print_r($results->message);
    print_r('resultsarray');
    print_r($resultarray);
    print_r('</pre>');

        if (strpos($results->message, 'Results')&& ($results->message != "Results: 0")){
            print_r("RESULT!!");
         $validresult ++;
        }
        if (!strcasecmp($region, 'manawatu-wanganui')){
            $parameters['region'] = 'wanganui';
            $results_w = $service->__soapCall('icpSearch_v1', array('parameters' => $parameters));
            $resultarray_w = (array)$results_w;
            if ($resultarray_w['message'] != "Results: 0") $validresult ++;
            //print_r($resultarray_w);
            $parameters['region'] = 'manawatu';
            $results_m = $service->__soapCall('icpSearch_v1', array('parameters' => $parameters));
            $resultarray_m = (array)$results_m;
            if ($resultarray_m['message'] != "Results: 0") $validresult ++;
            //print_r($resultarray_m);
            $merged = $resultarray_m + $resultarray_w +$resultarray;
            $resultarray= $merged;
            //print_r($resultarray);
            //print_r($validresult);
        }
        //request('/bin_public/Jadehttp.dll?WebService&serviceName=WSRegistry&listName=WSP_Registry2', 'POST', $postArray);
        //$results = $service->request('/tazzypost.php', 'POST',$postArray);
        // echo "<h1>Results</h1>";
        //$myarray = new SimpleXMLElement($results);
       //print_r($results);
        //ChromePhp::log($parameters);

        if (empty($resultarray['allErrors'])) {
            if (!$validresult) print_r('No Results');
            else {
                foreach ($resultarray['icpSearch_v1Result']->allResults->WS_ICPSearchResult as $searchResult) {
                    foreach ($searchResult->myAddressHistory as $him => $her) {
                      print($him . ' ' . $her . '<br>');
                    }
                    foreach ($searchResult->myIcp as $him => $her) {
                      print($him . ' ' . $her . '<br>');
                    }
                }



/*                foreach ($results->icpSearch_v1Result->allResults->WS_ICPSearchResult as $searchResult) {
                    foreach ($searchResult->myAddressHistory as $him => $her) {
                        print_r($him . ' ' . $her . '<br>');
                    }
                }*/
                //return "<h1>Results</h1>" . $results->message . $results->icpSearch_v1Result->allErrors->WS_Error->text;
            }
        }

        // return print_r($data) . $this->render(); //$this->render();
    }

}


