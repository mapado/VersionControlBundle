Version Controller
==================
Abstract Object Version Controller

The purpose of this project is to have a version history of an abstract object.

1. [Project principle](https://github.com/mapado/VersionControlBundle/blob/master/Resources/doc/1-principle.md)
2. [Webservice implementation](https://github.com/mapado/VersionControlBundle/blob/master/Resources/doc/2-webservice.md)

## Installation
### Get the bundle

Add this in your composer.json

```json
{
	"require": {
		"mapado/version-control-bundle": "v1.0.*"
	}
}
```

and then run

```sh
php composer.phar update
```
or 
```sh
composer update
```
if you installed composer globally.

### Add the classes to your Kernel
```php
new Mapado\VersionControlBundle\MapadoVersionControlBundle(),
```

## Webservice Configuration
Add this to your routing.yml :
```
mapado_version_control:
    resource: "@MapadoVersionControlBundle/Controller/"
    type:     annotation
    prefix:   /version_control/
```
