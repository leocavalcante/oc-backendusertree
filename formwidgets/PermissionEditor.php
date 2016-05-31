<?php namespace LeoCavalcante\BackendUserTree\FormWidgets;

use BackendAuth;

class PermissionEditor extends \Backend\FormWidgets\PermissionEditor
{
    public function render()
    {
        $this->prepareVars();

        $user = BackendAuth::getUser();
        $permissions = array_keys($user->permissions);

        foreach ($this->vars['permissions'] as $key => &$tab) {
            foreach ($tab as $_key => $permission) {
                if (!in_array($permission->code, $permissions)) {
                    unset($tab[$_key]);
                }
            }

            if (empty($tab)) {
                unset($this->vars['permissions'][$key]);
            }
        }

        return $this->makePartial('~/modules/backend/formwidgets/permissioneditor/partials/_permissioneditor.htm');
    }

    protected function loadAssets()
    {
        $this->addCss('/modules/backend/formwidgets/permissioneditor/assets/css/permissioneditor.css', 'core');
        $this->addJs('/modules/backend/formwidgets/permissioneditor/assets/js/permissioneditor.js', 'core');
    }
}
