<?php

namespace controllers;

use models\mReservering;

class cReservering
{
    public $data;
    private $model;


    public function __construct(){
        $this->model = new mReservering();
        $_GET["header"] = true;
    }



    public function index(){
        $_GET["page_title"] = "Reserveringen";
        $_GET["template"] = "private";

        $this->data = array();


        // get statuses
        $this->data["states"] = $this->model->getTable("status");

        // get reservations
        $_GET["id"] = ($_GET["id"] == "") ? 1 : $_GET["id"];
        $this->data["reservations"] = $this->model->getReservationsFromState($_GET["id"]);
    }



    public function statussen(){
        $_GET["page_title"] = "Statussen beheren";
        $_GET["template"] = "private";

        $this->data["message"] = (!empty($_POST)) ? $this->model->newStatus() : "";

        //if(!empty($_POST)){
        //	$this->data["message"] = $this->model->newStatus();
        //}else{
        //	$this->data["message"] = "";
        //}

        $this->data["list"] = $this->model->getStates();
    }



    public function delstatus(){
        $_GET["template"] = "private";
        $_GET["page_title"] = "Status verwijderen";

        //if($_SESSION["user"]["role"] == 3){
            $this->model->delStatus();
        //}else{
        //    header("location: /pages/nopermission");
        //}
    }



}