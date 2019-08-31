<?php
/**
 * Created by PhpStorm.
 * User: freddy-Admin
 * Date: 20/11/2018
 * Time: 11:24 PM
 */

namespace modulos\use_template;


use plataforma\ControllerBase;

class Controller extends ControllerBase
{

    public function perform()
    {
     $this->view('perform');
    }

    public function beforeFilter(){}

    public function tabs()
    {
       $this->view('use_tabs');
    }
    public function forms(){
        $this->view('use_forms');
    }
    public function pageBlank(){
        $this->view('page_blank');
    }

    public function typoGraphy()
    {
        $this->view('typo_graphy');
    }
    public function widget()
    {
        $this->view('page_widget');
    }
}