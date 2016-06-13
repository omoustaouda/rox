<?php

namespace Rox\Core\Extension;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Query\Expression;
use Rox\Member\Model\Member;
use Rox\Message\Model\Message;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig_Extension;
use Twig_Extension_GlobalsInterface;
use Twig_SimpleFunction;

class RoxTwigExtension extends Twig_Extension implements Twig_Extension_GlobalsInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Returns a list of filters.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('ago', [$this, 'ago']),
        ];
    }

    public function ago(Carbon $carbon)
    {
        return $carbon->diffForHumans();
    }

    /**
     * Name of this extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'LayoutKit';
    }

    public function getGlobals()
    {
        $words = new \MOD_words();
        $model = new \FlaglistModel();
        $langarr = array();
        foreach($model->getLanguages() as $language) {
            $lang = new \stdClass;
            $lang->NativeName = $language->Name;
            $lang->TranslatedName = $words->getSilent($language->WordCode);
            $lang->ShortCode = $language->ShortCode;
            $langarr[$language->ShortCode] = $lang;
        }
        $defaultLanguage = $langarr[$this->session->get( 'lang' , 'en')];
        usort($langarr, function($a, $b) {
            if ($a->TranslatedName == $b->TranslatedName) {
                return 0;
            }
            return (strtolower($a->TranslatedName) < strtolower($b->TranslatedName)) ? -1 : 1;
        });

        $member = $this->getMember();

        return [
            'version'   => trim(file_get_contents('VERSION')),
            'version_dt' => Carbon::createFromTimestamp(filemtime('VERSION')),
            'title'     => 'BeWelcome',
            'my_member' => $member,
            'messageCount' => $member ? $this->getMessageCount($member) : null,
            'faker' => class_exists(Factory::class) ? Factory::create() : null,
            'language'  => $defaultLanguage,
            'languages' => $langarr,
        ];
    }

    /**
     * @return Member|null
     */
    protected function getMember()
    {
        $memberId = $this->session->get('IdMember');

        if (!$memberId) {
            return;
        }

        $memberModel = new Member();

        return $memberModel->getById($memberId);
    }

    protected function getMessageCount(Member $member)
    {
        $message = new Message();

        $messageCount = $message->getConnection()->query()
            ->select([
                new Expression('COUNT(*) as cnt'),
            ])
            ->from($message->getTable())
            ->where('IdReceiver', (int) $member->id)
            ->where('WhenFirstRead', '0000-00-00 00:00')
            ->where('Status', 'Sent');

        return (int) $messageCount->value('cnt');
    }
}
