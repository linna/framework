---
layout: default
title: Controller
current_menu: controller
---

# Controller
The controller class for linna-framework, 
at the current state not provide any functionality for child controllers. 
In real web application must extend it for use framework Mvc model.

When extends a controller it's possible declare methods for execute code
before and/or after it execution. If a controller is created for do many jobs, 
you can declare methods before() and after() for one specific job.

See example below
```php
namespace App\Controllers;

use Linna\Mvc\Controller;
use App\Models\FooModel;

/**
 * Foo Page Controller
 *
 */
class FooController extends Controller
{
    /**
     * Constructor
     *
     * @param FooModel $model
     */
    public function __construct(FooModel $model)
    {
        parent::__construct($model);
    }

    public function before()
    {
        //global before
        //put here code to execute before controller action
        //code will be executed independently from requested action,
        //exampleActionOne() or exampleActionTwo()
    }
    
    public function beforeExampleActionOne()
    {
        //action specific before
        //put here code that will execute before exampleActionOne() action only
    }

    public function exampleActionOne()
    {
        $model->doActionOne();
    }
    
    public function afterExampleActionOne()
    {
        //action specific after
        //put here code that will execute after exampleActionOne() action only
    }

    public function exampleActionTwo()
    {
        $model->doActionTwo();
    }
    
    public function after()
    {
        //global after
        //put here code to execute after controller action
        //code will be executed independently from requested action,
        //exampleActionOne() or exampleActionTwo()
    }
}
```

## Class Structure

Properties
- no public properties

Methods
- __construct()

### __construct()
Call it from constructor when extend Controller

#### Parameters
*Linna\Mvc\Model* **$model**<br/>

#### Usage
Example below show how extend Controller class and pass it the Model
```php
namespace App\Controllers;

use Linna\Mvc\Controller;
use App\Models\FooModel;

/**
 * Foo Page Controller
 *
 */
class FooController extends Controller
{
    /**
     * Constructor
     *
     * @param FooModel $model
     */
    public function __construct(FooModel $model)
    {
        parent::__construct($model);
    }
}
```