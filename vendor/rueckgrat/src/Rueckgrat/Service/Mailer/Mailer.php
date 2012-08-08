<?php

require_once __DIR__ . '/../vendor/swiftmailer/swift_required.php';

class Mailer {
    
    public function createEmail($subject, $message, $mailTo, $mailFrom = 'info@comesback.com') {

        $email = Swift_Message::newInstance();
        $email->setSubject($subject);
        $email->setFrom($mailFrom);
        if (is_array($mailTo) == true) {
            $email->setTo($mailTo);
        }
        else {
            $email->setTo(array($mailTo));
        }
        $email->setBody($message);

        return $email;

    }

    public function sendEmail($email) {

//        $transport = Swift_SmtpTransport::newInstance('smtp.ffuf.de', 25);
//        $transport->setUsername('m023cb7b');
//        $transport->setPassword('q8WFMYf8KpWWCYUb');
        $transport = Swift_SmtpTransport::newInstance('smtp.googlemail.com',465, 'ssl');
        $transport->setUsername('nils.abegg');
        $transport->setPassword('ficker23');
        $mailer = Swift_Mailer::newInstance($transport);

        return $mailer->send($email, $failure);

    }
    
}
