<?php namespace LeoCavalcante\BackendUserTree\Controllers;

use Backend;
use BackendMenu;
use BackendAuth;
use Backend\Models\UserGroup;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

/**
 * Backend user tree controller
 */
class Users extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['leocavalcante.backendusertree.manage_backendusers'];

    public $bodyClass = 'compact-container';

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

    public function update($recordId, $context = null)
    {
        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function formExtendFields($form)
    {
        if (!$this->user->isSuperUser()) {
            $form->removeField('is_superuser');
        }

        if ($this->user->hasPermission('leocavalcante.backendusertree.manage_permissions')) {
            $form->addTabFields($this->generatePermissionsField());
        }

        if (!$form->model->exists) {
            $defaultGroupIds = UserGroup::where('is_new_user_default', true)->lists('id');

            $groupField = $form->getField('groups');
            $groupField->value = $defaultGroupIds;
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
                'type' => 'Backend\FormWidgets\PermissionEditor',
                'trigger' => [
                    'action' => 'disable',
                    'field' => 'is_superuser',
                    'condition' => 'checked'
                ]
            ]
        ];
    }
}
