<?php

require 'vendor/autoload.php';
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/MyClient.php";

use Phpoaipmh\Client,
    Phpoaipmh\Endpoint,
    Phpoaipmh\HttpAdapter\HttpAdapterInterface,
    Phpoaipmh\HttpAdapter\CurlAdapter,
    Phpoaipmh\HttpAdapter\GuzzleAdapter;

define("OAI_HOST", "http://gokb.kuali.org/gokb/oai/");
define("SPARQL_HOST", "http://test-gokb.kuali.org/sparql?default-graph-uri=&query=");
/**
* 
*/
class GOKbTools {
    
    /**
     * @var Client
     */
    private $titleClient;
    
    /**
     * @var Client
     */
    private $packageClient;

    /**
     * @var Endpoint
     */
    private $titleEndpoint;

    /**
     * @var Endpoint
     */
    private $packageEndpoint;

    /**
     * @var HttpAdapterInterface
     */
    private $httpClient;

    /**
     * @var GOKbTools (Pattern Singleton)
     */
    private static $instance = null;
// -------------------------------------------------------------------------
    /**
     * Return the unique instance of class (design pattern singleton)
     */
    public static function getInstance(){
        if (is_null(self::$instance)){
            self::$instance = new GOKbTools();
        } 
        return self::$instance;
    }

// -------------------------------------------------------------------------
     /**
     * private constuctor (design pattern singleton)
     */
    private function __construct()
    {
        $this->titleClient = new Client(OAI_HOST.'titles');
        $this->packageClient = new Client(OAI_HOST.'packages');
        $this->titleEndpoint = new Endpoint($this->titleClient);
        $this->packageEndpoint = new Endpoint($this->packageClient);


        $this->httpClient = (class_exists('GuzzleHttp\Client')) ? new GuzzleAdapter() : new CurlAdapter();

    }

// ------------------------------------------------------------------------- 
/**
     * Build and send search queries, return an array of array.
     * @param string    $name       the name searched
     * @param string    $issn       the type of searched resource
     * @param string    $publisher  the type of searched resource
     * @param int       $searchType type of search ( 0 = search packages + issues, 5 results per type;
     *                                               <0 = all results of packages;
     *                                               >0 = all results of titles;)
     * @return array                multidimensionnal array :  $res[0] = array packages, [1]= array titles
     *                              each array is like [GOKb_identifer => name]
     */
    public function searchOnGokb($name, $issn, $publisher, $searchType){

        // query construction for packages
        if ((!empty($name)) && (empty($issn)) && (empty($publisher)) && ($searchType <= 0)){
            $query = 'select distinct * where {';
            $query .= '?s a <http://purl.org/dc/dcmitype/Collection> .';
            $query .= '?s <http://www.w3.org/2004/02/skos/core#prefLabel> ?o .';
            $query .= 'FILTER regex(?o, "'.$name.'", "i")} ORDER BY DESC(?o)';
            if($searchType == 0) $query .= 'LIMIT 5';
            
            //send the request and get results
            $tmp = $this->sendSparqlQuery($query);
            $res = $tmp->{"results"}->{"result"};

            foreach ($res as $a => $b ) {
                $uri = $b->{'binding'}->{'uri'};
                $id = $this->UriToGokbId($uri);
                $prefLabel = $b->{'binding'}[1]->{'literal'};
                $packages["$id"] = $prefLabel;
            }
        }

        $titles = array();

        // query construction for titles
        if($searchType >= 0) {
            if (!empty($issn)){ //search by issn (or eissn) only
                $query = 'select distinct * where {';
                $query .= '?s a <http://www.w3.org/2002/07/owl#Work> .';
                $query .= '?s  <http://www.w3.org/2002/07/owl#sameAs> "'.$issn.'" .';
                $query .= '?s <http://www.w3.org/2004/02/skos/core#prefLabel> ?o .}';
            } else if (!empty($publisher)){ 
                $query = 'select distinct ?title ?name where{';
                $query .= '?title a <http://www.w3.org/2002/07/owl#Work>.';
                $query .= '?title <http://www.w3.org/2004/02/skos/core#prefLabel> ?name .';
                $query .= '?title <http://purl.org/dc/terms/publisher> ?orgID .';
                $query .= '{select distinct ?orgID where {';
                $query .= '?orgID a <http://xmlns.com/foaf/0.1/Organization> .';
                $query .= '?orgID <http://www.w3.org/2004/02/skos/core#prefLabel> ?label .';
                $query .= 'FILTER regex (?label, "'.$publisher.'", "i")} GROUP BY (?orgID)}';
                if (!empty($name)) {$query .= ' FILTER regex(?name, "'.$name.'", "i")';}
                $query .= '}';

            } else { //search by name only
                $query = 'select distinct * where {';
                $query .= '?s a <http://www.w3.org/2002/07/owl#Work> .';
                $query .= '?s <http://www.w3.org/2004/02/skos/core#prefLabel> ?o .';
                $query .= 'FILTER regex(?o, "'.$name.'", "i")} ORDER BY ASC(?o)';

            }
            if($searchType == 0) $query .= 'LIMIT 5';
            
            //send the request and get results
            $tmp = $this->sendSparqlQuery($query);
            $res = $tmp->{"results"}->{"result"};

            foreach ($res as $a => $b ) {
                $uri = $b->{'binding'}->{'uri'};
                $id = $this->UriToGokbId($uri);
                //$prefLabel = $b->{'binding'}[1]->{'literal'};
                $prefLabel = utf8_encode($b->{'binding'}[1]->{'literal'});
                $titles["$id"] = $prefLabel;
            }

        }

        $results = array($packages, $titles);
        return $results;

    }
// ------------------------------------------------------------------------- 

    /**
     * Perform a request and return a OAI SimpleXML Document
     * @param string    $query      the SPARQL request to perform
     * @return \SimpleXMLElement    An XML document
     */
    private function sendSparqlQuery($query){
        $url = SPARQL_HOST.urlencode($query);
        $url .= '&format=text%2Fxml&timeout=0&debug=on';

        $tmpClient = new Client();
        try {
            $response = $this->httpClient->request($url);
        } catch (HttpException $e) {
            $tmpClient->checkForOaipmhException($e);
            $response = '';
        }
        $myTmpClient = new MyClient();
        $res =  $myTmpClient->decodeResponse($response);
        return $res;
    }

// -------------------------------------------------------------------------
    /**
     * Convert the resource's URI into its GOKb identifier
     *      http://www.gokb.org/data/packages/XX --> org.gokb.cred.Package:XX
     *      http://www.gokb.org/data/titles/XX   --> org.gokb.cred.TitleInstance:XX
     *      http://www.gokb.org/data/orgs/XX     --> org.gokb.cred.Org:XX
     * @param   string  $uri    resource's URI
     * @return  string          resource's GOKb identifier
     */
    public function UriToGokbId($uri)
    {
        $cut = explode('/', $uri);
        $nb = count($cut);
        
        $gokbID = "org.gokb.cred.";

        switch ($cut[$nb-2]) {
            case 'packages':
                $gokbID .= 'Package';
                break;
            case 'titles':
                $gokbID .= 'TitleInstance';
                break;
            case 'orgs':
                $gokbID .= 'Org';
                break;

            default:
                break;
        }

        $gokbID .= ':'. $cut[$nb-1];

        return $gokbID;
    }
// -------------------------------------------------------------------------
    /**
     * Perform a 'GetRecord' request
     * @param   string  $type       the resource's type
     * @param   string  $gokbID     the resource's GOKb identifier
     * @return  /XMLSimpleElement   An XML document
     */
    public function getDetails($type, $gokbID)
    {
        $record = $this->getRecord($type, $gokbID);
        $rec = $record->{'metadata'}->{'gokb'}->{$type};
        
        return $rec;
    }


// -------------------------------------------------------------------------
    /**
     * Extract and display the results included in an xml document
     *          _ Recursive function _
     * @param $xml  /SimpleXMLElement    XML document to treat
     * @return      string               content 
     */

    public function displayRecord($xml){
        $string = "";
        if ((count($xml->children()) > 0) && ($xml->getName() != 'TIPPs')) {
            $string = "<table id='detailsTab'>";
            foreach ($xml->children() as $tag => $child) {
                if ($tag != 'TIPPs'){
                    $string .= '<tr> <td class="detailsTab_tag">'.$tag.'</td>';
                    $string .= '<td class="class="detailsTab_val">'.$this->displayRecord($child).'</td>';
                    $string .= '</tr>';
                }
            }
            $string .='</table>';
        }
        else{
            if ($xml->getName() == 'identifier') 
            {
                $string = '<td>';
                foreach ($xml->attributes() as $key => $value) {
                    $string .= '<td>'.$value.'</td>';
                }

                $string .= '</td>';
            } elseif ($xml->getName() == 'TIPPs') {
                $string = "";
            }
            elseif ($xml != "") {
                $string = $xml;
            } else {
                $string = "<span class='smallDarkRedText'>Empty</span>";
            }
        }
        return $string;
    }

// -------------------------------------------------------------------------
    /**
    * Extract and display TIPPs from record
    * @param $record        /SimpleXMLElement   results of getRecord request
    * @param $recordType    string              type of resource
    * @return               string              HTML content to display
    */
    function displayRecordTipps($record, $recordType){
        $string = "";
        $tipps=$record->{'TIPPs'};
        
        if ($recordType == 'package') {
                  $type = 'title';
            } else {
                  $type = 'package';
            }

            $string .= "<table id='tippsTable'> ";
        
        foreach ($tipps->children() as $child) {
            $resource = $child->{$type};
            $resourceAttr = $resource->attributes();
            $gokbID = $this->UriToGokbId("$type".'s/'.$resourceAttr[0]);
            
            //$string .= '<tr class=invisible> <td>- </td> <td><span onclick="';
            $string .= '<tr class=invisible> <td>- </td> <td><a class=tippLink onclick="';
            $string.= "getDetails('".$type."','".$gokbID."');";
            $string.= '">';
            //$string .= $this->getResourceName($resource). "</span></td> </tr>";
            $string .= $this->getResourceName($resource). "</a></td> </tr>";
        }
        
        $string .= "</table>";
        
           return $string;
    }
// -------------------------------------------------------------------------
    /**
    * Return resource name extract from XML metadata
    * @param $record    /SimpleXMLElement   results of getRecord request
    * @return           string              name of the resource
    */
    function getResourceName($record){
        return (string) $record->{'name'};
    }
// -------------------------------------------------------------------------
    /**
    * Return the number of TIPPs of the resource, extract from XML metadata
    * @param $record    /SimpleXMLElement   results of getRecord request
    * @return           int                 number of TIPPs
    */
    function getNbTipps($record){
        $tipps = $record->{'TIPPs'};
        $tmp = $tipps->attributes();
        return (int) $tmp[0];
    }

// -------------------------------------------------------------------------

    /**
     *   Perform a 'GetRecord' request
     * @param string     $type      resource's type (title or package)
     * @param string     $id           GOKb ID
     * @return XMLElement          Record: resource's informations
     */
    function getRecord($type, $id){
        
          switch ($type) {
            case 'title':
                $record = $this->titleEndpoint->getRecord($id, 'gokb');
                break;
            case 'package':
                $record = $this->packageEndpoint->getRecord($id, 'gokb');
                break;
            default:
                return null;
        }

        $rec = $record->{'GetRecord'}->{'record'};
        return $rec;
    }
    
    // -------------------------------------------------------------------------
    
    /**
     * create an array to stock identifiers for import treatment
     * @param array  $ids     array(XML elements)
     * @return type
     */
    function createIdentifiersArrayToImport($ids) {
            foreach ($ids as $key => $value) {
                  $tmp = $value->attributes();
                  $identifiers["$tmp[0]"] = (string) $tmp[1];
            }
            return $identifiers;
      }
      
      // -------------------------------------------------------------------------
      
      /**
       *  Convert a string date (from XML GOKb record) into DateTime object
       * @param string   $xmlDate   the date from XML record
       * @return DateTIme 
       */
      function convertXmlDateToDateTime($xmlDate){
            $format = "Y-m-d H:i:s.u";
            $date = DateTime::createFromFormat($format, $xmlDate);
            return $date;
      }

}

?>