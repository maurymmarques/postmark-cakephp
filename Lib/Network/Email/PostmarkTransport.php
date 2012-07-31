<?php
/**
 * Postmark Transport
 *
 * Copyright 2011, Maury M. Marques
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author	Maury M. Marques
 * @copyright	Copyright 2011, Maury M. Marques
 * @version	0.1
 * @license	http://www.opensource.org/licenses/mit-license.php The MIT License
 */

App::uses('AbstractTransport', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');

/**
 * PostmarkTransport
 *
 * This class is used for sending email messages
 * using the Postmark API http://postmarkapp.com/
 *
 */
class PostmarkTransport extends AbstractTransport {

/**
 * CakeEmail
 *
 * @var CakeEmail
 */
	protected $_cakeEmail;

/**
 * Variable that holds Postmark connection
 *
 * @var HttpSocket
 */
	private $__postmarkConnection;

/**
 * CakeEmail headers
 *
 * @var array
 */
	protected $_headers;

/**
 * Configuration to transport
 *
 * @var mixed
 */
	protected $_config = array();

/**
 * Sends out email via Postmark
 *
 * @return array Return the Postmark
 */
	public function send(CakeEmail $email) {
		// CakeEmail
		$this->_cakeEmail = $email;

		$this->_config = $this->_cakeEmail->config();
		$this->_headers = $this->_cakeEmail->getHeaders(array('from', 'to', 'cc', 'bcc', 'replyTo', 'subject'));

		// Setup connection
		$this->__postmarkConnection = & new HttpSocket();

		// Build message
		$message = $this->__buildMessage();

		// Build request
		$request = $this->__buildRequest();

		// Send message
		$returnPostmark = $this->__postmarkConnection->post($this->_config['uri'], json_encode($message), $request);

		// Return data
		$result = json_decode($returnPostmark, true);
		$headers = $this->_headersToString($this->_headers);
		if ($this->_cakeEmail->emailFormat() === 'html') {
			$message = $message['HtmlBody'];
		} else {
			$message = $message['TextBody'];
		}

		return array_merge(array('Postmark' => $result), array('headers' => $headers, 'message' => $message));
	}

/**
 * Build message
 *
 * @return array
 */
	private function __buildMessage() {
		// Message
		$message = array();

		// From
		$message['From'] = $this->_headers['From'];

		// To
		$message['To'] = $this->_headers['To'];

		// Cc
		$message['Cc'] = $this->_headers['Cc'];

		// Bcc
		$message['Bcc'] = $this->_headers['Bcc'];

		// ReplyTo
		$message['ReplyTo'] = $this->_headers['Reply-To'];

		// Subject
		$message['Subject'] = mb_decode_mimeheader($this->_headers['Subject']);

		// Tag
		if (isset($this->_headers['Tag'])) {
			$message['Tag'] = $this->_headers['Tag'];
		}

		// HtmlBody
		if ($this->_cakeEmail->emailFormat() === 'html' || $this->_cakeEmail->emailFormat() === 'both') {
			$message['HtmlBody'] = $this->_cakeEmail->message('html');
		}

		// TextBody
		if ($this->_cakeEmail->emailFormat() === 'text' || $this->_cakeEmail->emailFormat() === 'both') {
			$message['TextBody'] = $this->_cakeEmail->message('text');
		}

		// Attachments
		$message['Attachments'] = $this->__buildAttachments();

		return $message;
	}

/**
 * Build attachments
 *
 * @return array
 */
	private function __buildAttachments() {
		// Attachments
		$attachments = array();

		$i = 0;
		foreach ($this->_cakeEmail->attachments() as $fileName => $fileInfo) {

			$handle = fopen($fileInfo['file'], 'rb');
			$data = fread($handle, filesize($fileInfo['file']));
			$data = chunk_split(base64_encode($data)) ;
			fclose($handle);

			$attachments[$i]['Name'] = $fileName;
			$attachments[$i]['Content'] = $data;
			$attachments[$i]['ContentType'] = $fileInfo['mimetype'];

			$i++;
		}

		return $attachments;
	}

/**
 * Build request
 *
 * @return array
 */
	private function __buildRequest () {
		$request = array(
			'header' => array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'X-Postmark-Server-Token' => $this->_config['key']
			)
		);

		return $request;
	}

}
