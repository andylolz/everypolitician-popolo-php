<?php

namespace mySociety\EveryPoliticianPopolo;

class Popolo
{
    use Traits\ArrayGetterTrait;

    /**
     * Create a new Popolo Instance from
     */
    public function __construct($jsonData)
    {
        $this->jsonData = $jsonData;

        // TODO: consider using __get here instead
        $classes = [
            'persons'       => 'PersonCollection',
            'organizations' => 'OrganizationCollection',
            'memberships'   => 'MembershipCollection',
            'areas'         => 'AreaCollection',
            'posts'         => 'PostCollection',
            'events'        => 'EventCollection',
        ];

        foreach ($classes as $property => $collectionClassName) {
            $collectionClassPath = 'mySociety\\EveryPoliticianPopolo\\Collections\\' . $collectionClassName;
            $dataArr = $this->arrGet($jsonData, $property, []);
            $this->$property = new $collectionClassPath($dataArr, $this);
        }
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
