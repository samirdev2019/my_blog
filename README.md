# My blog [![SymfonyInsight](https://insight.symfony.com/projects/6412270e-6006-468e-ac24-01b15f675314/big.svg)](https://insight.symfony.com/projects/6412270e-6006-468e-ac24-01b15f675314)

The project is therefore to develop a professional blog. This website is divided into two large groups of pages:

* pages useful to all visitors;
>a visitor can see the list of posts, by consulting and leaving a comment, this latter is visible on the detail of the article only if it is validated by an administrator.
* pages to administer the blog.
>an administration part which will have to be accessible only to the registered and validated users.
## Requirements
My blog require 
- ``PHP 7.2.10 or an version ulterieure to run``
- ``MySql 5.7.23``
### this project use
- ``Template engine for PHP TWIG 
v2.7``https://twig.symfony.com/
- ``Bootstrap 4.3.1`` 

## Installation
* Install [composer] the Dependency Manager for PHP https://getcomposer.org/
`Latest: v1.8.6`
 
[composer] - `$ composer install`

* Upgrades your dependencies to the latest version according to composer.json, and updates the composer.lock file.

```PHP "autoload":{
        "psr-4": {
            "App\\":"App/"
        }
    },
    "require": {
        "twig/twig": "2.7"
    },
```

`[composer]- $ composer u `



### usage
if you use the apach server in your machine, or a development environement in local like as wampserver or easyPHP copy the the project in your dossier racin WWW and request the public folder,
or you can use the server php, in your terminal tape 
>$ $ cd ~/public_html
>$ php -S localhost:8000 

* for the first test you can use this parameters for connecting like admin

email : admin@gmail.com
password : azerty

send email

There's no need to configure anything to run the application just for sending email
it need to configure your SMTP in the php.ini file

### TODO 
this project will needs somme add foctionalities in the future like as :
* using the categories for posts
* add the pagination 
* add a filter for looking posts by author, date or title


