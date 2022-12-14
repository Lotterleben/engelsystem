<?php

namespace Engelsystem\Events\Listener;

use Engelsystem\Mail\EngelsystemMailer;
use Engelsystem\Models\News as NewsModel;
use Engelsystem\Models\User\Settings as UserSettings;
use Engelsystem\Models\User\User;
use Illuminate\Database\Eloquent\Collection;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;

class News
{
    /** @var LoggerInterface */
    protected $log;

    /** @var EngelsystemMailer */
    protected $mailer;

    /** @var UserSettings */
    protected $settings;

    public function __construct(
        LoggerInterface $log,
        EngelsystemMailer $mailer,
        UserSettings $settings
    ) {
        $this->log = $log;
        $this->mailer = $mailer;
        $this->settings = $settings;
    }

    public function created(NewsModel $news): void
    {
        /** @var UserSettings[]|Collection $recipients */
        $recipients = $this->settings
            ->whereEmailNews(true)
            ->with('user')
            ->get();

        foreach ($recipients as $recipient) {
            $this->sendMail($news, $recipient->user, 'notification.news.new', 'emails/news-new');
        }
    }

    protected function sendMail(NewsModel $news, User $user, string $subject, string $template): void
    {
        try {
            $this->mailer->sendViewTranslated(
                $user,
                $subject,
                $template,
                ['title' => $news->title, 'news' => $news, 'username' => $user->name]
            );
        } catch (TransportException $e) {
            $this->log->error(
                'Unable to send email "{title}" to user {user} with {exception}',
                ['title' => $subject, 'user' => $user->name, 'exception' => $e]
            );
        }
    }
}
