<?php

class Table
{
    private $extension = '.json';
    
    private $name = null;
    private $data = array();
    private $nextId = 1;
    private $hasBeenModify = false;
    
    function __construct($name)
    {
        if(!empty($name)) {
            $filePath = Config::get('storagePath').$name.$this->extension;
            $data = Storage::read($filePath, true);
            
            $this->name = $name;
            
            if($data) {
                if(!empty($data)) {
                    if(!empty($data['data']) && !empty($data['nextId'])) {
                        $this->data = $data['data'];
                        $this->nextId = $data['nextId'];
                    }
                }
            }
        } else {
            ErrorPerso::add('Table name is empty');
        }
    }

    function __destruct()
    {
        if($this->hasBeenModify) {
            $arrayToWrite = array(
                "nextId" => $this->nextId,
                "data" => $this->data
            );

            $filePath = Config::get('storagePath').$this->name.$this->extension;
            $retour = Storage::write($filePath, $arrayToWrite, true);

            if(!$retour) {
                ErrorPerso::add('Error during writing the storage for "'.$this->name.'"');
            }
        }
    }
    
    public function add($data)
    {
        if(!empty($data) && is_array($data)) {
            $data['id'] = $this->nextId;
            $data['createdAt'] = Date::isoString();
            $this->data[] = $data;
            $this->nextId++;
            $this->hasBeenModify = true;
            
            return true;
        } else {
            ErrorPerso::add('Data is empty or not an array');
        }
        
        return false;
    }
    
    public function get($id)
    {
        foreach ($this->data as $data) {
            if($data['id'] == $id) {
                return $data;
            }
        }
        
        return null;
    }
    
    public function delete($id)
    {
        foreach ($this->data as $key => $data) {
            if($data['id'] == $id) {
                unset($this->data[$key]);
                $this->hasBeenModify = true;
                
                return true;
            }
        }
        
        return false;
    }
    
    public function update($dataUpdated)
    {
        foreach ($this->data as &$data) {
            if($data['id'] == $dataUpdated['id']) {
                $data = $dataUpdated;
                $this->hasBeenModify = true;
                
                return true;
            }
        }
        
        return false;
    }
    
    public function getAll()
    {
        return $this->data;
    }
    
    public function getCount()
    {
        return count($this->data);
    }
    
    public function getNextId()
    {
        return $this->nextId;
    }
}
