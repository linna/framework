---
layout: default
title: Controller
current_menu: controller
---

# Controller
The controller class for linna-framework, 
at the current state not provide any functionality for child controllers. 
In real web application must extend it for use framework Mvc model.

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