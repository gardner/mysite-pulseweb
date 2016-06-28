<?php

class HeroPage extends Page
{
    private static $db = array(
            'MetroColumnNumber' => 'Int',
//        'MetroRowNumber' => 'Int',
            'MetroBoxHeight' => 'Int',
            'GreenCTAContent' => 'HTMLText',
            'FooterMegaMenu' => 'HTMLText',

    );

    private static $has_one = array();

    private static $many_many_extraFields = array(
            'MetroImageUpper' => array(
                    'SortOrder' => 'Int'
            ),
            'MetroImageLower' => array(
                    'SortOrder' => 'Int'
            ),
    );


    //   static $allowed_children = array('FeaturePage');


    // private static $many_many = array();

    public function MetroImageUpper()
    {
        return $this->getManyManyComponents('MetroImageUpper')->sort('SortOrder');
    }

    public function MetroImageLower()
    {
        return $this->getManyManyComponents('MetroImageLower')->sort('SortOrder');
    }

    private static $many_many = array(
            'MetroImageUpper' => 'MetroImage',
            'MetroImageLower' => 'MetroImage',
    );


    public function populateDefaults()
    {
        parent::populateDefaults();
        $this->DropInColumnNumber = 0;

        $this->DropInBoxHeight = 250;
        $this->MetroColumnNumber = 2;

        $this->MetroBoxHeight = 250;
    }


    function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $greenCTAfield = new HtmlEditorField('GreenCTAContent', 'Green Call to Action', '');
        $fields->addFieldToTab('Root.Main', $greenCTAfield);

        $footerMegafield = new HtmlEditorField('FooterMegaMenu', 'Footer Mega Menu', '');
        $fields->addFieldToTab('Root.Main', $footerMegafield);


        $fields->addFieldToTab('Root.MetroImages',
                new DropdownField('MetroColumnNumber', 'Number of Metro Image Columns',
                        array('0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4')));
        $fields->addFieldToTab('Root.MetroImages',
                new DropdownField('MetroBoxHeight', 'Metro Image Box Height in Pixels',
                        array(150 => 150, 200 => 200, 250 => 250, 300 => 300, 400 => 400)));


        $fields->removeFieldsFromTab('Root', array('ContentForDropIn'));

        /*
         * Add Orbit Section to the page using has_many relation + GridFeild
         */

        $gridFieldConfig3 = GridFieldConfig_RelationEditor::create();
        $gridFieldConfig3->addComponents(
                new GridFieldSortableRows("SortOrder"), new GridFieldBulkManager()//, new GridFieldBulkUpload()
        );
        $gridFieldConfig4 = GridFieldConfig_RelationEditor::create();
        $gridFieldConfig4->addComponents(
                new GridFieldSortableRows("SortOrder"), new GridFieldBulkManager()//, new GridFieldBulkUpload()
        );


        $gridField3 = new GridField("MetroImageUpper", "Upper Metro Box Images", $this->MetroImageUpper(),
                $gridFieldConfig3, $this);
        $fields->addFieldToTab('Root.MetroImages', $gridField3);

        $gridField4 = new GridField("MetroImageLower", "Lower Metro Box Images", $this->MetroImageLower(),
                $gridFieldConfig4, $this);
        $fields->addFieldToTab('Root.MetroImages', $gridField4);

        return $fields;
    }
}

class HeroPage_Controller extends Page_Controller
{

    public function ChildColumnSizeFeat()
    {
        $count = DB::query("SELECT COUNT(*) FROM SiteTree_Live WHERE ParentID = '$this->ID'")->value();
        //$size = round(12/$count,0);
        //return $size;
        switch ($count) {
            case 1:
                return "twelve";
            case 2:
                return "six";
            case 3:
                return "four";
            case 4:
                return "three";
            case 5:
                return "two"; //eek wtf!! This leaves a hole. Dont do it.
            case 6:
                return "two";
        }
    }

    /*    function CountDropIn(){
                $count = DB::query("SELECT COUNT(*) FROM HeroPage_DropInPages WHERE HeroPageID = '$this->ID'")->value();
                //$size = round(12/$count,0);
             return $count;
        }*/

    public function MetroFontSize()
    {
        switch ($this->MetroColumnNumber) {
            case 0;
                return 'style="font-size:0.5em"';
            case 1:
                return 'style="font-size:0.5em"';
            case 2:
                return 'style="font-size:0.5em"';
            case 3:
                return 'style="font-size:0.7em"';
            case 4:
                return 'style="font-size:0.9em"';
        }
    }

    public function F4DropInCSSCount()
    {
        switch ($this->DropInColumnNumber) {
            case 0;
                return "12";
            case 1:
                return "12";
            case 2:
                return "6";
            case 3:
                return "4";
            case 4:
                return "3";
        }
    }


    /*    public function MetroCSSCount() {
                 switch ($this->MetroColumnNumber){
                     case 0; return "twelve";
                     case 1: return "twelve";
                     case 2: return "six";
                     case 3: return "four";
                     case 4: return "three";
                 }
         }*/

    public function F4MetroCSSCount()
    {
        switch ($this->MetroColumnNumber) {
            case 0;
                return "large-12 small-12";
            case 1:
                return "large-12 small-12";
            case 2:
                return "large-6 small-6";
            case 3:
                return "large-4 small-4";
            case 4:
                return "large-3 small-3";
            case 6:
                return "large-2 small-4";
        }
    }

    public function F4MetroListCount()
    {
        switch ($this->MetroColumnNumber) {
            case 0;
                return "small-block-grid-1 large-block-grid-1";
            case 1:
                return "small-block-grid-1 large-block-grid-1";
            case 2:
                return "small-block-grid-2 large-block-grid-2";
            case 3:
                return "small-block-grid-2 large-block-grid-3";
            case 4:
                return "small-block-grid-2 large-block-grid-4";
            case 6:
                return "small-block-grid-2 large-block-grid-6";
        }
    }

    public function DropInRows()
    {
        if (CountDropIn() >= 4) {
            return true;
        } else {
            return false;
        }

    }


    public function askNicelyHome()
    {
        $ch = curl_init("http://static.asknice.ly/published/pulseenergy/recommend_home.html");
        if (!$ch) {
            return null; //die( "Cannot allocate a new PHP-CURL handle" );
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_ENCODING ,"ISO-8859-1");
        $data = curl_exec($ch);
        $data = str_replace("\xEF\xBB\xBF", '', $data);

        return $data;
    }


    public function askNicelyJSON()
    {
        $ch = curl_init("https://pulseenergy.asknice.ly/api/v1/responses/desc/50/1/0/json/published?X-apikey=2WiE0zZo0ShSVJHDXqyhX8vMGxnFFYaEzZMWYPPKFXY");
        if (!$ch) {
            return null; //die( "Cannot allocate a new PHP-CURL handle" );
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_ENCODING ,"ISO-8859-1");
        $result = curl_exec($ch);
        //var_dump($result);
        $result = json_decode($result);
        if (empty($result)) {
            return null;
        }
        $html = "";
        $count = 0;
        foreach ($result->data as $object) {
            //var_dump($object);
            $count++;
            //$time = gmdate("Y-m-d\TH:i:s\Z", $object->created);
            $time = Timesince::maketime($object->responded);
            //$comment = preg_replace('%//.*(?<!\.)$%m', '\0.', $object->comment);
            $comment = preg_replace('/(?<![.])(?=[\n\r]|$)/','\0.', $object->comment);

            $output = preg_replace_callback('/([.!?])\s*(\w)/', function ($matches) {
                return strtoupper($matches[1] . ' ' . $matches[2]);
            }, ucfirst(strtolower($comment)));

            $html .= <<<EOT
                    <li style="margin:0px;position:relative"><div class="an_comment">
                <div class="an_bubble">
                            <p class="an_text">$output</p>
                            <div class="pointer">
                    </div>
                    <div class="pointerBorder">
                    </div>
                </div>
                <div class="an_who">
                  <div class="an_name">$object->name</div>
                  <div class="an_company"></div><div class="an_time">$time</div>
                </div>
            </div>
EOT;
            if ($count == 4) {
                break;
            }
        }

        return $html;

    }

}

?>
