<?php

namespace sample\src\model;

class Country
{

    protected $code      = null;
    protected $name      = null;
    protected $continent = null;
    protected $region    = null;
    protected $president = null;

    public function setCode($code)
    {
        $this->code = (string) $code;
    }

    public function getCode()
    {
        return (string) $this->code;
    }

    public function setName($name)
    {
        $this->name = (string) $name;
    }

    public function getName()
    {
        return (string) $this->name;
    }

    public function setContinent($continent)
    {
        $this->continent = (string) $continent;
    }

    public function getContinent()
    {
        return (string) $this->continent;
    }

    public function setRegion($region)
    {
        $this->region = (string) $region;
    }

    public function getRegion()
    {
        return (string) $this->region;
    }

    public function setPresident($president)
    {
        $this->president = (string) $president;
    }

    public function getPresident()
    {
        return (string) $this->president;
    }
}
