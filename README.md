# Laravel Abilities
A convenient way to encapsulate model conditions business logic

Laravel Gates are an excellent way to separate logic of access and permissions to models, but if we need to separate only models conditions not related to users (ex: if is valid to Publish a Post at certain state regardless of user)

This package adds a layer on top of laravel Gates, so first "ability" validity is checked then user access and permission is checked though normal laravel Gates if present.

Also, this package adds abilities and policies attributes to your models through `HasHabilities` trait, useful if you need available "actions" at the front end.

## Installation

```console
$ composer require iutrace/laravel-abilities
```

## Usage