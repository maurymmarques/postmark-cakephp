# Postmark plugin for CakePHP

CakePHP plugin that makes email delivery using [Postmark](https://postmarkapp.com)

### Version

Written for CakePHP 2.x

### Copyright

Copyright (c) 2011 Maury M. Marques

## Installation

You can install this plugin using Composer, GIT Submodule, GIT Clone or Manually

_[Using [Composer](http://getcomposer.org/)]_

Add the plugin to your project's `composer.json` - something like this:

```javascript
{
  "require": {
    "maurymmarques/postmark-plugin": "dev-master"
  },
  "extra": {
    "installer-paths": {
      "app/Plugin/Postmark": ["maurymmarques/postmark-plugin"]
    }
  }
}
```
Then just run `composer install`

Because this plugin has the type `cakephp-plugin` set in it's own `composer.json`, composer knows to install it inside your `/Plugin` directory, rather than in the usual vendors file.

_[GIT Submodule]_

In your app directory (`app/Plugin`) type:

```bash
git submodule add git://github.com/maurymmarques/postmark-cakephp.git Plugin/Postmark
git submodule init
git submodule update
```

_[GIT Clone]_

In your plugin directory (`app/Plugin` or `plugins`) type:

```bash
git clone https://github.com/maurymmarques/postmark-cakephp.git Postmark
```

_[Manual]_

* Download the [Postmark archive](https://github.com/maurymmarques/postmark-cakephp/archive/master.zip).
* Unzip that download.
* Rename the resulting folder to `Postmark`
* Then copy this folder into `app/Plugin/` or `plugins`

## Configuration

Bootstrap the plugin in `app/Config/bootstrap.php`:

```php
CakePlugin::load('Postmark');
```

Create the file `app/Config/email.php` with the class EmailConfig.

```php
class EmailConfig {
	public $postmark = array(
		'transport' => 'Postmark.Postmark',
		'uri' => 'http://api.postmarkapp.com/email',
		'key' => 'your-postmark-key',
		'track_opens' => true
	);
}
```

If you want your connection to Postmark to be encrypted, simply change the uri to use `https`.

You can set [track_opens](http://developer.postmarkapp.com/developer-build.html#open-tracking) to `false` or remove it from the config array if you don't want Postmark to track emails.
Read more about [Open Tracking](http://blog.postmarkapp.com/post/87919491263/open-tracking-is-finally-here).

Note: Make sure to modified the API key to match the credentials for your Postmark server rack instance.

## Proxy

You can also configure a proxy in the file `app/Config/email.php`:

```php
class EmailConfig {
  public $postmark = array(
    'transport' => 'Postmark.Postmark',
    'uri' => 'http://api.postmarkapp.com/email',
    'key' => 'your-postmark-key',
    'track_opens' => true,
    'proxy' => array(
      'host' => 'your-proxy-host', # Can be an array with settings to authentication class
      'port' => 3128, # Default 3128
      'method' => null, # Proxy method (ie, Basic, Digest). If empty, disable proxy authentication
      'user' => null, # Username if your proxy need authentication
      'pass' => null # Password to proxy authentication
    ),
  );
}
```

Read more about [HttpSocket Parameters](https://api.cakephp.org/2.10/class-HttpSocket.html#_configProxy).

## Usage

This plugin uses [CakeEmail](http://book.cakephp.org/2.0/en/core-utility-libraries/email.html), and works virtually the same.

Then, simply send messages like this:

```php
App::uses('CakeEmail', 'Network/Email');
$email = new CakeEmail();

$email->config('postmark');
$email->from('yourpostmark@mail.com');
$email->to('recipient@domain.com');
$email->subject('Test Postmark');
$email->send('Message');
```

Or use more resources:

```php
App::uses('CakeEmail', 'Network/Email');
$email = new CakeEmail();

$email->config('postmark');
$email->template('default', 'default');
$email->emailFormat('html');
$email->viewVars(array('name' => 'Your Name'));
$email->from(array('yourpostmark@mail.com' => 'Your Name'));
$email->to(array('recipient1@domain.com' => 'Recipient1', 'recipient2@domain.com' => 'Recipient2'));
$email->cc(array('recipient3@domain.com' => 'Recipient3', 'recipient4@domain.com' => 'Recipient4'));
$email->bcc(array('recipient5@domain.com' => 'Recipient5', 'recipient6@domain.com' => 'Recipient6'));
$email->subject('Test Postmark');
$email->addHeaders(array('Tag' => 'my tag'));
$email->attachments(array(
    'cake.icon.png' => array(
        'file' => WWW_ROOT . 'img' . DS . 'cake.icon.png'
	)
));

$email->send();
```

If you need the instance of the class PostmarkTrasport:

```php
App::uses('CakeEmail', 'Network/Email');
$email = new CakeEmail();

$postmarkInstance = $email->transport('Postmark.Postmark')->transportClass();
```

The syntax of all parameters is the same as the default CakeEmail:

http://book.cakephp.org/2.0/en/core-utility-libraries/email.html

For more information, see the Postmark API documentation:

http://developer.postmarkapp.com/#message-format


## Debugging

You can see the response from Postmark in the return value when you send a message:

```php
$result = $email->send('Message');
$this->log($result, 'debug');
```

If there are any errors, they'll be included in the response. See the Postmark API documentation for error code detail:

http://developer.postmarkapp.com/#api-error-codes


## CakePHP 1.3+

This class does not work for CakePHP 1.3, for this see:

https://github.com/danielmcormond/postmark-cakephp
