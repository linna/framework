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
*public, array*<br/>
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
*private, \SplObjectStorage*<br/>
This property contain the list of Observer objects that will receive notifications

### __construct()
*public*<br/>
Call it from constructor when extend Model
```php
parent::__construct();
```

### attach()

### detach()

### notify()
