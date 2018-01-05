# Bonnier - ContentHub Editor Plugin

This plugin enables your WordPress site to integrate with the Bonnier ContentHub and edit content.

### Requirements

- WordPress 4.7.2 or higher
- ACF PRO 5.x
- PHP 5.6 or higher

### Installation/Configuration

Install through composer:

``` bash
composer require bonnier/wp-contenthub-editor
```

### Hooks

##### Slug change
Triggers when the composite slug is changed
 ``` php
 add_action(WpComposite::SLUG_CHANGE_HOOK, function($postId, $oldLink, $newLink){
    // Do your magic here
 }, 10, 3); 
 ```
Please note that the links provided are the absolute links and not just post links
