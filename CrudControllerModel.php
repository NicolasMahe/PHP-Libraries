<?php

abstract class CrudControllerModel
{
    protected $storage = null;
    protected $verbose = false;
    protected $name = null;
            
    function __construct($name, $verbose=false)
    {
        if(!empty($name)) {
            $this->storage = new Table($name);
            $this->verbose = $verbose;
            $this->name = $name;
        } else {
            Error::add('name is empty');
            Response::setStatus('error');
        }
    }
    
    public function get()
    {
        $id = Request::get('id');
        
        if(!empty($id)) {
            $data = $this->storage->get($id);
        } else {
            $data = $this->storage->getAll();
        }
		
        Response::setStatus('success');
        Response::setData($data);
    }
    
    public function add()
    {
        $data = Request::post('data');

        if(!empty($data)) {
            $retour = $this->storage->add($data);
            
            if($retour) {
                if($this->verbose) {
                    Log::add($this->name, "add", "success", $this->name." item id '".($this->storage->getNextId() - 1)."' successfully added");
                }
                Response::setStatus('success');
            } else {
                if($this->verbose) {
                    Log::add($this->name, "add", "error", $this->name." item id '".($this->storage->getNextId() - 1)."' error during add");
                }
                Error::add('error during add');
                Response::setStatus('error');
            }
        } else {
            Error::add('data is empty');
            Response::setStatus('error');
        }
    }
    
    public function update()
    {
        $data = Request::post('data');

        if(!empty($data) && !empty($data['id'])) {
            $retour = $this->storage->update($data);
            
            if($retour) {
                if($this->verbose) {
                    Log::add($this->name, "update", "success", $this->name." item id '".$data['id']."' successfully updated");
                }
                Response::setStatus('success');
            } else {
                if($this->verbose) {
                    Log::add($this->name, "update", "error", $this->name." item id '".$data['id']."' error during update");
                }
                Error::add('error during update');
                Response::setStatus('error');
            }
        } else {
            Error::add('data and/or id is empty');
            Response::setStatus('error');
        }
    }
    
    public function delete()
    {
        $id = Request::get('id');

        if(!empty($id)) {
            $retour = $this->storage->delete($id);
            
            if($retour) {
                if($this->verbose) {
                    Log::add($this->name, "delete", "success", $this->name." item id '".$id."' successfully deleted");
                }
                Response::setStatus('success');
            } else {
                if($this->verbose) {
                    Log::add($this->name, "delete", "error", $this->name." item id '".$id."' error during delete");
                }
                Error::add('error during delete');
                Response::setStatus('error');
            }
        } else {
            Error::add('data and/or id is empty');
            Response::setStatus('error');
        }	
    }
}
