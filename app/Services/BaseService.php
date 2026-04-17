<?php
namespace App\Services;
use App\Models\User;
use App\Models\Post;

class BaseService{
    protected $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
    public function create(array $data){
        return $this->model->create($data);
    }
    public function update($id, $data){
        return $this->model->where('id', $id)->update($data);
    }
    public function delete($id){
        return $this->model->where('id', $id)->delete();
    }
}
