<?php

namespace AppBundle\Utils;

use Symfony\Component\Translation\TranslatorInterface;

class Mailer {
    /** @var \Swift_Mailer **/
    protected $mailer;
    /** @var \Symfony\Component\Translation\TranslatorInterface **/
    protected $translator;
    /** @var string **/
    protected $websiteUrl;
    /** @var string **/
    protected $websiteMail;
    /** @var string **/
    protected $mailName;
    
    /**
     * @param \Swift_Mailer $mailer
     * @param \AppBundle\Utils\TranslatorInterface $translator
     * @param string $websiteUrl
     * @param string $websiteMail
     * @param string $mailName
     */
    public function __construct(\Swift_Mailer $mailer, TranslatorInterface $translator, $websiteUrl, $websiteMail, $mailName) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->websiteUrl = $websiteUrl;
        $this->websiteMail = $websiteMail;
        $this->mailName = $mailName;
    }
    
    /**
     * @param string $email
     * @param string $mailKey
     * @param array $parameters
     * @param string $sensitivity
     * @return int
     */
    public function sendTo($email, $mailKey, $parameters = [], $sensitivity = 'Normal') {
        $parameters = array_merge($parameters, [
            '%website_url%' => $this->websiteUrl
        ]);
        $message = \Swift_Message::newInstance()
            ->setPriority(2)
            ->setSubject($this->translator->trans("$mailKey.subject", $parameters))
            ->setFrom([$this->websiteMail => $this->mailName])
            ->setReplyTo($this->websiteMail)
            ->setTo($email)
            ->setContentType('text/html')
            ->setBody($this->translator->trans("$mailKey.body", $parameters))
        ;
        $headers = $message->getHeaders();
        $headers->addTextHeader('X-Mailer', 'PHP'.phpversion());
        $headers->addTextHeader('Sensitivity', $sensitivity);
        $headers->addTextHeader('Organization', 'Médiévistes');
        return $this->mailer->send($message);
    }
}