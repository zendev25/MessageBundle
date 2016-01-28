<?php

namespace ZEN\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ZEN\LocaleBundle\Entity\TranslatableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CatMessage
 *
 * @ORM\Entity(repositoryClass="ZEN\MessageBundle\Repository\CatMessageRepository")
 */
class CatMessage extends TranslatableEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(min = "1", max = "255")
     * @ORM\Column(name="dev_alias", type="string", length=255)
     */
    private $devAlias;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=250)
     */
    private $name;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * Set devAlias
     *
     * @param string $devAlias
     * @return CatMessage
     */
    public function setDevAlias($devAlias) {
        $this->devAlias = $devAlias;

        return $this;
    }

    /**
     * Get devAlias
     *
     * @return string 
     */
    public function getDevAlias() {
        return $this->devAlias;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CatMessage
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

}
