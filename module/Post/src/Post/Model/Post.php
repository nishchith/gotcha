<?php

namespace Post\Model;

class Post
{   
    public $id;
    public $title;
    public $description;
    public $fileType; // TEXT|VIDEO|IMAGE
    public $filePath;
    public $status; // PFA| APPROVED| REJECTED| FEATURED
    public $likes;
    public $dateAdded;
    public $dateLastUpdated;
    public $verifiedBy;
    public $featureListedBy;
    public $active;

    public function exchangeArray($data) 
    {
        $this->id   = (!empty($data['id'])) ? $data['id'] : null;
        $this->title    = (!empty($data['title'])) ? $data['title'] : null;
        $this->description  = (!empty($data['description'])) ? $data['description'] : null;
        $this->fileType = (!empty($data['fileType'])) ? $data['fileType'] : null;
        $this->filePath = (!empty($data['filePath'])) ? $data['filePath'] : null;
        $this->status = (!empty($data['verified'])) ? $data['verified'] : null;
        // $this->verified = (!empty($data['verified'])) ? $data['verified'] : null;
        // $this->featured = (!empty($data['featured'])) ? $data['featured'] : null;
        $this->likes    = (!empty($data['likes'])) ? $data['likes'] : null;
        // $this->views    = (!empty($data['views'])) ? $data['views'] : null;
        $this->dateAdded    = (!empty($data['dateAdded'])) ? $data['dateAdded'] : null;
        $this->verifiedBy   = (!empty($data['verifiedBy'])) ? $data['verifiedBy'] : null;
        $this->featuredBy   = (!empty($data['featuredBy'])) ? $data['featuredBy'] : null;
        $this->dateLastUpdated  = (!empty($data['dateLastUpdated'])) ? $data['dateLastUpdated'] : null;
        $this->active   = (!empty($data['active'])) ? $data['active'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }  
}