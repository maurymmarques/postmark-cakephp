<?php
App::uses('CakeEmail', 'Network/Email');

/**
 * Test case
 *
 */
class PostmarkTransportTest extends CakeTestCase {

/**
 * CakeEmail
 *
 * @var CakeEmail
 */
	private $email;

/**
 * Setup
 *
 * @return void
 */
	public function setUp() {
		$this->email = new CakeEmail();
	}

/**
 * testPostmarkSend method
 *
 * @return void
 */
	public function testPostmarkSend() {
		$this->email->config('postmark');
		$this->email->template('default', 'default');
		$this->email->emailFormat('html');
		$this->email->from(array('yourpostmark@mail.com' => 'Your Name'));
		$this->email->to(array('recipient@domain.com' => 'Recipient'));
		$this->email->cc(array('recipient@domain.com' => 'Recipient'));
		$this->email->bcc(array('recipient@domain.com' => 'Recipient'));
		$this->email->subject('Test Postmark');
		$this->email->addHeaders(array('Tag' => 'my tag'));
		$this->email->attachments(array(
		    'cake.icon.png' => array(
		        'file' => WWW_ROOT . 'img' . DS . 'cake.icon.png'
			)
		));

		$sendReturn =  $this->email->send();

		$headers = $this->email->getHeaders(array('to'));
		$this->assertEqual($sendReturn['To'], $headers['To']);
		$this->assertEqual($sendReturn['ErrorCode'], 0);
		$this->assertEqual($sendReturn['Message'], 'OK');
	}

}
