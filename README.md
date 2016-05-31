# Backend User Tree
Adds SimpleTree trait functionality to backend users.

You can make backend users create backend user children and later restrict lists to display only what belongs to logged user children.

## Why?

You will be able, for example, to create Chief Editors and Editors to your [RainLab.Blog](https://octobercms.com/plugin/rainlab-blog) where Chief Editors can create Editors and see their posts, but with seeing their siblings posts or siblings Editors posts.

This can be used for whatever Model you what to just applying to `listExtendQueryBefore` the following code:

```php
$query->whereIn('user_id', $this->user->getAllChildrenIdList(););
```

Where `user_id` is the foreign key on the model to represent the user who created it.
This can be archived using `beforeSave` and adding the current logged in user to the model:

```php
$model->user = BackendAuth::getUser();
```

or

```php
$model->user = $this->user
```

Don't forget to add `belongsTo` `user` equals to `Backend\Models\User` in the models that needs ownership.
