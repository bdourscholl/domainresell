<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\App;
use App\Core\Logger;
use App\Models\EmailTemplate;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class EmailService
{
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('mail');
    }

    public function send(string $to, string $subject, string $body, bool $isHtml = true): bool
    {
        $mail = new PHPMailer(true);

        try {
            if (empty($this->config['username']) || empty($this->config['password'])) {
                Logger::info('Email skipped (SMTP not configured): ' . $to);
                return false;
            }

            $mail->isSMTP();
            $mail->Host = $this->config['host'];
            $mail->Port = (int) $this->config['port'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['username'];
            $mail->Password = $this->config['password'];
            $mail->SMTPSecure = $this->config['encryption'] ?? 'tls';
            $mail->Timeout = 5;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($this->config['from_address'], $this->config['from_name']);
            $mail->addAddress($to);

            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body = $body;

            if ($isHtml) {
                $mail->AltBody = strip_tags($body);
            }

            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            Logger::error('Email send failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendTemplate(string $templateSlug, string $to, array $variables): bool
    {
        $rendered = EmailTemplate::render($templateSlug, $variables);
        if (!$rendered) {
            Logger::error("Email template not found: {$templateSlug}");
            return false;
        }

        return $this->send($to, $rendered['subject'], $rendered['body']);
    }
}
