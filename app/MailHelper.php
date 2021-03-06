<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Copywrite;
use App\CustomConfig;
use Illuminate\Support\Facades\Mail;

class MailHelper extends Model
{
    /*
     * this mail helper is for reset password
     *
     * @var $mailParams Array mixed
     * @return $email Object mixed
     *
     */

    public function createResetPasswordMail(array $mailParams) {

        $toReplace = ['/:full_name:/', '/:reset_token:/'];
        $fromReplace = [$mailParams['mail_to_name'], $mailParams['reset_token']];
        $emailContent = preg_replace($toReplace, $fromReplace, Copywrite::MAIL_RESET_PASSWORD_BODY_HTML);

        $mailboxParams = [
            'mail_content' => $emailContent,
            'email_to' => $mailParams['mail_to_email'],
            'name_to' => $mailParams['mail_to_name'],
            'email_from' => env('MAIL_FROM_ADDRESS'),
            'name_from' => env('MAIL_FROM_NAME')
        ];

        $fireMailbox = Mail::send('reset_password_mail', $mailboxParams, function($message) use ($mailboxParams) {
                    $message->from($mailboxParams['email_from'], $mailboxParams['name_from']);
                    $message->to($mailboxParams['email_to'], $mailboxParams['name_to'])->subject(Copywrite::MAIL_RESET_PASSWORD_SUBJECT);
                });

        return $fireMailbox;
    }

    public function accountVerificationMail(array $mailParams) {
        $toReplace = ['/:full_name:/', '/:activation_spiel:/', '/:activation_link:/'];
        $fromReplace = [$mailParams['mail_fullname'], $mailParams['activation_spiel'], $mailParams['activation_link']];
        $emailContent = preg_replace($toReplace, $fromReplace, Copywrite::MAIL_ACTIVATION_BODY_HTML);

        $mailboxParams = [
            'mail_content' => $emailContent,
            'email_to' => $mailParams['mail_to_email'],
            'name_to' => $mailParams['mail_to_name'],
            'email_from' => env('MAIL_FROM_ADDRESS'),
            'name_from' => env('MAIL_FROM_NAME')
        ];

        $fireMailbox = Mail::send('account_activation_mail', $mailboxParams, function($message) use ($mailboxParams) {
            $message->from($mailboxParams['email_from'], $mailboxParams['name_from']);
            $message->to($mailboxParams['email_to'], $mailboxParams['name_to'])->subject(Copywrite::MAIL_ACTIVATION_SUBJECT);
        });

        return $fireMailbox;
    }
}
