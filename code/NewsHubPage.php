<?php

class NewsHubPage extends Page {
    private static $db = array(
    );
    private static $has_one = array(
    );
    private static $has_many = array(
    );


    function getCMSFields() {
        $fields = parent::getCMSFields();
                 /*
                 * Add Orbit Section to the page using has_many relation + GridFeild
                 */


        return $fields;
        }
 }

class NewsHubPage_Controller extends Page_Controller {

    //no point in creating empty columns for child pages with no children
        public function columnCount(){
            $count = 0;
            if($this->Children()){
               // print_r($this);
                foreach($this->Children() as $child){
                 //   print_r($child);
                  // print_r($child->Title);
                  // print_r("<br>");
                    if($child->Children()->count()){
                        $count++;
                       // print_r("Count++");
                       // print_r("child ".$child->Children()->count());
                       // foreach($child->Children() as $mokopuna){

                       //     print_r("Child name: ");
                       //     print_r($mokopuna->URLSegment.' - '.$mokopuna->URLSegment);
                       //     print_r("<BR>");
                       // }
                    }
                  //  print_r("<hr>");
                }
            };
            return $count;
        }

        public function F4ColumnClass() {
                 switch ($this->columnCount()){
                     case 0; return "large-12 small-12";
                     case 1: return "large-12 small-12";
                     case 2: return "large-6 small-12";
                     case 3: return "large-4 small-12";
                     case 4: return "large-3 small-12";
                     case 6: return "large-2 small-12";
                 }
         }



    public function ColumnsCount(){
        //print_r($this->Children());
        return $this->Children()->Count();
    }
}

