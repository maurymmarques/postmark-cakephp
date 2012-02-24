# Postmark plugin for CakePHP

Copyright 2011, Maury M. Marques
Licensed under The MIT License
Redistributions of files must retain the above copyright notice.


### Version

Written for CakePHP 2.0+


### Installation

You can clone the plugin into your project (or if you want you can use as a [submodule](http://help.github.com/submodules)):

```
cd path/to/app/Plugin or /plugins
git clone https://github.com/maurymmarques/postmark-cakephp.git Postmark
```

Bootstrap the plugin in app/Config/bootstrap.php:

```php
<?php
CakePlugin::load('Postmark');
```


### Configuration

Create the file app/Config/email.php with the class EmailConfig.

```php
<?php
class EmailConfig {
	public $postmark = array(
		'uri' => 'http://api.postmarkapp.com/email',
		'key' => 'your-key-postmark'
	);
}
```

If you want your connection to Postmark to be encrypted, simply change the uri to use https.

Make sure to modified the API key to match the credentials for your Postmark server rack instance.


### Usage

This class uses [CakeEmail](http://book.cakephp.org/2.0/en/core-utility-libraries/email.html), and works virtually the same.

Then, simply send messages like this:

```php
<?php
App::uses('CakeEmail', 'Network/Email');
$email = new CakeEmail();

$email->transport('Postmark.Postmark');
$email->config('postmark');
$email->from('yourpostmark@mail.com');
$email->to('recipient@domain.com');
$email->subject('Test Postmark');
$email->send('Message');
```

Or use more resources:

```php
<?php
App::uses('CakeEmail', 'Network/Email');
$email = new CakeEmail();

$email->transport('Postmark.Postmark');
$email->config('postmark');
$email->template('default', 'default');
$email->emailFormat('html');
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
<?php
App::uses('CakeEmail', 'Network/Email');
$email = new CakeEmail();

$postmarkInstance = $email->transport('Postmark.Postmark')->transportClass();
```

The syntax of all parameters is the same as the default CakeEmail:

http://book.cakephp.org/2.0/en/core-utility-libraries/email.html

For more information, see the Postmark API documentation:

http://developer.postmarkapp.com/#message-format


### Debugging

You can see the response from Postmark in the return value when you send a message:

```php
<?php
$result = $email->send('Message');
$this->log($result, 'debug');
```

If there are any errors, they'll be included in the response. See the Postmark API documentation for error code detail:

http://developer.postmarkapp.com/#api-error-codes


### CakePHP 1.3+

This class does not work for CakePHP 1.3, for this see:

https://github.com/danielmcormond/postmark-cakephp
