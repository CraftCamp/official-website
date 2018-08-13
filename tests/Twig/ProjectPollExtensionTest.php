<?php

namespace Tests\Twig;

use App\Twig\ProjectPollExtension;

use App\Entity\Project\Vote;

class ProjectPollExtensionTest extends \PHPUnit\Framework\TestCase
{
    /** @var ProjectPollExtension **/
    protected $extension;
    
    public function setUp()
    {
        $this->extension = new ProjectPollExtension($this->getTranslatorMock());
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function testProjectPollVotesFilter(array $votes, array $expected)
    {
        $this->extension->projectPollVotesFilter($votes);
        $this->assertEquals($this->extension->getOptions(), $expected);
    }
    
    public function getTranslatorMock()
    {
        $translatorMock = $this->createMock(\Symfony\Component\Translation\TranslatorInterface::class);
        $translatorMock
            ->expects($this->any())
            ->method('trans')
            ->willReturnArgument(0)
        ;
        return $translatorMock;
    }
    
    public function dataProvider()
    {
        return [
            [
                [
                    (new Vote())
                    ->setIsPositive(true)
                    ->setChoice(Vote::CHOICE_POSITIVE_TECH),
                    (new Vote())
                    ->setIsPositive(true)
                    ->setChoice(Vote::CHOICE_POSITIVE_TECH),
                    (new Vote())
                    ->setIsPositive(true)
                    ->setChoice(Vote::CHOICE_POSITIVE_PURPOSE),
                    (new Vote())
                    ->setIsPositive(false)
                    ->setChoice(Vote::CHOICE_NEGATIVE_INFOS),
                ],
                [
                    'positive' => [
                        'count' => 3,
                        'choices' => [
                            Vote::CHOICE_POSITIVE_TECH => 2,
                            Vote::CHOICE_POSITIVE_PURPOSE => 1,
                        ]
                    ],
                    'negative' => [
                        'count' => 1,
                        'choices' => [
                            Vote::CHOICE_NEGATIVE_TECH => 0,
                            Vote::CHOICE_NEGATIVE_PURPOSE => 0,
                            Vote::CHOICE_NEGATIVE_INFOS => 1
                        ]
                    ]
                ]
            ],
            [
                [
                    (new Vote())
                    ->setIsPositive(true)
                    ->setChoice(Vote::CHOICE_POSITIVE_TECH),
                    (new Vote())
                    ->setIsPositive(false)
                    ->setChoice(Vote::CHOICE_NEGATIVE_INFOS),
                    (new Vote())
                    ->setIsPositive(false)
                    ->setChoice(Vote::CHOICE_NEGATIVE_INFOS),
                    (new Vote())
                    ->setIsPositive(false)
                    ->setChoice(Vote::CHOICE_NEGATIVE_INFOS),
                    (new Vote())
                    ->setIsPositive(false)
                    ->setChoice(Vote::CHOICE_NEGATIVE_TECH),
                    (new Vote())
                    ->setIsPositive(false)
                    ->setChoice(Vote::CHOICE_NEGATIVE_TECH),
                ],
                [
                    'positive' => [
                        'count' => 1,
                        'choices' => [
                            Vote::CHOICE_POSITIVE_TECH => 1,
                            Vote::CHOICE_POSITIVE_PURPOSE => 0,
                        ]
                    ],
                    'negative' => [
                        'count' => 5,
                        'choices' => [
                            Vote::CHOICE_NEGATIVE_TECH => 2,
                            Vote::CHOICE_NEGATIVE_PURPOSE => 0,
                            Vote::CHOICE_NEGATIVE_INFOS => 3
                        ]
                    ]
                ]
            ]
        ];
    }
}