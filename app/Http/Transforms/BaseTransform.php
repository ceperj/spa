<?php

namespace App\Http\Transforms;

use Error;
use Vinkla\Hashids\Facades\Hashids;

class BaseTransform
{

    protected function model($hashid, $model){
        $id = $this->modelId($hashid, $model);
        return $model::findOrFail($id);
    }
    
    protected function modelId($hashid, $model){
        $array = Hashids::connection($model)->decode($hashid);
        if (count($array) !== 1)
            throw new Error("Hash $hashid does not correspond to model $model id.");
        return $array[0];
    }

    protected function removeIntegerSeparators($input){
        return str_replace(['.', '-'], ['', ''], $input);
    }

}