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
        if (!$this->hasProxy()) {
            return null;
        }

        if (!$this->hasParentProxy()) {
            return null;
        }

        return $this->model->proxy->parent->user;
    }

    public function getParentAttribute()
    {
        if (!$this->hasProxy()) {
            return 'none';
        }

        if (!$this->hasParentProxy()) {
            return 'me';
        }

        if (BackendAuth::getUser()->id == $this->getParent()->id) {
            return 'me';
        }

        return $this->getParent()->login;
    }

    private function hasProxy()
    {
        return !is_null($this->model->proxy);
    }

    private function hasParentProxy()
    {
        return !is_null($this->model->proxy->parent);
    }
}
