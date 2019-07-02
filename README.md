# PHP Routing Framework (Atom)

**Atom** is very light weight PHP Framework which is specially design to build **RESTFul APIs** and it only support MySQL Database by default. 

**Note**: *You can easily integrate any other type of Database by simply creating custom helper inside the framework.*

## Installation Instructions
Atom is installed in four steps:
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

Let's take a deep dive and understand how to use this framework for building RESTFul APIs. 

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
$this->res->status(200).json(["response" => true, "msg" => "Your Message"]);    # Sending Data along with HTTP Status Code
```

### How to accept HTTP Request Body

To access the entity body of HTTP Request (of any HTTP Method) you have to use `$this->req->body` variable with in your Router Class. **Also you can parse JSON *(application/json)* and URL Encoded *(application/x-www-form-urlencoded)* data into PHP stdClass Object** by using following varibale given in example below: 
```php
$this->req->body        # Raw Data
$this->req->json        # Parse JSON (application/json) data into PHP stdClass Object
$this->req->urlencoded  # Parse URL Encoded (application/x-www-form-urlencoded) data into PHP stdClass Object
```
