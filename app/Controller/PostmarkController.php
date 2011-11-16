<?php
App::uses('AppController', 'Controller');
/**
 * Postmark Controller
 */
class PostmarkController extends AppController {

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		App::uses('CakeEmail', 'Network/Email');
		$email = new CakeEmail();

		$email->transport('Postmark');
		$email->config('postmark');
		$email->template('default', 'default');
		$email->emailFormat('html');
		$email->from(array('yourpostmark@mail.com' => 'Your Name'));
		$email->to(array('recipient@domain.com' => 'Recipient'));
		$email->subject('Test Postmark');
		$email->attachments(array(
		    'cake.icon.png' => array(
		        'file' => WWW_ROOT . 'img' . DS . 'cake.icon.png'
			)
		));

		$email->send();

		$this->autoRender = false;
	}

}