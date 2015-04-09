# PageCache
PageCache is basically used to cached the static page content or you can cached the content that you thing not update on frequent basis

* Steps to Enable and usage of PageCache Module

Step 1 :To enable the Module in ZF2, Include the module name inside application.config.php file
```php
return array(
  ...,
  ...,
  'PageCache'
);
```

Step 2: Please check the cache directory exist at module root level, 
Please create fullpage/ directory inside data/cache/ dir. and should have full permission to write and create files inside api_log dir.

Step 3: Now enable the cache option in your application route inside module.config.php file, where you want cache should happen. 
For example:
```php
    'router' => array(
        'routes' => array(
            'pages' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/pages[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Pages\Controller\Index',
                        'action' => 'index',
                        'cache' => true
                    )
                )
            )
            
        )
    ),
```
Step 4: When you run your application and your route executed then PageCache modue will create cache dir with hash-key concatenated inside fullpage/ dir.

When the sme route called then the module will render the content on your browser without hit to the contorller and view-model.

It will help to improve your application performance.
