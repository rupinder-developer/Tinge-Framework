# PHP Routing Framework (Atom)

**Atom** is very light weight PHP Framework which is specially design to build **RESTful APIs** and it only support MySQL Database by default. 

**Note**: *You can easily integrate any other type of Database by simply creating custom helpers inside the framework.*

## Installation Instructions
Atom is installed in three steps:
1. Unzip the package.
2. Upload the Atom folders and files to your server. Normally the index.php file will be at your root.
3. If you intend to use a database, open the app/core/config.php file with a text editor and set your database settings.

## Folder Structure
For building RESTful APIs we have to only deal with helpers, models, routes. *Please do not mess up with **core directory** as core functionality of the framework depends on it.*

    .
    ├── app                     
    │   ├── core      
    │   ├── helper              
    │   └── models
    │   └── routes 
    


## Documentation

Let's take a deep dive and understand how to use this framework for building RESTful APIs. 
1. [Routes](#routes)
    + [Create Route](#how-to-create-route)
    + [Send Response](#how-to-send-response)
    + [Accept HTTP Request Body](#how-to-accept-http-request-body)
    + [Accept Parameters from URL](#accept-parameters-from-url)
    + [Validate HTTP Request Method](##validate-http-request-method)
2. [Models](#models)
    + [Create Model](#how-to-create-model)
    + [Model usage](#how-to-access-models-inside-your-router-class)
    + [MySQL Database Connection](#how-to-interact-with-mysql-database-inside-your-model-class)
    + [Active Records](#active-records)
3. [Helpers](#helpers)
    + [Create Helpers](#how-to-create-helper)
    + [Helpers usage](#how-to-access-helper)

## Routes

### How to create Route?
If you want to make the route as like `http://www.example.com/Member/getMembers`, then create a file named as **Member.php** inside the **./app/routes** directory. 

**Member.php**
```php
<?php

class Member extends Router {
    public function getMembers() {
        $this->res->status(200)->json([
            'response' => true,
            'msg' => 'Route -> /Member/getMembers'
        ]);
    }
}
```

```
http://www.example.com/Member/getMembers
                         |        |
                         |        |---------> Function Name
   Class Name <----------|
                                            

```
> **Note**: File name and Class name should be same.

Here you can see that we used `$this->res` variable to the send response.

### How to send response?

We have basically three ways for sending response.
```php
$this->res->send('Raw Data');                                                   # Sending Raw Data
$this->res->json(["response" => true, "msg" => "Your Message"]);                # Sending JSON Data
$this->res->status(200)->json(["response" => true, "msg" => "Your Message"]);    # Sending Data along with HTTP Status Code
```

### How to accept HTTP Request Body?

To access the entity body of HTTP Request (of any HTTP Method) you have to use `$this->req->body` variable with in your Router Class. **Also you can parse JSON *(application/json)* and URL Encoded *(application/x-www-form-urlencoded)* data into PHP stdClass Object** by using following varibale given in example below: 
```php
$this->req->body        # Raw Data
$this->req->json        # Parse JSON (application/json) data into PHP stdClass Object
$this->req->urlencoded  # Parse URL Encoded (application/x-www-form-urlencoded) data into PHP stdClass Object
```
> **Note**: You can accept FormData by using built in GLOBAL Varibale $_POST.
### Accept Parameters from URL
Typically there is a one-to-one relationship between a URL string and its corresponding router class/method. The segments in a URI normally follow this pattern:
```
example.com/class/function/:id
```

In some instances, however, you may want to remap this relationship so that a different class/method can be called instead of the one corresponding to the URL.

For example, let’s say you want your URLs to have this prototype:
```
example.com/Product/getSingle/1/
example.com/Product/getSingle/2/
example.com/Product/getSingle/3/
example.com/Product/getSingle/4/
```
You can easily achieve above prototype by defining one single parameter to the method inside your router class as follows: 
```php
<?php

class Product extends Router {
    public function getSingle($productId) {
        // Your Logic
    }
}
```
You can easily accept more than one parameters from the URL by simply defining multiple parameters to the method inside the router class.

For example, let’s say you want your URLs to have this prototype:
```
example.com/Product/getAll/:limit/:offset
```
You can achieve above protype as follows: 
 ```php
 <?php
 
class Product extends Router {
    public function getAll($limit, $offset) {
       // Your Logic
    }
}
 ```
 
### Validate HTTP Request Method
You can easily set HTTP Request Method validation with help of `$this->req->method()` function inside your router class.

Now let's create the following routes to understand the usage of `$this->req->method()`:

| Request Method | Path                                          |
|----------------|-----------------------------------------------|
| `GET`          | http://example.com/Product/getAll             |
| `POST`         | http://example.com/Product/save               |
| `PUT`          | http://example.com/Product/putUpdate          |
| `PATCH`        | http://example.com/Product/patchUpdate        |
| `DELETE`       | http://example.com/Product/delete/:productId  |


```php
<?php

class Product extends Router {

    // GET Route
    public function getAll() {
        $this->req->method('get');
        // Your code...
    }
    
    // POST Route
    public function save() {
        $this->req->method('post'); 
        // Your code...
    }
    
    // PUT Route
    public function putUpdate() {
        $this->req->method('put');
        // Your code...
    }
    
    // PATCH Route
    public function patchUpdate() {
        $this->req->method('patch');
        // Your code...
    }
    
    // DELETE Route
    public function delete($productId) {
        $this->req->method('delete');
        // Your code...
    }
}
```

## Models

In this framework, **Model Classes** are used to interact with your **database**. All the main logic of your API goes here. Now let's see how to use models and see how model class can interact with router class.

### How to create Model?
You can simply create a Model by creating php file inside your **./app/models** directory with the name as same as your model name. 

For example, If you want to create a Model with the name **Member**, then you have to create a file named as Member.php inside your models directory.

**Member.php** *(./app/models)*
```php
<?php
class Member extends Model {
    public function testFunc() {
        // Your Logic
    }
}
```
> **Note:** File name and class name should be same.

### How to access Models inside your Router Class?

You can use `$this->model()` function inside your Router Class to access your model.

Now Let's take an example, how to access your **Model** inside the Router Class.

**Example.php** *(Router Class)* -> ./app/routes
```php
<?php
// Your Example Route
class Example extends Router {
    
    public function exampleFunc() {
        $model = $this->model('ModelName'); # $this->model() accept one paramter which is the name of your model
        $model->functionName();             # Accessing Model's Function
    }
}
```

### How to interact with MySQL Database inside your Model Class?

If you intend to use a database, open the app/core/config.php file with a text editor and set your database settings. You can also create global variables inside congfig.php file which you can access all over the framework. 

**./app/core/config.php**
```php
<?php
/**
 * Configuration File.
 * (You can also define your own custom GLOBAL VARIABLES in this file.)
 *
 * This file contains the following variables :
 * * HOSTNAME
 * * USERNAME
 * * PASSWORD
 * * DATABASE NAME
 *
 */
// Enter your Hostname.
define('HOSTNAME', 'localhost');
// Enter your Username.
define('USERNAME', 'root');
// Enter your Server Password.
define('PASSWORD', '');
// Enter your Database Name.
define('DB_NAME', 'dbname');


/**
 * CUSTOM GLOBAL VARIABLES
 *          |
 *          |
 *          V
 */
$EXAMPLE_VARIABLE = 'THIS IS EXAMPLE GLOBAL VARIABLE'; 
```

You can use `$this->db` variable inside your model class to interact with your MySQL Database. 

### Initialize Database Connection

**connect()** function/method is used to initialize your database connection.
```php
$this->db->connect(); # Initialize your database connection.
```

## Active Records 

This framework support Active Record which is a type of database pattern that pattern allows information to be retrieved, inserted, and updated in your database with minimal scripting. In some cases only one or two lines of code are necessary to perform a database action.

Beyond simplicity, It also allows for safer queries, since the values are escaped automatically by the system.

> **Note**: Active Records uses PDO (PHP Data Objects) to interact with MySQL Database.

If you want to use Active Records separately in your PHP Project please visit [https://github.com/rupinder-developer/ActiveRecords](https://github.com/rupinder-developer/ActiveRecords). 

Followings are the functions which are supported by our Active Records

| Function               | Description                                                                 |
|:-----------------------|:----------------------------------------------------------------------------|
| $this->db->connect()   | To initialize database connection.                                          |
| $this->db->select()    | Fetch Data from DB *(Returns Multidimensional Array)*.                      |
| $this->db->join()      | To generate Join Queries *(Returns Multidimensional Array)*.                |
| $this->db->insert()    | To insert data into your database.                                          |
| $this->db->update()    | To generate update query.                                                   |
| $this->db->delete()    | To delete data from your database.                                          |
| $this->db->query()     | To generate custom database queries.                                        |
| $this->db->installSQL()| To install SQL file to your connected database.                             |
| $this->db->dropTables()| To Drop all the tables inside your database.                                |
| $this->db->scanTables()| Returns the list of tables present in your database.                        |

**$this->db->select()**

```php
$this->db->select('table_name');
//Output: SELECT * FROM table_name
```

```php
$this->db->select([ 'col_name_1, col_name_2', 'table_name' ]);
//Output: SELECT col_name_1,col_name_2 FROM table_name
```

```php
$condition = [
    'col_1' => 'val_1',
    'col_2' => 'val_2'
];
$this->db->select('table_name', $condition);
//Output : SELECT * FROM table_name WHERE col_1=val_1 AND col_2=val2
```

```php
$condition = [
    'col_1' => 'val_1',
    'col_2' => 'val_2'
];
$this->db->select('table_name', $condition, 'OR');
//Output : SELECT * FROM table_name WHERE col_1=val_1 OR col_2=val2
```
**$this->db->join()**

```php
$this->db->join('table_1', 'table_2', 'table_1.col_name=table_2.col_name');
//Output : SELECT * FROM table_1 JOIN table_2 ON table_1.col_name=table_2.col_name;
```

```php
$this->db->join('table_1', ['table_2','INNER'], 'table_1.col_name=table_2.col_name');
//Output : SELECT * FROM table_1 INNER JOIN table_2 ON table_1.col_name=table_2.col_name;
```

```php
$this->db->join(['col_name_1,col_name_2','table_1'], 'table_2', 'table_1.col_name=table_2.col_name');
//Output : SELECT col_name_1,col_name_2 FROM table_1 JOIN table_2 ON table_1.col_name=table_2.col_name;
```

```php
$condition = [
    'col_1' => 'val_1',
    'col_2' => 'val_2'
];
$this->db->join('table_1', 'table_2', 'table_1.col_name=table_2.col_name', $condition);
//Output : SELECT * FROM table_1 JOIN table_2 ON table_1.col_name=table_2.col_name WHERE col_1=val_1 AND col_2=val2;
```
```php
$condition = [
    'col_1' => 'val_1',
    'col_2' => 'val_2'
];
$this->db->join('table_1', 'table_2', 'table_1.col_name=table_2.col_name', $condition, 'OR');
//Output : SELECT * FROM table_1 JOIN table_2 ON table_1.col_name=table_2.col_name WHERE col_1=val_1 OR col_2=val2;
```

**$this->db->insert()**
```php
$values = [
    'col_1' => 'val_1',
    'col_2' => 'val_2'
];
$this->db->insert('table_name', $values);
//Output : INSERT INTO table_name(col_1, val_1) VALUES('val_1', 'val_2')
```

**$this->db->update()**
```php
$query = [
    'col_1' => 'val_1',
    'col_2' => 'val_2'
];
$condition = [
    'col_name_1' => 'value_1',
    'col_name_2' => 'value_2',
];

$this->db->update('table_name', $condition, $condition);
//Output : UPDATE table_name SET col_1=val_1, col_2=val_2 WHERE col_name_1=value_1 AND col_name_2=value_2

$this->db->update('table_name', $condition, $condition, 'OR');
//Output : UPDATE table_name SET col_1=val_1, col_2=val_2 WHERE col_name_1=value_1 OR col_name_2=value_2
```

**$this->db->delete()**
```php
$condition = [
    'col_1' => 'val_1',
    'col_2' => 'val_2'
];
$this->db->delete('table_name', $condition);
//Output : DELETE FROM table_name WHERE col_1=val_1 AND col_2=val_2
```
```php
$condition = [
    'col_1' => 'val_1',
    'col_2' => 'val_2'
];
$this->db->delete('table_name', $condition, 'OR');
//Output : DELETE FROM table_name WHERE col_1=val_1 OR col_2=val_2
```

**$this->db->installSQL()**
```php
$this->db->installSQL('path/file_name.sql');
//Output: Install SQL file to your connected database
```

**$this->db->query()**

query() function is used to generate custom SQL queries and also provides the functionality to bind parameters with in your custom query.

```php
$query = $this->db->query('SELECT * FROM table_name WHERE col_1=:col_2 AND col_2=:col_2', [ ':col_1'=> 'val_1', ':col_2'=>'val_2' ] );
$query->execute();
$result = $query->fetchAll();

```

## Helpers

Helpers are the custom classes which you can access all over the framework.

### How to create Helper?
You can simply create a Helper by creating php file inside your **./app/helpers** directory with the name as same as your model name. 

For example, If you want to create a Model with the name **Authorization**, then you have to create a file named as Member.php inside your models directory.

**Authorization.php** *(./app/models)*
```php
<?php
class Authorization {
    public function testFunc() {
        // Your Logic
    }
}
```
> **Note:** File name and class name should be same.

### How to access Helper?

You can use `$this->helper()` function inside your Router/Model Class to access your helper.

```php
$helper = $this->helper('helper_name');
$helper->func_name();
```
