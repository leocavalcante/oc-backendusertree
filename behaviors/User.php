<?php namespace LeoCavalcante\BackendUserTree\Behaviors;

use BackendAuth;

class User extends \October\Rain\Extension\ExtensionBase
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getAllChildrenIdList()
    {
        return $this->model->proxy->getAllChildren()->lists('user_id');
    }

    public function getParent()
    {
        return $this->model->proxy->parent->user;
    }

    public function getParentAttribute()
    {
        if (BackendAuth::getUser()->id == $this->getParent()->id) {
            return 'Me';
        }

        return $this->getParent()->login;
    }
}
