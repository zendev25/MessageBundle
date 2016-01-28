<?php

namespace ZEN\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Discussion
 *
 * @ORM\Entity(repositoryClass="ZEN\MessageBundle\Repository\DiscussionRepository")
 */
class Discussion {

    const READ = 'read';
    const UNREAD = 'unread';

    public static function getIsReadList() {
        return array(
            0 => self::UNREAD,
            1 => self::READ,
        );
    }

    public static function getIsReadValue($num) {
        $isRead = Discussion::getIsReadList();
        return $isRead[$num];
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     * @Assert\Length(min = "1", max = "255")
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\OneToMany(targetEntity="ZEN\MessageBundle\Entity\Message", mappedBy="discussion")
     */
    private $messages;

    /**
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="ZEN\MessageBundle\Model\UserInterface")
     */
    private $author;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="date_update", type="datetime")
     */
    private $dateUpdate;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="CatMessage")
     */
    private $catMessage;

    /**
     * @var integer
     * @Assert\Type(type="integer")
     * @ORM\Column(name="archived", type="smallint")
     */
    private $archived = 0;

    /**
     * @var integer
     * @Assert\Type(type="integer")
     * @ORM\Column(name="archived_admin", type="smallint")
     */
    private $archivedAdmin = 0;

    /**
     * @var integer
     * @Assert\Type(type="integer")
     * @ORM\Column(name="is_read", type="smallint")
     */
    private $isRead = 0;

    /**
     * @var integer
     * @Assert\Type(type="integer")
     * @ORM\Column(name="is_read_admin", type="smallint")
     */
    private $isReadAdmin = 0;

    /**
     * @ORM\ManyToOne(targetEntity="ZEN\MessageBundle\Model\GroupInterface")
     */
    private $group;

    /**
     * Constructor
     */
    public function __construct() {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Discussion
     */
    public function setSubject($subject) {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * Set archived
     *
     * @param integer $archived
     * @return Discussion
     */
    public function setArchived($archived) {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return integer 
     */
    public function getArchived() {
        return $this->archived;
    }

    /**
     * Set archivedAdmin
     *
     * @param integer $archivedAdmin
     * @return Discussion
     */
    public function setArchivedAdmin($archivedAdmin) {
        $this->archivedAdmin = $archivedAdmin;

        return $this;
    }

    /**
     * Get archivedAdmin
     *
     * @return integer 
     */
    public function getArchivedAdmin() {
        return $this->archivedAdmin;
    }

    /**
     * Set isRead
     *
     * @param integer $isRead
     * @return Discussion
     */
    public function setIsRead($isRead) {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return integer 
     */
    public function getIsRead() {
        return $this->isRead;
    }

    /**
     * Set isReadAdmin
     *
     * @param integer $isReadAdmin
     * @return Discussion
     */
    public function setIsReadAdmin($isReadAdmin) {
        $this->isReadAdmin = $isReadAdmin;

        return $this;
    }

    /**
     * Get isReadAdmin
     *
     * @return integer 
     */
    public function getIsReadAdmin() {
        return $this->isReadAdmin;
    }

    /**
     * Add messages
     *
     * @param \ZEN\MessageBundle\Entity\Message $messages
     * @return Discussion
     */
    public function addMessage(\ZEN\MessageBundle\Entity\Message $messages) {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \ZEN\MessageBundle\Entity\Message $messages
     */
    public function removeMessage(\ZEN\MessageBundle\Entity\Message $messages) {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * Set catMessage
     *
     * @param \ZEN\MessageBundle\Entity\CatMessage $catMessage
     * @return Discussion
     */
    public function setCatMessage(\ZEN\MessageBundle\Entity\CatMessage $catMessage = null) {
        $this->catMessage = $catMessage;

        return $this;
    }

    /**
     * Get catMessage
     *
     * @return \ZEN\MessageBundle\Entity\CatMessage 
     */
    public function getCatMessage() {
        return $this->catMessage;
    }

    /**
     * Set author
     *
     * @param \ZEN\MessageBundle\Model\UserInterface $author
     * @return Discussion
     */
    public function setAuthor(\ZEN\MessageBundle\Model\UserInterface $author = null) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \ZEN\MessageBundle\Model\UserInterface 
     */
    public function getAuthor() {
        return $this->author;
    }


    /**
     * Set group
     *
     * @param \ZEN\MessageBundle\Model\GroupInterface $group
     * @return Discussion
     */
    public function setGroup(\ZEN\MessageBundle\Model\GroupInterface $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \ZEN\MessageBundle\Model\GroupInterface 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set dateUpdate
     *
     * @param string $dateUpdate
     * @return Discussion
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return string 
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }
}
