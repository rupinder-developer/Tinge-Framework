# Tinge Framework - v2

**What's New?**
- Improve Active Records
    - Implemented Method Chaining
    - New Active Record's methods
        - *where(), nin(), in(), limit(), orderBy(), like(), notLike()*
- Remove *join()* method from Active Records
     

**Tinge Framework** is very light weight PHP Framework which is specially design to build **RESTful APIs** and it only support MySQL Database by default. 

**Note**: *You can easily integrate any other type of Database by simply creating custom helpers inside the framework.*

## Installation Instructions
Tinge is installed in three steps:
1. Unzip the package.
2. Upload the Tinge folders and files to your server. Normally the index.php file will be at your root.
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
    + [Validate HTTP Request Method](#validate-http-request-method)
2. [Models](#models)
    + [Create Model](#how-to-create-model)
    + [Model usage](#how-to-access-models-inside-your-router-class)
    + [MySQL Database Connection](#how-to-interact-with-mysql-database-inside-your-model-class)
    + [Active Records](#active-records)
3. [Helpers](#helpers)
    + [Create Helpers](#how-to-create-helper)
    + [Helpers usage](#how-to-access-helper)
4. [PHP-JWT (JSON Web Tokens)](#jwtjson-web-tokens)

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

To access the entity body of HTTP Request (of any HTTP Method) you have to use `$this->req->body` variable with in your Router Class. **Also you can parse JSON *(application/json)* and URL Encoded *(application/x-www-form-urlencoded)* data into PHP stdClass Object** by using following methods given in example below: 

#### Accept Raw Data
```php
<?php

class Test extends Router {
    public function testRoute() {
        $rawData = $this->req->body();
    }
}
```
#### Accept JSON Data
**parseJSON()** method parses JSON (application/json) data into PHP stdClass Object and stores the result in **$this->req->json** variable.
```php
<?php

class Test extends Router {
    public function testRoute() {
        $this->req->parseJSON();
        $firstName = $this->req->json->firstName;
        $lastName = $this->req->json->lastName;
    }
}
```

#### Accept URL Encoded Data
**parseUrlencoded()** method parses URL Encoded (application/x-www-form-urlencoded) data into PHP stdClass Object and stores the result in **$this->req->urlencoded** variable.
```php
<?php

class Test extends Router {
    public function testRoute() {
        $this->req->parseUrlencoded();
        $firstName = $this->req->urlencoded->firstName;
        $lastName = $this->req->urlencoded->lastName;
    }
}
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

If you intend to use a database, open the app/core/config.php file with a text editor and set your database settings. You can also create global variables inside config.php file which you can access all over the framework. 

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
$this->db->connect(); # This function will return new PDO Connection
```

## Active Records 

This framework support Active Record which is a type of database pattern that pattern allows information to be retrieved, inserted, and updated in your database with minimal scripting. In some cases only one or two lines of code are necessary to perform a database action.

Beyond simplicity, It also allows for safer queries, since the values are escaped automatically by the system.

> **Note**: Active Records uses PDO (PHP Data Objects) to interact with MySQL Database.

**Tip**: A great benefit of PDO is that it has an exception class to handle any problems that may occur in our database queries. If an exception is thrown within the try{ } block, the script stops executing and flows directly to the first catch(){ } block.

### Selecting Data
The following functions allow you to build SQL SELECT statements.

### $this->db->select();
```php
$query  = $this->db->select('table_name')->execute();
$result = $query->fetchAll();

// Produces: SELECT * FROM table_name
```

### $this->db->project();
```php
$query  = $this->db->select('table_name')->project('col1, col2')->execute();
$result = $query->fetchAll();

// Produces: SELECT col1, col2 FROM table_name
```

### $this->db->where();
This function enables you to set WHERE clause.
> **Note**: All values passed to this function are escaped automatically, producing safer queries.

**WHERE clause with *AND***
```php
$query  = $this->db->select('table_name')
                    ->where(array(
                        'col_1' => 'val_1',
                        'col_2' => 'val_2',
                    ))
                    ->execute();
$result = $query->fetchAll();

// Produces: SELECT * FROM table_name WHERE col_1='val_1' AND col_2='val_2'
```

**WHERE clause with *OR***
```php
$query  = $this->db->select('table_name')
                    ->where(array(
                        'col_1' => 'val_1',
                        'col_2' => 'val_2',
                    ), 'OR')
                    ->execute();
$result = $query->fetchAll();

// Produces: SELECT * FROM table_name WHERE col_1='val_1' OR col_2='val_2'
```

If you use multiple function calls they will be chained together with AND between them:
```php
$query  = $this->db->select('table_name')
                    ->where(array(
                        'col_1' => 'val_1',
                        'col_2' => 'val_2',
                    ), 'OR')
                    ->where(array(
                        'col_3' => 'val_3',
                        'col_4' => 'val_4',
                    ), 'OR')
                    ->execute();
$result = $query->fetchAll();

// Produces: SELECT * FROM table_name WHERE (col_1='val_1' OR col_2='val_2') AND (col_3='val_3' OR col_4='val_4')
```

###  $this->db->in()
This function enables you to IN operator in a WHERE clause.
```php
$query  = $this->db->select('table_name')
                    ->in('col_name', ['value1', 'value2'])
                    ->execute();
$result = $query->fetchAll();
// Produces: SELECT * FROM table_name WHERE col_name IN ('value1', 'value2')
```

### $this->db->nin()

This function is same as $this->db->in(), but only the difference is that it will produce NOT IN query as given below:
```php
$query  = $this->db->select('table_name')
                    ->nin('col_name', ['value1', 'value2'])
                    ->execute();
$result = $query->fetchAll();
// Produces: SELECT * FROM table_name WHERE col_name NOT IN ('value1', 'value2')
```

### $this->db->like()
This function enables you to LIKE operator in a WHERE clause.
```php
$query  = $this->db->select('table_name')
                    ->like(array(
                        'col_1' => 'value1',
                        'col_2' => 'value2'
                    ))
                    ->like(array(
                        'col_3' => 'value3',
                        'col_4' => 'value4',
                    ), 'OR')
                    ->execute();
$result = $query->fetchAll();
// Produces: SELECT * FROM table_name WHERE (col_1 LIKE '%value1%' AND col_2 LIKE '%value2%') AND (col_3 LIKE '%value3%' OR col_4 LIKE '%value4%') 
```

### $this->db->notLike()
This is funcation is same as $this->db->like(), but it will product NOT LIKE queries instead of LIKE.
```php
$query  = $this->db->select('table_name')
                    ->notLike(array(
                        'col_1' => 'value1',
                        'col_2' => 'value2'
                    ))
                    ->notLike(array(
                        'col_3' => 'value3',
                        'col_4' => 'value4',
                    ), 'OR')
                    ->execute();
$result = $query->fetchAll();

// Produces: SELECT * FROM table_name WHERE (col_1 NOT LIKE '%value1%' AND col_2 NOT LIKE '%value2%') AND (col_3 NOT LIKE '%value3%' OR col_4 NOT LIKE '%value4%') 
```

### $this->db->orderBy()

```php
$query  = $this->db->select('table_name')
                    ->orderBy('id', 'DESC')
                    ->execute();
$result = $query->fetchAll();
                        
// Produces: SELECT * FROM table_name ORDER BY id DESC

$query  = $this->db->select('table_name')
                    ->orderBy('title DESC, name ASC')
                    ->execute();
$result = $query->fetchAll();
                        
// Produces: SELECT * FROM table_name ORDER BY title DESC, name ASC
```

### $this->db->limit()
Lets you limit the number of rows you would like returned by the query:
```php
$query  = $this->db->select('table_name')
                    ->limit(10)
                    ->execute();
$result = $query->fetchAll();
                        
// Produces: SELECT * FROM table_name LIMIT 10
```

The second parameter lets you set a result offset.

```php
$query  = $this->db->select('table_name')
                    ->limit(10, 20)
                    ->execute();
$result = $query->fetchAll();
                        
// Produces: SELECT * FROM table_name LIMIT 20, 10
```

### Inserting Data

**$this->db->insert()**
```php
$this->db->insert('table_name', array(
    'col_1' => 'val_1',
    'col_2' => 'val_2'
)); 

//Produces : INSERT INTO table_name(col_1, val_1) VALUES('val_1', 'val_2')
```

### Updating Data

**$this->db->update()**
```php
$this->db->update('table_name', array(
                'col1' => 'value1',
                'col2' => 'value2',
            ))
            ->where(array(
                'id' => 1,
                'col' => 'val'
            ))
            ->execute();

//Produces : UPDATE table_name SET col1='value1', col2='value2' WHERE id=1 AND col='val'

$this->db->update('table_name', array(
                'col1' => 'value1',
                'col2' => 'value2',
            ))->execute();

//Produces : UPDATE table_name SET col1='value1', col2='value2' 
```

### Deleting Data

**$this->db->delete()**
```php
$this->db->delete('table_name')
            ->where(array(
                'id' => 1,
                'col' => 'val'
            ))
            ->execute();

//Produces : DELETE FROM table_name WHERE id=1 AND col='val'

$this->db->delete('table_name')->execute();

//Produces : DELETE FROM table_name
```

### Other Active Record's Methods

**$this->db->installSQL()**
```php
$this->db->installSQL('path/file_name.sql');
//Output: Install SQL file to your connected database
```

**$this->db->scanTables()**
```php
$result = $this->db->scanTables();
//Output: Return the list of all tables present in database
```

**$this->db->query()**
$this->db->query() method is used to generate custom SQL queries.
```php

// It will return Prepared Statement 
$preparedStmt = $this->db->query('SELECT * FROM table_name WHERE col_1=:col_1 AND col_2=:col_2'); 

// Bind Parameters
$value1 = 'Value1';
$value2 = 'Value2';
$preparedStmt->bindParam(':col_1', $value1);
$preparedStmt->bindParam(':col_2', $value2);

// Execute Statement
$preparedStmt->execute();

// Fetching Result 
$result = $preparedStmt->fetchAll();
```

## Helpers

Helpers are the custom classes which you can access all over the framework.

### How to create Helper?
You can simply create a Helper by creating php file inside your **./app/helpers** directory with the name as same as your model name. 

For example, If you want to create a Helper with the name **Authorization**, then you have to create a file named as Authorization.php inside your helpers directory.

**Authorization.php** *(./app/helpers)*
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


## JWT(JSON Web Tokens)

We added [PHP-JWT](https://github.com/firebase/php-jwt) Library inside this framework. You can took referance from [https://github.com/firebase/php-jwt](https://github.com/firebase/php-jwt)

Let's take a deep dive to understand the working of PHP-JWT Library. 
**./app/routes/Example.php**
```php
class Example extends Router {
    public function encode() {
        // JWT Encodeing Example
        $jwt = JWT::encode([
            'memberId' => 1134,
            'iat' => time(), //Issued at
            'nbf' => time() + 60, //Not Before 
            'exp' => time() + (2 * 60) //Expiration Time
        ], 'secret_key');
        $this->res->status(200)->json([
            'response' => true,
            'jwtEncoded' => $jwt
        ]);  
    }
    
    public function decode() {
        try {
            $decode = JWT::decode($this->req->json->token, 'secret_key');
        } catch (Exception $e) {
            $this->res->status(401)->json([
                'response' => false,
                'msg' => 'Invalid Token'
            ]); 
        }
        $this->res->status(200)->json([
            'response' => true,
            'jwtDecoded' => $decode
        ]); 
    }
}
```
