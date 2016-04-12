<?php

class Controller
{
    private $request;

    public function __construct()
    {
        $this->request = $_REQUEST;
    }

    public function action()
    {
        return $this->worldAction();
    }

    private function worldAction()
    {
        $world = new World();
        return $world->load($this->get('orderBy'), $this->get('orderType'));
    }

    private function get($name)
    {
        return isset($this->request[$name]) ? $this->request[$name] : null;
    }
}