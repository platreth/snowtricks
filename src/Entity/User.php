<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email")
 * @ORM\Entity()
 */
class User implements UserInterface, \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=250)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles = array();

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trick", mappedBy="author", orphanRemoval=true)
     */
    private $tricks;

    public function __construct() {
        $this->isActive = true;
        $this->tricks = new ArrayCollection();
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getUsername() {
        return $this->email;
    }

    public function getSalt() {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword() {
        return $this->password;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    public function getRoles() {
        if (empty($this->roles)) {
            return ['ROLE_USER'];
        }
        return $this->roles;
    }

    function addRole($role) {
        $this->roles[] = $role;
    }

    public function eraseCredentials() {
        
    }

    /** @see \Serializable::serialize() */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
                // see section on salt below
                // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized) {
        list (
                $this->id,
                $this->email,
                $this->password,
                $this->isActive,
                // see section on salt below
                // $this->salt
                ) = unserialize($serialized);
    }

    function getId() {
        return $this->id;
    }

    function getEmail() {
        return $this->email;
    }

    function getPlainPassword() {
        return $this->plainPassword;
    }

    function getIsActive() {
        return $this->isActive;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
    }

    function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    /**
     * @return Collection|Trick[]
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTrick(Trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->setAuthor($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
            // set the owning side to null (unless already changed)
            if ($trick->getAuthor() === $this) {
                $trick->setAuthor(null);
            }
        }

        return $this;
    }

}
