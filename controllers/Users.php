<?php namespace LeoCavalcante\BackendUserTree\Controllers;

use BackendMenu;

class Users extends \Backend\Controllers\Users
{
    public $requiredPermissions = ['leocavalcante.backendusertree.manage_backendusers'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('LeoCavalcante.BackendUserTree', 'leocavalcante.backendusertree.users', 'leocavalcante.backendusertree.users');
    }

    public function listExtendQueryBefore($query)
    {
        if ($this->user->isSuperuser()) {
            return;
        }

        $children = $this->user->getAllChildrenIdList();
        $query->whereIn('id', $children);
    }

    public function formExtendFields($form)
    {
        parent::formExtendFields($form);

        if (!$this->user->hasPermission('leocavalcante.backendusertree.manage_permissions')) {
            $form->removeField('permissions');
        }

        if (!$this->user->hasPermission('leocavalcante.backendusertree.manage_groups')) {
            $form->removeField('groups');
        }
    }

    protected function generatePermissionsField()
    {
        return [
            'permissions' => [
                'tab' => 'backend::lang.user.permissions',
                'type' => 'LeoCavalcante\BackendUserTree\FormWidgets\PermissionEditor',
                'trigger' => [
                    'action' => 'disable',
                    'field' => 'is_superuser',
                    'condition' => 'checked'
                ]
            ]
        ];
    }
}
