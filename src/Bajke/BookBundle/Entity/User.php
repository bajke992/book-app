<?php

namespace Bajke\BookBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @Serializer\ExclusionPolicy("all")
 */
class User extends BaseUser implements \Serializable{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Serializer\Expose
     * @ORM\Column(name="google_id", type="string", length=255, unique=true, nullable=true)
     */
    private $googleId;

    /**
     * @var string
     *
     * @Serializer\Expose
     * @ORM\Column(nullable=true)
     */
    private $realname;

    /**
     * @var string
     *
     * @Serializer\Expose
     * @ORM\Column(nullable=true)
     */
    private $nickname;

    /**
     * @var mixed
     *
     * @Serializer\Expose
     * @ORM\OneToMany(targetEntity="Book", mappedBy="owner")
     */
    private $books;

    /**
     * @var mixed
     *
     * @Serializer\Expose
     * @ORM\OneToMany(targetEntity="Client", mappedBy="owner")
     */
    protected $clients;

    public function __construct()
    {
        parent::__construct();
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
     * @return mixed
     */
    public function getBooks() {
        return $this->books;
    }

    /**
     * @param mixed $books
     */
    public function setBooks($books) {
        $this->books = $books;
    }

    /**
     * @return string
     */
    public function getGoogleId() {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     */
    public function setGoogleId($googleId) {
        $this->googleId = $googleId;
    }

    /**
     * @return string
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * @param string $realname
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param mixed $clients
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    public function serialize() {
        return serialize(array(
            $this->id
        ));
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            ) = unserialize($serialized);
    }
}