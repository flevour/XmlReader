<?php

namespace Flevour\XMLSAX;

/**
 * Description of AbstractXmlDataSource
 *
 * @author flevour
 */
abstract class XMLReader implements \Iterator
{

    protected $reader;
    protected $tagName;
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        $this->tagName = $this->getTagName();
        $this->reader = new \XMLReader;
        $this->reader->xml($this->data);
        // move to the first <TAGNAME /> node
        while ($this->reader->read() && $this->reader->name !== $this->tagName)
            ;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * Defines the tag to loop for in the XML file.
     *
     * @return string the tag name (case sensitive) containing a single information in the XML.
     */
    abstract public function getTagName();

    /**
     * Defines a mapper from a SimpleXMLElement to an array containing the extracted information.
     *
     * @return array a keyed array containing the data extracted from the current node.
     */
    abstract protected function mapData(\SimpleXMLElement $node);

    /**
     * Implementazione Iterator
     */
    public function current()
    {
        $doc = new \DOMDocument();
        /*if (isset($_GET['anna'])) {
        	var_dump(simple_xml_load_string($this->data));
        	var_dump($this->data);
        }*/
        
        $node = simplexml_import_dom($doc->importNode($this->reader->expand(), true));

        $data = $this->mapData($node);

        return $data;
    }

    public function next()
    {
        // go to next <TAG_NAME />
        $this->reader->next($this->tagName);
    }

    /**
     * Quando l'xml e' stato passato tutto, reader->name sara' nullo pertanto anche
     * questo iteratore deve terminare.
     * @return boolean
     */
    public function valid()
    {
        return $this->reader->name === $this->tagName;
    }

    /**
     * Non implementato, non ha senso parlare di una chiave in questo contesto.
     * Il curorse nell'xml puo' solo avanzare.
     */
    public function key()
    {

    }

    /**
     * Il curorse nell'xml puo' solo avanzare.
     */
    public function rewind()
    {

    }

}