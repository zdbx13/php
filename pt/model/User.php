<?php

class User{
    private ?int $id;
    private ?string $username;
    private ?string $password;
    private ?string $role;
    private ?string $email;
    private ?DateTime $dob;

    public function __construct(?int $id=null, ?string $username=null, ?string $password=null, ?string $role=null, ?string $email=null, ?DateTime $dob=null){
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->email = $email;
        $this->dob = $dob;
    }

    public function getId():?int{
        return $this->id;
    }

    public function setId(?int $id){
        $this->id = $id;
    }

    public function getUsername():?string{
        return $this->username;
    }

    public function setUsername(?string $username){
        $this->username = $username;
    }


    public function getPassword():?string{
        return $this->password;
    }

    public function setPassword(?string $password){
        $this->password = $password;
    }


    public function getRole():?string{
        return $this->role;
    }

    public function setRole(?string $role){
        $this->role = $role;
    }


    public function getEmail():?string{
        return $this->email;
    }

    public function setEmail(?string $email){
        $this->email = $email;
    }


    public function getDob():?DateTime{
        return $this->dob;
    }

    public function setDob(?DateTime $dob){
        $this->dob = $dob;
    }

    public function __toString() {
        return "User{[id=$this->id][username=$this->username][password=$this->password][role=$this->role][email=$this->email][dob=$this->dob]}";
    }

}