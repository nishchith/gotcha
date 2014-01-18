<?php

namespace User\Model;

class User
{   
    public $id;
    public $firstName;
    public $lastName;
    public $gender;
    public $email;
    public $mobile;
    public $dob;
    public $preganancyStatus;
    public $idNumber;
    public $addressId;
    public $username;
    public $dateAdded;
    public $dateLastUpdated;
    public $active;

    public function exchangeArray($data) 
    {
        $this->id   = (!empty($data['id'])) ? $data['id'] : null;
        $this->firstName    = (!empty($data['firstName'])) ? $data['firstName'] : null;
        $this->lastName = (!empty($data['lastName'])) ? $data['lastName'] : null;
        $this->gender   = (!empty($data['gender'])) ? $data['gender'] : null;
        $this->email    = (!empty($data['email'])) ? $data['email'] : null;
        $this->mobile   = (!empty($data['mobile'])) ? $data['mobile'] : null;
        $this->dob  = (!empty($data['dob'])) ? $data['dob'] : null;
        $this->preganancyStatus = (!empty($data['preganancyStatus'])) ? $data['preganancyStatus'] : null;
        $this->idNumber = (!empty($data['idNumber'])) ? $data['idNumber'] : null;
        $this->addressId    = (!empty($data['addressId'])) ? $data['addressId'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->dateAdded    = (!empty($data['dateAdded'])) ? $data['dateAdded'] : null;
        $this->dateLastUpdated  = (!empty($data['dateLastUpdated'])) ? $data['dateLastUpdated'] : null;
        $this->active   = (!empty($data['active'])) ? $data['active'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }  
}