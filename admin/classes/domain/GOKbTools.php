<?php

require 'vendor/autoload.php';


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
        echo 'DEBUG_ getInstance()<br/>';
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
        echo 'DEBUG_ constructeur tool _ START<br/>';
        $this->titleClient = new Client(OAI_HOST.'titles');
        $this->packageClient = new Client(OAI_HOST.'packages');
        $this->titleEndpoint = new Endpoint($this->titleClient);
        $this->packageEndpoint = new Endpoint($this->packageClient);


        $this->httpClient = (class_exists('GuzzleHttp\Client')) ? new GuzzleAdapter() : new CurlAdapter();

        echo 'DEBUG_ constructeur tools _ END<br/>';
    }

// -------------------------------------------------------------------------

    /**
     * Build and send a search query, return an array 
     * @param string    $name   the name searched
     * @param string    $type   the type of searched resource
     * @return array            an array with all results [GOKb_identifer => name]
     */
    public function searchByName($name, $type){ //voir comment avoir une unique fonction de génération de requete (prefix etc...), 
        switch ($type) {
            case 'title':
                $prefix = '<http://www.w3.org/2002/07/owl#Work>';
                break;
            case 'package':
                $prefix = '<http://purl.org/dc/dcmitype/Collection>';
                break;
            default:
                break;
        }

        //query construction
        $query = 'select distinct * where {'.
        $query .= '?s a '.$prefix.' .';
        $query .= '?s <http://www.w3.org/2004/02/skos/core#prefLabel> ?o .';
        $query .= 'FILTER regex(?o, "'.$name.'", "i")} LIMIT 100';

        //send the request and get results
        $results = $this->sendSparqlQuery($query);
        $res = $results->{"results"}->{"result"};
        
        $tmp = array();
        foreach ($res as $a => $b ) {
            $uri = $b->{'binding'}->{'uri'};
            $id = $this->UriToGokbId($uri);
            $prefLabel = $b->{'binding'}[1]->{'literal'};
            $tmp["$id"] = $prefLabel;
        }

        return $tmp;

    }

// ------------------------------------------------------------------------- 

 

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

        $res =  $tmpClient->decodeResponse($response); //decodeResponse protected: passé à public temporairement --> trouver une solution !! (héritage + surcharge ?)
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


    private function UriToGokbId($uri)
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
        echo 'DEBUG_ getDetails('.$type.','.$gokbID.')<br/>';
        switch ($type) {
            case 'title':
                $record = $this->titleEndpoint->getRecord($gokbID, 'gokb');
                break;
            case 'package':
                $record = $this->packageEndpoint->getRecord($gokbID, 'gokb');
                break;
            default:
                break;
        }


        $rec = $record->{'GetRecord'}->{'record'}->{'metadata'}->{'gokb'}->{$type};
        return $rec;
    }


// -------------------------------------------------------------------------
    /**
     * Extract and display the results included in an xml document
     *          _ Recursive function _
     * @param /SimpleXMLElement     XML document to treat
     * @return string               content 
     */

    public function displayRecord($xml){
        $string = "";
        if (count($xml->children()) > 0) {
            $string = "<table style='border-style:solid; border-width: 2px;'>";
            foreach ($xml->children() as $tag => $child) {
                $string .= '<tr> <td style="text-align: right;">'.$tag.'</td>';
                $string .= '<td>'.$this->displayRecord($child).'</td>';
                $string .= '</tr>';
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
            } elseif ($xml != "") {
                $string = $xml;
            } else {
                $string = "<span style='color:red;font-style: italic;'>Empty</span>";
            }
        }
        return $string;
    }
// -------------------------------------------------------------------------
// -------------------------------------------------------------------------

}



?>