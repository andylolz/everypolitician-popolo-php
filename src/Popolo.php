<?php

namespace mySociety\EveryPoliticianPopolo;

class Popolo
{
    use Traits\ArrayGetterTrait;

    private $jsonData;
    private $collections = [
        'persons'       => ['Person', 'PersonCollection'],
        'organizations' => ['Organization', 'OrganizationCollection'],
        'memberships'   => ['Membership', 'MembershipCollection'],
        'areas'         => ['Area', 'AreaCollection'],
        'posts'         => ['Post', 'PostCollection'],
        'events'        => ['Event', 'EventCollection'],
    ];

    /**
     * Create a new Popolo Instance from
     */
    public function __construct($jsonData)
    {
        $this->jsonData = $jsonData;
    }

    public function __get($prop)
    {
        if (array_key_exists($prop, $this->collections)) {
            $c = $this->collections[$prop];
            $objectClass = 'mySociety\\EveryPoliticianPopolo\\Objects\\'.$c[0];
            $collectionClass = 'mySociety\\EveryPoliticianPopolo\\Collections\\'.$c[1];
            $dataArr = $this->arrGet($this->jsonData, $prop, []);
            return new $collectionClass($dataArr, $objectClass, $this);
        }
        trigger_error('Undefined property: '.__CLASS__.'::$'.$prop, E_USER_ERROR);
    }

    /**
     * Construct from filename
     *
     * @param string $filename name of Popolo json file
     *
     * @return $this
     */
    public static function fromFilename($filename)
    {
        $contents = file_get_contents($filename);
        $jsonData = json_decode($contents, true);
        $instance = new self($jsonData);
        return $instance;
    }

    /**
     * Construct from URL
     *
     * @param string $url location of Popolo json file
     *
     * @return $this
     */
    public static function fromUrl($url, $client = null)
    {
        $client = $client ?: new \GuzzleHttp\Client();
        $response = $client->get($url);
        $jsonData = json_decode($response->getBody()->getContents(), true);
        $instance = new self($jsonData);
        return $instance;
    }
}
