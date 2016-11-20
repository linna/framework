---
layout: default
title: Model
current_menu: model
---

# Model

Model class is the parent class for every concrete Model in application. It works with View as Observer pattern where Model is the Subject and View is the Observer.

## How it works?
Extend Model: 
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

Methods
- __construct()
- attach()
- detach()
- notify()

## Properties

### getUpdate
type: *array*<br/>

Utilize it to store data that will pass to a View

#### Usage
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

## Methods

### __construct()
Call it from constructor when extend Model
```php
parent::__construct();
```

### attach()
Attach an Observer class to this Subject for updates when a subject state change occours
```php
$model->attach(\SplObjectStorage $observer);
```
#### Parameters
*\SplObjectStorage* **$observer**<br/>

#### Usage
```php
$model = new ProductModel;
        
$view = new ProductView($model, new ProductTemplate);
        
$controller = new ProductController($model);
        
$model->attach($view);
```
Now when a change occours, the Model may notify to the View.

### detach()
Like attach(), this method is used to exclude an object from notifies.
```php
$model->detach(\SplObjectStorage $observer);
```
#### Parameters
- *\SplObjectStorage* **$observer**<br/><br/>

#### Usage
```php
$model->attach($view);
$model->detach($view);
```
### notify()
After data manipulation call this method for notify changes to Observers

#### Usage
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
