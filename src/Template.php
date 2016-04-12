<?php

class Template
{
    private $method;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function show(array $data)
    {
        return ($this->method == 'POST') ? $this->showJson($data) : $this->showHtml($data);
    }

    private function showJson(array $data)
    {
        return json_encode($data);
    }

    private function showHtml(array $data)
    {
        $html = file_get_contents('template/view.html');

        //Ну да, такой себе темплейтор :)
        $html = str_replace('#!world!#', json_encode($data), $html);

        return $html;
    }
}