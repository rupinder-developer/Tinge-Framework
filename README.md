# PHP Routing Framework (Atom)

**Atom** is very light weight PHP Framework which is specially design to build **RESTful APIs** and it only support MySQL Database by default. 

**Note**: *You can easily integrate any other type of Database by simply creating custom helpers inside the framework.*

## Installation Instructions
Atom is installed in three steps:
1. Unzip the package.
2. Upload the Atom folders and files to your server. Normally the index.php file will be at your root.
3. If you intend to use a database, open the app/core/config.php file with a text editor and set your database settings.

## Folder Structure
For building RESTFul APIs we have to only deal with helpers, models, routes. *Please to not mess up with **core directory** as it contains the core functionality of the framework.*

    .
    ├── app                     
    │   ├── core      
    │   ├── helper              
    │   └── models
    │   └── routes 
    


## Documentation

Let's take a deep dive and understand how to use this framework for building RESTful APIs. 

## Routes

### How to create Route?
If you want to make the route as like `http://www.example.com/Member/getMembers`, we have to create a file named as **Member.php** inside the **./app/routes** directory. 

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
    public function func_name($limit, $offset) {
       // Your Logic
    }
}
 ```

