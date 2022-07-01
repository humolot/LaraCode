# LaraCode

Codeigniter simplicity with Laravel performance - Support PHP 7.4, 8.0, 8.1
Tested in production despite some notifications everything works without problems.

## Installation

Download LaraCode
```bash
git clone https://github.com/humolot/LaraCode.git
```
```bash
cd LaraCode
```
```bash
composer install --ignore-platform-reqs
```
```bash
configure .env.development /.env.production
```
## Init LaraCode

JWT
- Six algorithms supported:
```
'HS256', 'HS384', 'HS512', 'RS256', 'RS384', 'RS512'
```
- `kid` support.
- Leeway support 0-120 seconds.
- Timestamp spoofing for tests.
- Passphrase support for `RS*` algos.

## Usage JWT

```php
use Ahc\Jwt\JWT;

// Instantiate with key, algo, maxAge and leeway.
$jwt = new JWT('secret', 'HS256', 3600, 10);
```

> Only the key is required. Defaults will be used for the rest:
```php
$jwt = new JWT('secret');
// algo = HS256, maxAge = 3600, leeway = 0
```

> For `RS*` algo, the key should be either a resource like below:
```php
$key = openssl_pkey_new([
    'digest_alg' => 'sha256',
    'private_key_bits' => 1024,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
]);
```

> OR, a string with full path to the RSA private key like below:
```php
$key = '/path/to/rsa.key';

// Then, instantiate JWT with this key and RS* as algo:
$jwt = new JWT($key, 'RS384');
```

***Pro***
You dont need to specify pub key path, that is deduced from priv key.

> Generate JWT token from payload array:
```php
$token = $jwt->encode([
    'uid'    => 1,
    'aud'    => 'http://site.com',
    'scopes' => ['user'],
    'iss'    => 'http://api.mysite.com',
]);
```

> Retrieve the payload array:
```php
$payload = $jwt->decode($token);
```

> Oneliner:
```php
$token   = (new JWT('topSecret', 'HS512', 1800))->encode(['uid' => 1, 'scopes' => ['user']]);
$payload = (new JWT('topSecret', 'HS512', 1800))->decode($token);
```

***Pro***

> Can pass extra headers into encode() with second parameter:
```php
$token = $jwt->encode($payload, ['hdr' => 'hdr_value']);
```

#### Test mocking

> Spoof time() for testing token expiry:
```php
$jwt->setTestTimestamp(time() + 10000);

// Throws Exception.
$jwt->parse($token);
```

> Call again without parameter to stop spoofing time():
```php
$jwt->setTestTimestamp();
```

#### Examples with `kid`

```php
$jwt = new JWT(['key1' => 'secret1', 'key2' => 'secret2']);

// Use key2
$token = $jwt->encode(['a' => 1, 'exp' => time() + 1000], ['kid' => 'key2']);

$payload = $jwt->decode($token);

$token = $jwt->encode(['a' => 1, 'exp' => time() + 1000], ['kid' => 'key3']);
// -> Exception with message Unknown key ID key3
```
## Usage  Digital Ocean Spaces

Obtain API keys from the [Digital Ocean Applications & API dashboard](https://cloud.digitalocean.com/account/api/tokens)

```php
use SpacesAPI\Spaces;

// Connect to a space
$spaces = new Spaces('api-key', 'api-secret');
$space = $spaces->space('space-name');

// Download a file
$file = $space->file('remote-file-1.txt');
$file->download('local/file/path/file.txt');

// Upload text to a file
$file2 = $space->uploadText("Lorem ipsum","remote-file-2.txt");

// Get a signed public link, valid for 2 hours
$file2url = $file2->getSignedURL("2 hours");

// Make a copy
$file3 = $file2->copy('remote-file-3.txt');

// Make a file public and get the URL
$file3->makePublic();
$file3url = $file3->getURL();
```
## Usage Pusher Channels HTTP PHP Library
Use the credentials from your Pusher Channels application to create a new `Pusher\Pusher` instance.

```php
$app_id = 'YOUR_APP_ID';
$app_key = 'YOUR_APP_KEY';
$app_secret = 'YOUR_APP_SECRET';
$app_cluster = 'YOUR_APP_CLUSTER';

$pusher = new Pusher\Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);
```
The fourth parameter is an `$options` array. The additional options are:

* `scheme` - e.g. http or https
* `host` - the host e.g. api.pusherapp.com. No trailing forward slash
* `port` - the http port
* `path` - a prefix to append to all request paths. This is only useful if you
  are running the library against an endpoint you control yourself (e.g. a
  proxy that routes based on the path prefix).
* `timeout` - the HTTP timeout
* `useTLS` - quick option to use scheme of https and port 443.
* `cluster` - specify the cluster where the application is running from.
* `encryption_master_key` - a 32 char long key. This key, along with the
  channel name, are used to derive per-channel encryption keys. Per-channel
  keys are used encrypt event data on encrypted channels.

For example, by default calls will be made over HTTPS. To use plain
HTTP you can set useTLS to false:

```php
$options = [
  'cluster' => $app_cluster,
  'useTLS' => false
];
$pusher = new Pusher\Pusher($app_key, $app_secret, $app_id, $options);
```
## Usage Manipulate Images API
Image manipulation doesn't have to be hard. Here are a few examples on how this package makes it very easy to manipulate images.

```php
use Spatie\Image\Image;

// modifying the image so it fits in a 100x100 rectangle without altering aspect ratio
Image::load($pathToImage)
   ->width(100)
   ->height(100)
   ->save($pathToNewImage);
   
// overwriting the original image with a greyscale version   
Image::load($pathToImage)
   ->greyscale()
   ->save();
   
// make image darker and save it in low quality
Image::load($pathToImage)
   ->brightness(-30)
   ->quality(25)
   ->save();
   
// rotate the image and sharpen it
Image::load($pathToImage)
   ->orientation(90)
   ->sharpen(15)
   ->save();
```
## MongoDB
# Methods

## Insert Method
* `insert` Insert a new document into a collection
* `batch_insert` Insert multiple new documents into a collection

## Select Method
* `select` Get select fields from returned documents
* `where` OR `get_where` Where section of the query
* `where_in` Where something is in an array of something
* `where_in_all` Where something is in all of an array of * something
* `where_not_in` Where something is not in array of something
* `where_or` Where something is based on or
* `where_gt` Where something is greater than something
* `where_gte` Where something is greater than or equal to something
* `where_lt` Where something is less than something
* `where_lte` Where something is less than or equal to something
* `where_between` Where something is in between to something
* `where_between_ne` Where something is in between and but not equal to something
* `where_ne` Where something is not equal to something
* `like` Where something is search by like query
* `order_by` Order the results
* `limit` OR `offset` Limit the number of returned results
* `count` Document Count based on where query
* `distinct` Retrieve a list of distinct values for the given key across a single collection
* `find_one` Retrieve single document from collection

## Update Method
* `set` Sets a field to a value
* `unset_field` Unsets a field
* `addtoset` Adds a value to an array if doesn't exist
* `push` Pushes a value into an array field
* `pop` Pops a value from an array field
* `pull` Removes an array by the value of a field
* `rename_field` Rename a field
* `inc` Increments the value of a field
* `mul` Multiple the value of a field
* `max` Updates the value of the field to a specified value if the specified value is greater than the current value of the field
* `min` Updates the value of the field to a specified value if the specified value is less than the current value of the field.
* `update` Update a single document in a collection
* `update_all` Update all documents in a collection

## Delete Method
* `delete` Delete a single document in a collection
* `delete_all` Delete all documents in a collection

## Aggregation Method
* `aggregate` Perform aggregation query on document

## Profiling Methods
* `output_benchmark` return complete explain data for all the find based query performed

## Index Method
* `add_index` Create a new index on collection
* `remove_index` Remove index from collection
* `list_indexes` Show all index created on collections

## DB Method
* `switch_db` Switch to a different database
* `drop_db` Drops a database
* `drop_collection` Drops a collection
* `command` Perform MongoDB command

## Extra Helper
* `date` Create or convert date to MongoDB based Date
## Usage PHP Simple Encryption
```php
use Encryption\Encryption;
use Encryption\Exception\EncryptionException;

$text = 'The quick brown fox jumps over the lazy dog';
$key  = 'secretkey';
try {
    $encryption = Encryption::getEncryptionObject();
    $iv = $encryption->generateIv();
    $encryptedText = $encryption->encrypt($text, $key, $iv);
    $decryptedText = $encryption->decrypt($encryptedText, $key, $iv);

    printf('Cipher   : %s%s', $encryption->getName(), PHP_EOL);
    printf('Encrypted: %s%s', $encryptedText, PHP_EOL);
    printf('Decrypted: %s%s', $decryptedText, PHP_EOL);
    printf('Version  : %s%s', Encryption::VERSION, PHP_EOL);
}
catch (EncryptionException $e) {
    echo $e;
}
```

To get a list of ciphers supported by your system *and* this library you can call `Encryption::listAvailableCiphers()`
to receive an array of available ciphers. This list is an intersection of available ciphers from your system's
installed version of Openssl and ciphers supported by this library.    

**Total ciphers supported:** 139    
**Default cipher:** AES-256-CBC (version 1)

## Usage Datatables Php Library
```php
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;
class Welcome extends CI_Controller {
    public function index()
    {
        $this->load->view('welcome_message');
    }
    public function ajax()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query('Select film_id, title, description from film');
        echo $datatables->generate();
    }
}
```
This is the list of available public methods.

__query($query)__ *required*

* sets the sql query

__generate()__  *required*

* runs the queries and build outputs
* returns the output as json
* same as generate()->toJson()

__toJson()__

* returns the output as json
* should be called after generate()

__toArray()__

* returns the output as array
* should be called after generate()

__add($column, function( $row ){})__

* adds extra columns for custom usage

__edit($column, function($row){})__

* allows column editing

__filter($column, function(){})__

* allows custom filtering
* it has the methods below
    - escape($value)
    - searchValue()
    - defaultFilter()
    - between($low, $high)
    - whereIn($array)
    - greaterThan($value)
    - lessThan($value)

__hide($columns)__

* removes the column from output
* It is useful when you only need to use the data in add() or edit() methods.

__setDistinctResponseFrom($column)__

* executes the query with the given column name and adds the returned data to the output with the distinctData key.

__setDistinctResponse($output)__

* adds the given data to the output with the distinctData key.

__getColumns()__

* returns column names (for dev purpose)

__getQuery()__

## Eloquent: Getting Started
Eloquent: Getting Started [Eloquent: Getting Started](https://laravel.com/docs/8.x/eloquent)

Database: Getting Started [Database: Getting Started](https://laravel.com/docs/8.x/database)

## RestServer
Step 1: Add this to your controller (should be before any of your code)

```php
use chriskacerguis\RestServer\RestController;
```
Step 2: Extend your controller

```php
class Example extends RestController
```

## Basic GET example

Here is a basic example. This controller, which should be saved as `Api.php`, can be called in two ways:

* `http://domain/api/users/` will return the list of all users
* `http://domain/api/users/id/1` will only return information about the user with id = 1

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function users_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
            ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        ];

        $id = $this->get( 'id' );

        if ( $id === null )
        {
            // Check if the users data store contains users
            if ( $users )
            {
                // Set the response and exit
                $this->response( $users, 200 );
            }
            else
            {
                // Set the response and exit
                $this->response( [
                    'status' => false,
                    'message' => 'No users were found'
                ], 404 );
            }
        }
        else
        {
            if ( array_key_exists( $id, $users ) )
            {
                $this->response( $users[$id], 200 );
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'No such user found'
                ], 404 );
            }
        }
    }
}
```

## Usage Alternative Template
```php
use raelgc\view\Template;
```
The basic working of the template engine is based on the following idea: you have a PHP file (which will use the library), and this file will not contain any HTML code. The HTML code will be separated, in a file that will contain only HTML codes. This HTML file will be read by the PHP file. Incidentally, everything will be processed in the PHP file.

That said, let's go to the first example, the already familiar Hello world. Let's create 2 files: the PHP responsible for all the logic, and the HTML file with our layout.

Then, create an HTML file, called hello.html with the content below:

```html
<html>
  <body>
    Hello PHP!
  </body>
</html>
```
*Usage*
```php
  use raelgc\view\Template;
  $tpl = new Template("hello.html");
  $tpl->show();
```
## Variables

Now for an important concept: template variables. As you can imagine, we're going to want to change various parts of the HTML file. How to do this? Simple: on the HTML side, you create so-called template variables. See the example below:

```html
<html>
  <body>
    Hello {EXAMPLE}, PHP!
  </body>
</html>
```
```php
  use raelgc\view\Template;
  $tpl = new Template("hello.html");
  $tpl->EXAMPLE = "LaraCode";
  $tpl->show();
```
Template variables that do not have a value assigned will be cleared from the final generated code.
```php
  use raelgc\view\Template;
  $tpl = new Template("hello.html");
  $tpl->EXAMPLE = "Rael";
  die("Value of Example: ".$tpl->EXAMPLE);
  $tpl->show();
```
## Checking if Variables Exist

If you want to assign a value to a template variable, but you are not sure if the variable exists, you can use the exists() method to check.
```php
  use raelgc\view\Template;
  $tpl = new Template("layout.html");
  if($tpl->exists("EXAMPLE")) $tpl->EXAMPLE = "LaraCode";
  $tpl->show();
```
## Blocks

This is the second and final concept you need to know about this template library: blocks.

Imagine that you would like to list the total number of products registered in a database. If there are no products, you will display a warning that no products were found.

We will then use two blocks: one that shows the total amount; and another that warns that there are no registered products, if the bank is really empty. The HTML code for this is:
```html
<html>
  <body>

    <p>Number of products registered in the system:</p>

    <!-- BEGIN BLOCK_NUMBERS -->
    <div class="destaque">There are {QUANTITY} products registered.</div>
    <!-- END BLOCK_NUMBERS -->

    <!-- BEGIN BLOCK_EMPTY -->
    <div class="vazio">There is no registered product.</div>
    <!-- END BLOCK_EMPTY -->

  </body>
</html>
```
The words BEGIN and END must always be capitalized. The block name must contain only letters, numbers or underscores.

And then, on the PHP side, let's check if the products exist. If so, we will show the `BLOCK_NUMBERS` block. If not, we will display the `BLOCK_EMPTY` block.

```php
  use raelgc\view\Template;
  $tpl = new Template("hello.html");
  $numbers = 5;
  if($quantidade > 0){
      $tpl->QUANTITY = $numbers;
      $tpl->block("BLOCK_NUMBERS");
  }
  else {
      $tpl->block("BLOCK_EMPTY");
  }
  $tpl->show();
```
Notice that we only have one HTML table row for the product data, inside a block. Let's then assign value to these variables, and duplicate the content of the block as we list the products:

```php
  use raelgc\view\Template;
  $tpl = new Template("hello.html");
  $products = array(
    array("name" => "Washing powder", "amount" => 15),
    array("name" => "Toothbrush", "amount" => 53),
    array("name" => "Toothpaste", "amount" => 37)
  );
  foreach($products as $p){
    $tpl->NAME = $p["name"];
    $tpl->AMOUNT = $p["amount"];
    $tpl->block("BLOCK_PRODUCTS");
  }
  $tpl->show();
```
## Nested blocks

Let's now put together the 2 examples of using blocks that we saw: we want to show product data in a block, but if there are no products registered, we will display a message with a warning. Let's do this now using nested blocks, i.e. blocks inside other blocks:

```html
<html>
  <body>

    <p>Products registered in the system:</p>

    <!-- BEGIN BLOCK_PRODUCTS -->
    <table border="1">

      <tr><td>Name</td><td>Amount</td></tr>

      <!-- BEGIN BLOCK_DATA -->
      <tr>
        <td> {NAME} </td>
        <td> {AMOUNT} </td>
      </tr>
      <!-- END BLOCK_DATA -->

    </table>
    <!-- END BLOCK_PRODUCTS -->

    <!-- BEGIN BLOCK_EMPTY -->
    <div class="none">No records found.</div>
    <!-- END BLOCK_EMPTY -->

  </body>
</html>
```
And then, if there are products, we display the `PRODUCTS` block. Otherwise, we display the `EMPTY` block:
```php
  use raelgc\view\Template;
  $tpl = new Template("hello.html");
  $tpl->addFile("CONTENT", "content.html"); // header.html, footer.html etc.

  $products = array(
    array("name" => "Washing powder", "amount" => 15),
    array("name" => "Toothbrush", "amount" => 53),
    array("name" => "Toothpaste", "amount" => 37)
  );

  foreach($products as $p){
    $tpl->NAME = $p["name"];
    $tpl->AMOUNT = $p["amount"];
    $tpl->block("BLOCK_DATA");
  }

  // If there are products, then we show the block with the data of all
  if(isset($products) && is_array($products) && sizeof($products) > 0){
    $tpl->block("BLOCK_PRODUCTS");
  }
  // Otherwise, we show the block with the warning of no registered
  else {
    $tpl->block("BLOCK_EMPTY");
  }

  $tpl->show();

?>
```
## Escaping Variables

Let's assume that for some reason you need to keep a template variable in the final output of your HTML. For example: you are writing a system that automatically generates the templates for you.

For that, let's assume you have the HTML below:
```html
<html>
  <body>

    {CONTENT}

  </body>
</html>
```
And you need `{CONTENT}` not to be replaced (or removed), but to remain in the final HTML.

To do this, *escape* by including `{_}` inside the variable:
```html
<html>
  <body>

    {{_}CONTENT}

  </body>
</html>
```
## Error Messages

Below are the meanings for the error messages displayed by the Template class:

* `Parse error: syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}'`: you are probably using PHP < 7 (see the requirements needed to use this library).

* `addFile: var <varname> doesn't exist`: you are using the addFile() method to add an HTML file (or equivalent), but the template variable you want to put the content in does not exist.

* `var <varname> does not exist`: you are trying to assign value to a variable that does not exist. Make sure that the name of the template variable is correct, and that you are using as the name of this variable only letters, numbers and underscore, between braces.

* `file <filename> does not exist`: you are providing the path to an HTML file (or equivalent) that does not exist, or whose read permission is denied.

* `file <filename> is empty`: the HTML file (or equivalent) you are passing as a parameter is empty. If it's empty, either you're entering a wrong file, or you forgot to put content in it.

* `duplicate block: <blockname>`: the name you are trying to assign to the block has already been given to another block. Remember that the block name must be unique. If you are using more than one HTML file (or equivalent), the block with the same name as yours may be in one of the other files.

* `block <blockname> is malformed`: the block you declared is defective. Maybe you used the `BEGIN BLOCK` tag with one name, and ended up (the `END BLOCK` tag) with another. Or, you forgot the `END BLOCK` tag. Or you put the `FINALLY BLOCK` tag in the wrong place.

* `block <blockname> does not exist`: you are telling the `block()` method the name of a block that does not exist. Make sure that the block name is correct, and that you are using only letters, numbers and underscores as the name of this block.

* `there is no method in class <classname> to access <object>-><property>`: there is no method to access the property you are calling. If you call in HTML `OBJECT->NAME`, this object's class must have a method called getName() or isName(). See more details in the "Using Objects" section.

## Usage UUID 

```php
    use Ramsey\Uuid\Uuid;
    $uuid = Uuid::uuid4();
    printf(
        "UUID: %s\nVersion: %d\n",
        $uuid->toString(),
        $uuid->getFields()->getVersion()
    );
```
UUID: Getting Started [UUID: Getting Started](https://uuid.ramsey.dev/en/stable)

## LaraCode works with Codeigniter v3.1.13 full documentation + Eloquent + Laravel Database + Whoops + JWT + ResetApi Server + Spaces-API + Pusher Server + SpatieImage + MongoDb + PHP Encryption + Template Engine + phpspreadsheet + phpmailer + yidas.

You can start your multi tenant using helper domain.

Use codetisan to create your controllers, models and views

```bash
php index.php codetisan 
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
