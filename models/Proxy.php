<?php namespace LeoCavalcante\BackendUserTree\Models;

use Model;

class Proxy extends Model
{
    use \October\Rain\Database\Traits\SimpleTree;

    public $table = 'leocavalcante_backendusertree_proxies';

    public $belongsTo = [
        'user' => ['Backend\Models\User']
    ];
}
