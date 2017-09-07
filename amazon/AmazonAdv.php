<?php
namespace hardwarefinal;



/**
* AmazonAdv (Amazon Avertising) get products from Amazon for display it into your Website.
*
* @author   Franck Alary <http://www.developpeur-web.dantsu.com/>
* @version  1.0
* @access   public
*/

class AmazonAdv {


    private static $HTTP = 'http://';
    private static $HOST = 'webservices.amazon.fr';
    private static $METHOD = 'GET';
    private static $URI = '/onca/xml';
    private static $Key = 'AKIAIIEQ6YPV6ZCBKS2A';
    private static $SecretKey = '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';
    private static $AssociateTag = 'hardwhere1407-21';

    private $Keywords = array();
    private $SearchIndex = 'PC_HARDWARE';

    const ALL                  = "All";
    const APPAREL              = "Apparel";
    const AUTOMOTIVE           = "Automotive";
    const BABY                 = "Baby";
    const BEAUTY               = "Beauty";
    const BLENDED              = "Blended";
    const BOOKS                = "Books";
    const CLASSICAL            = "Classical";
    const DVD                  = "DVD";
    const ELECTRONICS          = "Electronics";
    const FOREIGN_BOOKS        = "ForeignBooks";
    const HEALTH_PERSONAL_CARE = "HealthPersonalCare";
    const JEWELRY              = "Jewelry";
    const KINDLE_STORE         = "KindleStore";
    const KITCHEN              = "Kitchen";
    const LIGHTING             = "Lighting";
    const MP3_DOWNLOADS        = "MP3Downloads";
    const MUSIC                = "Music";
    const MUSICAL_INSTRUMENTS  = "MusicalInstruments";
    const MUSIC_TRACKS         = "MusicTracks";
    const OFFICE_PRODUCTS      = "OfficeProducts";
    const PC_HARDWARE          = "PCHardware";
    const PET_SUPPLIES         = "PetSupplies";
    const SHOES                = "Shoes";
    const SOFTWARE             = "Software";
    const SOFTWARE_VIDEO_GAMES = "SoftwareVideoGames";
    const SPORTING_GOODS       = "SportingGoods";
    const TOYS                 = "Toys";
    const VHS                  = "VHS";
    const VIDEO                = "Video";
    const VIDEO_GAMES          = "VideoGames";
    const WATCHES              = "Watches";

    /**
    * Create a new instance of AmazonAdv
    *
    */
    public function NewRequest() {
        return new AmazonAdv();
    }

    /**
    * Add keyword to the products search.
    *
    * @param string $keywords Separate keywords with space.
    * @return AmazonAdv
    */
    public function addKeyword($keywords) {
        $ArrayWords = explode(' ', $keywords);
        foreach($ArrayWords as $word)
        {
            if($word != '')
            $this->Keywords[] = $word;
        }
        return $this;
    }

    /**
    * Reset the keyword list
    *
    * @return AmazonAdv
    */
    public function resetKeyword() {
        $this->Keywords = array();
        return $this;
    }

    /**
    * Encode the variable for URL
    *
    * @param string $string
    * @return string
    */
    public static function url_encode($string) {
        return str_replace("%7E", "~", rawurlencode($string));
    }

    /**
    * Set the searchIndex value (DVD, BOOKS, SOFTWARE...)
    *
    * @param string $SI Use the constant of AmazonAdv class
    * @return AmazonAdv
    */
    public function setSearchIndex($SI) {
        $this->SearchIndex = $SI;
        return $this;
    }

    /**
    * Execute the request to amazon. It will return a AmazonAdvItems object.
    *
    * @return AmazonAdvItems
    */
    public function request() {
// for ($i=1;$i<=3;$i++) {
        $parametre = array(


            'AWSAccessKeyId' => self::$Key,
            'AssociateTag' => self::$AssociateTag,
            'Keywords' => implode('+', $this->Keywords),
            'Operation' => 'ItemSearch',
            'ResponseGroup' => 'Images,ItemAttributes',
            'SearchIndex' => $this->SearchIndex,
            'Service' => 'AWSECommerceService',
            'Timestamp' => date("Y-m-d\TH:i:s\Z"),
            'Version' => '2017-08-22',
            'ItemPage'=> 1
        );


        ksort($parametre);

        $queryString = '';
        foreach($parametre as $k=>$v)
        $queryString .= '&'.AmazonAdv::url_encode($k).'='.AmazonAdv::url_encode($v);
        $queryString = substr($queryString, 1);

        $signature = AmazonAdv::url_encode(
            base64_encode(
                hash_hmac(
                    "sha256",
                    self::$METHOD."\n".self::$HOST."\n".self::$URI."\n".$queryString,
                    self::$SecretKey,
                    true
                )
            )
        );

        $queryString =  self::$HTTP.self::$HOST.self::$URI.'?'.$queryString.'&Signature='.$signature;

        if ( function_exists('curl_init') ) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $queryString);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 15);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $XML = curl_exec($curl);
            curl_close($curl);
        } else {
            $XML = file_get_contents($queryString);
        }

        return AmazonAdvItems::createWithXml(simplexml_load_string($XML));

    }
  // } //for
    public function __toString() {


        return 'AmazonAdv';


    }

}

?>
