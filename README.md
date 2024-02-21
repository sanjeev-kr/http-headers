# Sanjeev_HttpHeaders Magento 2 module

Sanjeev_HttpHeaders is a module for Magento 2. This module helps to add http headers without making changes in nginx and apache configuration and/or .htaccess file.

## Install with Composer
```
composer require sanjeev-kr/http-headers
php bin/magento module:enable Sanjeev_HttpHeaders
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```

## Install Manually
- Download zip and extract
- Create a new directory Sanjeev/HttpHeaders in app/code directory and copy files from http-headers folder and paste files in Sanjeev/HttpHeaders directory.
- And run below commands

```
php bin/magento module:enable Sanjeev_HttpHeaders
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```
## How to enable Cookie banner
- In admin, Go to Stores -> Settings -> Configuration -> General.
- Click on Web under General tab.
- Click on Http Headers tab and enable it.

## Header Sample file
- Header sample file headers.json is placed in headers folder.
- Move headers.json file to app/etc and modify as per your http headers requirements. 

