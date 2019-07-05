<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Description of BaseModel
 *
 * @author Niels.Klazenga <Niels.Klazenga at rbg.vic.gov.au>
 */
class BaseModel extends Model {
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->relations)) {
            return parent::getAttribute($key);
        } else {
            return parent::getAttribute(snake_case($key));
        }
    }

    public function setAttribute($key, $value)
    {
        return parent::setAttribute(snake_case($key), $value);
    }
    
    public function getDateFormat()
    {
         return 'Y-m-d H:i:s.u';
    }

    public function getNamespaceAttribute()
    {
        return \App\Models\NamespaceModel::find($this->namespace_id);
    }

    public function getUriAttribute()
    {
        $base = 'https://id.biodiversity.org.au/';
        if ($this->namespace) {
            $base .=  Str::kebab(Str::camel($this->table)) . '/' . $this->namespace->rdf_id . '/';
        }
        else {
            $base .= 'vocabulary/' . Str::kebab(Str::camel($this->table)) . '/';
        }
        if ($this->rdf_id) {
            return $base . $this->rdf_id;
        } 
        return $base . $this->id;
    }

}
