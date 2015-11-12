<?php

namespace ZEN\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(options={"engine"="MyISAM"})
 * @ORM\Entity(repositoryClass="ZEN\MessageBundle\Repository\MessageRepository")
 */
class Message {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var text
     * @Assert\Type(type="string")
     * @Assert\NotNull
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="ZEN\MessageBundle\Model\UserInterface")
     */
    private $sender;

    /**
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="ZEN\MessageBundle\Model\UserInterface")
     */
    private $receiver;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="date_send", type="string")
     */
    private $dateSend;

    /**
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="Discussion", inversedBy="messages", cascade={"persist"})
     */
    private $discussion;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }

    
    /**
     * Set discussion
     *
     * @param \ZEN\MessageBundle\Entity\Discussion $discussion
     * @return Message
     */
    public function setDiscussion(\ZEN\MessageBundle\Entity\Discussion $discussion = null) {
        $this->discussion = $discussion;

        return $this;
    }

    /**
     * Get discussion
     *
     * @return \ZEN\MessageBundle\Entity\Discussion 
     */
    public function getDiscussion() {
        return $this->discussion;
    }


    /**
     * Set sender
     *
     * @param \ZEN\MessageBundle\Model\UserInterface $sender
     * @return Message
     */
    public function setSender(\ZEN\MessageBundle\Model\UserInterface $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \ZEN\MessageBundle\Model\UserInterface 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param \ZEN\MessageBundle\Model\UserInterface $receiver
     * @return Message
     */
    public function setReceiver(\ZEN\MessageBundle\Model\UserInterface $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \ZEN\MessageBundle\Model\UserInterface 
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set dateSend
     *
     * @param \DateTime $dateSend
     * @return Message
     */
    public function setDateSend($dateSend)
    {
        $this->dateSend = $dateSend;

        return $this;
    }

    /**
     * Get dateSend
     *
     * @return \DateTime 
     */
    public function getDateSend()
    {
        return $this->dateSend;
    }
}
