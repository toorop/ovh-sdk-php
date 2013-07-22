<?php

/**
 * Copyright 2013 Stéphane Depierrepont (aka Toorop)
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://www.apache.org/licenses/LICENSE-2.0.txt
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

/*
 * @ todo Task
 *
 *  Setter : sanity check
 *  doc & comment
 */

namespace Ovh\Common;

class Task
{
    protected $id;
    protected $type;
    protected $state;

    public function __construct ($json=false){
        if($json){
            $o=json_decode($json);
            if(!$o)
                throw new \BadMethodCallException('$json parameter is not a valid JSON string');
            if(!isset($o->id))
                throw new \BadMethodCallException('No id found in JSON.');
            if(!isset($o->type))
                throw new \BadMethodCallException('No type found in JSON.');
            if(!isset($o->state))
                throw new \BadMethodCallException('No state found in JSON.');

            $this->setId($o->id);
            $this->setType($o->type);
            $this->setState($o->state);

        }
    }


    public function setId($id){
        $this->id=$id;
    }
    public function getId(){
        return $this->id;
    }
    public function setType($type){
        $this->type=$type;
    }
    public function getType(){
        return $this->type;
    }
    public function setState($state){
        $this->state=$state;
    }
    public function getState(){
        return $this->state;
    }




}
