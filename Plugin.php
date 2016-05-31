<?php namespace LeoCavalcante\BackendUserTree;

use Event;
use Backend;
use BackendAuth;
use System\Classes\PluginBase;
use Backend\Models\User;
use Backend\Controllers\Users;
use LeoCavalcante\BackendUserTree\Models\Proxy;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'Backend User Tree',
            'description' => 'Manage backend users with the power of SimpleTree trait.',
            'author'      => 'LeoCavalcante',
            'icon'        => 'icon-users'
        ];
    }

    public function boot()
    {
        User::extend(function ($model) {
            $model->implement[] = 'LeoCavalcante\BackendUserTree\Behaviors\User';
            $model->hasOne['proxy'] = ['LeoCavalcante\BackendUserTree\Models\Proxy'];

            $model->bindEvent('model.afterSave', function() use ($model) {
                if (!$model->proxy) {
                    $proxy = new Proxy();
                    $proxy->user = $model;

                    $user = BackendAuth::getUser();
                    $userProxy = $user->proxy;
                    if (!$userProxy) {
                        $userProxy = new Proxy();
                        $userProxy->user = $user;
                    }

                    $proxy->parent = $userProxy;
                    $proxy->save();
                }
            });
        });
    }

    public function registerPermissions()
    {
        return [
            'leocavalcante.backendusertree.manage_permissions' => [
                'tab'   => 'Backend User Tree',
                'label' => 'Manage permissions'
            ],
            'leocavalcante.backendusertree.manage_groups' => [
                'tab'   => 'Backend User Tree',
                'label' => 'Manage groups'
            ],

            'leocavalcante.backendusertree.manage_backendusers' => [
                'tab'   => 'Backend User Tree',
                'label' => 'Manage backend users'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'leocavalcante.backendusertree.users' => [
                'label' => 'Backend Users',
                'url'   => Backend::url('LeoCavalcante/backendusertree/users'),
                'icon'  => 'icon-users',
                'order' => '500',
                'permissions' => ['leocavalcante.backendusertree.manage_backendusers'],
            ]
        ];
    }
}
