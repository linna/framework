---
layout: default
title: Model
current_menu: model
---

# Model

Model class is the father class for every concrete Model in application. He work with View as Observer pattern where Model is the Subject and View is the Observer.

```php
use Linna\Mvc\Model;

class ProductModel extends Model
{
    private $dBase;

    public function __construct(Database $dBase)
    {
        parent::__construct();
        
        $this->dBase = $dBase->connect();
    }
    
    //ProductModel other code
}
```

## Class Structure

Properties
- getUpdate
- observers

Methods
- __construct()
- attach()
- detach()
- notify()

### getUpdate
visibility: *public*<br/>
type: *array*<br/>
Utilize it for store data that will pass to a View
```php
use Linna\Mvc\Model;

class ProductModel extends Model
{
    //ProductModel other code

    public function delete(int $productId)
    {
        $delete = $this->dBase->prepare('DELETE FROM products WHERE product_id = :id');
        $delete->bindParam(':id', $productId, \PDO::PARAM_INT);
        $delete->execute();

        $this->getUpdate = ($delete->rowCount() === 1) ? 
        ['delete_successful' => true] : 
        ['delete_successful' => false];
    }
    
    //ProductModel other code
}
```
The above example will pass to View updated data for output

### observers
visibility: *private*<br/>
type: *\SplObjectStorage*<br/><br/>
This property contain the list of Observer objects that will receive notifications

### __construct()
visibility: *public*<br/><br/>
Call it from constructor when extend Model
```php
parent::__construct();
```

### attach()
visibility: *public*<br/>
param: *\SplObjectStorage*<br/><br/>
Attach an Observer class to this Subject for updates when occour a subject state change
```php
$model = new ProductModel;
        
$view = new ProductView($model, new ProductTemplate);
        
$controller = new ProductController($model);
        
$model->attach($view);
```
Now when a change occurs, the Model may notify to the View.

### detach()
visibility: *public*<br/>
param:*\SplObjectStorage*<br/><br/>
Like attach(), this method is used for exclude an object from notifies.
```php
$model->attach($view);
$model->detach($view);
```
### notify()
*public*<br/>
After data change call this method for do notifies to Model Oservers
```php
$model = new ProductModel;
        
$view = new ProductView($model, new ProductTemplate);
        
$controller = new ProductController($model);
        
$model->attach($view);

//Delete product with id n. 5
//The Controller, filter the parameters and call the Model for do actions on data
$controller->delete(5);

$model->notify;
```
Now Model(Subject) notify to View(Observer) changes.
