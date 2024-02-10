<?php

class Product{
    private ?int $id;
    private ?string $code;
    private ?string $description;
    private ?float $price;


    public function __construct(?int $id=null, ?string $code=null, ?string $description=null, ?float $price=null){
        $this->id = $id;
        $this->code = $code;
        $this->description = $description;
        $this->price = $price;
    }

    public function getId():?int{
        return $this->id;
    }

    public function setId(?int $id){
        $this->id = $id;
    }

    public function getCode():?string{
        return $this->code;
    }

    public function setCode(?string $code){
        $this->code = $code;
    }


    public function getDescription():?string{
        return $this->description;
    }

    public function setDescriptoin(?string $description){
         $this->description = $description;
    }


    public function getPrice():?float{
        return $this->price;
    }

    public function setPrice(?float $price){
        $this->price = $price;
    }

    public function __toString() {
        return "Product{[id=$this->id][code=$this->code][description=$this->description][price=$this->price]}";
    }

}