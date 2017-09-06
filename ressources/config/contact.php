<?php
require_once '/path/to/vendor/autoload.php';

// Create the Transport
// Sendmail
$transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$message = (new Swift_Message('HardWhere support'))
  ->setFrom(['Hardwhere@support.fr' => 'Support Hardwhere'])
  ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
  ->setBody('Here is the message itself')
  ;

// Send the message
$result = $mailer->send($message);
?>
