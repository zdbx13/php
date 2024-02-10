<?php

class Order{
    private ?int $id;
    private ?DateTime  $creationDate;
    private ?string $delMethod;
    private ?float $customer;


    public function __construct(?int $id=null, ?DateTime $creationDate=null, ?string $delMethod=null, ?float $customer=null){
        $this->id = $id;
        $this->creationDate = $creationDate;
        $this->delMethod = $delMethod;
        $this->customer = $customer;
    }

    public function getId():?int{
        return $this->id;
    }

    public function setId(?int $id){
        $this->id = $id;
    }

    public function getCreationDate():?DateTime {
        return $this->creationDate;
    }

    public function setCreationDate(?DateTime $creationDate){
        $this->creationDate = $creationDate;
    }


    public function getDelMethod():?string{
        return $this->delMethod;
    }

    public function setDelMethod(?string $delMethod){
         $this->delMethod = $delMethod;
    }


    public function getCustomer():?float{
        return $this->customer;
    }

    public function setCustomer(?float $customer){
        $this->customer = $customer;
    }

    public function __toString() {
        return "Order{[id=$this->id][creationDate=$this->creationDate][delMethod=$this->delMethod][customer=$this->customer]}";
    }

}