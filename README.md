# Backend User Tree
Adds [SimpleTree trait](https://octobercms.com/docs/database/traits#simple-tree) functionality to backend users (administrators) thought a Proxy model.

## Why?

You will be able, for example, to create levels of administrators like **Chief Editors** and **Editors** to your [RainLab.Blog](https://octobercms.com/plugin/rainlab-blog)
where **Chief Editors** can create **Editors** and see their posts as their childrens posts, but without seeing their siblings posts or siblings Editors posts.

This can be used for whatever [Model](http://octobercms.com/docs/database/model) you want. Just applying the following code to `listExtendQueryBefore` hook:


    $query->whereIn('user_id', $this->user->getAllChildrenIdList());

Where `user_id` is the foreign key on the model to represent the user who created it.
This can be archived using `beforeSave` hook, adding the current logged in user to the model:

    $model->user = BackendAuth::getUser();
    // or
    $model->user = $this->user;

Don't forget to add `user` to `belongsTo` property equals to `Backend\Models\User` in the models that needs ownership. It's a basic Eloquent relation.

## Permissions

Users will be able to define permissions to their children based on their own permissions.
