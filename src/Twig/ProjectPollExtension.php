<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use Symfony\Component\Translation\TranslatorInterface;

use App\Entity\Project\Vote;

class ProjectPollExtension extends AbstractExtension
{
    /** @var TranslatorInterface **/
    protected $translator;
    /** @var array **/
    protected $options = [
        'positive' => [
            'count' => 0,
            'choices' => [
                Vote::CHOICE_POSITIVE_TECH => 0,
                Vote::CHOICE_POSITIVE_PURPOSE => 0,
            ]
        ],
        'negative' => [
            'count' => 0,
            'choices' => [
                Vote::CHOICE_NEGATIVE_TECH => 0,
                Vote::CHOICE_NEGATIVE_PURPOSE => 0,
                Vote::CHOICE_NEGATIVE_INFOS => 0
            ]
        ]
    ];
    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    public function getFilters(): array
    {
        return [
            new TwigFilter('project_poll_votes', [$this, 'projectPollVotesFilter'], [
                'is_safe' => ['html']
            ]),
        ];
    }
    
    public function projectPollVotesFilter(array $votes)
    {
        $result = '';
        $nbVotes = count($votes);
        
        foreach ($votes as $vote) {
            $option = ($vote->getIsPositive() === true) ? 'positive' : 'negative';
            $this->options[$option]['count']++;
            $this->options[$option]['choices'][$vote->getChoice()]++;
        }
        foreach ($this->options as $key => $data) {
            $result .= "<h4>{$this->translator->trans("projects.votes.{$key}")}<span>{$data['count']}</span></h4>";
            foreach ($data['choices'] as $key => $count) {
                $percent = round($count * 100 / $nbVotes, 2);
                $result .= "<div class='vote-score'>
                    <header><h5>{$this->translator->trans("projects.votes." . str_replace('.', '_', $key))}</h5><span>{$count}</span></header>
                    <section><div class='progress-bar'><div style='width:{$percent}%'></div></div></section>
                </div>";
            }
        }
        return $result;
    }
    
    public function getOptions(): array
    {
        return $this->options;
    }
}