<?php

interface ClearUriInterface
{

    function clear($uri);
}

class BaseClear implements ClearUriInterface
{

    public $uri;

    function __construct($uri)
    {
        $this->uri = $uri;
    }

    function clear($uri)
    {
        $this->uri = $uri;
        $this->uri = preg_replace("/(\/)?doc\//", "", $this->uri);
        $this->uri = preg_replace("/\/$/", "", $this->uri);
    }

}

abstract class UriDecorator implements ClearUriInterface
{

    private $MyChecker = null;
    public $uri;
    protected $pattern;

    // конструктор запоминает следующий декоратор
    function __construct(ClearUriInterface $MyChecker)
    {
        $this->MyChecker = $MyChecker;
    }

    abstract function createPattern();

    protected function setPattern()
    {
        $pattern = $this->createPattern();
        $this->pattern = $pattern;
    }

    function clear($uri)
    {
        $this->setPattern();
        $uri = preg_replace($this->pattern, "", $uri);
        $this->uri = $uri;
        if ($this->MyChecker != null)
            $this->MyChecker->clear($uri);
        else
            return false;
    }

    public function getUri()
    {
        return $this->uri;
    }

}

class ClearSort extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/sort\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearIndent extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/indent\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearAsc extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/asc\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearPrice extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/price\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearAt extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/at\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearPage extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/page\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearCount extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/count\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearPcount extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/pcount\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearBrand extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/brand\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearPrint extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/print\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearLng extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/lng\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearSearch extends UriDecorator
{

    function createPattern()
    {
        $pattern = "/\/text\/([^\/]*)/";
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}

class ClearGet extends UriDecorator
{

    function createPattern()
    {
        $pattern = '/(\?.*)/';
        return $pattern;
    }

    function clear($uri)
    {
        parent::clear($uri);
    }

}
