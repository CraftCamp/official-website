<?php

namespace Tests\Manager\Project;

use App\Manager\Project\VoteManager;

use App\Entity\Project\Vote;
use App\Entity\Project\Poll;
use App\Entity\User\Member;

class VoteManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var VoteManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new VoteManager($this->getEntityManagerMock());
    }
    
    public function testGetUserVote()
    {
        $vote = $this->manager->getUserVote($this->getPollMock(), $this->getUserMock()->setId(2));
        
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals(1, $vote->getPoll()->getId());
        $this->assertEquals('kern', $vote->getUser()->getUsername());
    }
    
    public function testGetPollVotes()
    {
        $votes = $this->manager->getPollVotes((new Poll()));
        
        $this->assertCount(4, $votes);
    }
    
    public function testVote()
    {
        $vote = $this->manager->vote($this->getPollMock(), $this->getUserMock(), true, Vote::CHOICE_POSITIVE_TECH);
        
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals(1, $vote->getPoll()->getId());
        $this->assertEquals('kern', $vote->getUser()->getUsername());
        $this->assertTrue($vote->getIsPositive());
        $this->assertEquals(Vote::CHOICE_POSITIVE_TECH, $vote->getChoice());
    }
    
    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage projects.votes.already_voted
     */
    public function testVoteTwice()
    {
        $this->manager->vote($this->getPollMock(), (new Member())->setId(2), false, Vote::CHOICE_NEGATIVE_INFOS);
    }
    
    
    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage projects.votes.already_ended
     */
    public function testVoteWithEndedPoll()
    {
        $this->manager->vote($this->getPollMock()->setIsEnded(true), (new Member()), false, Vote::CHOICE_NEGATIVE_INFOS);
    }
    
    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage projects.votes.invalid_choice
     */
    public function testVoteWithInvalidChoice()
    {
        $this->manager->vote($this->getPollMock(), $this->getUserMock(), true, 'toto');
    }
    
    public function getEntityManagerMock()
    {
        $entityManagerMock = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $entityManagerMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback([$this, 'getRepositoryMock'])
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true)
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('flush')
            ->willReturn(true)
        ;
        return $entityManagerMock;
    }
    
    public function getRepositoryMock()
    {
        $repositoryMock = $this
            ->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy', 'findByPoll'])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findOneBy')
            ->willReturnCallback([$this, 'getVoteMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findByPoll')
            ->willReturnCallback([$this, 'getPollVotesMock'])
        ;
        return $repositoryMock;
    }
    
    public function getVoteMock( $criterias)
    {
        if ($criterias['user']->getId() === 1) {
            return null;
        }
        return
            (new Vote())
            ->setPoll($criterias['poll'])
            ->setUser($criterias['user'])
            ->setIsPositive(true)
            ->setChoice(Vote::CHOICE_POSITIVE_TECH)
        ;
    }
    
    public function getPollVotesMock(Poll $poll)
    {
        return [
            (new Vote())
            ->setPoll($poll)
            ->setIsPositive(true)
            ->setChoice(Vote::CHOICE_POSITIVE_TECH),
            (new Vote())
            ->setPoll($poll)
            ->setIsPositive(true)
            ->setChoice(Vote::CHOICE_POSITIVE_PURPOSE),
            (new Vote())
            ->setPoll($poll)
            ->setIsPositive(false)
            ->setChoice(Vote::CHOICE_NEGATIVE_PURPOSE),
            (new Vote())
            ->setPoll($poll)
            ->setIsPositive(true)
            ->setChoice(Vote::CHOICE_POSITIVE_TECH),
        ];
    }
    
    public function getPollMock()
    {
        return
            (new Poll())
            ->setId(1)
            ->setIsEnded(false)
        ;
    }
    
    public function getUserMock()
    {
        return
            (new Member())
            ->setId(1)
            ->setUsername('kern')
        ;
    }
}