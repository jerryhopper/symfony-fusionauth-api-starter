<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private $aud;
    private $exp;
    private $iat;
    private $iss;
    private $sub;
    private $authenticationType;
    private $email;
    private $email_verified;
    private $preferred_username;
    private $applicationId;


    private $roles = [];


    public function getPreferredUsername(): ?string
    {
        return $this->preferred_username;
    }
    public function setPreferredUsername(string $preferred_username): self
    {
        $this->preferred_username = $preferred_username;

        return $this;
    }











    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }
    public function setApplicationId(string $applicationId): self
    {
        $this->applicationId = $applicationId;

        return $this;
    }



    public function getEmailVerified(): ?string
    {
        return $this->email_verified;
    }
    public function setEmailVerified(string $email_verified): self
    {
        $this->email_verified = $email_verified;

        return $this;
    }

    public function getAuthenticationType(): ?string
    {
        return $this->authenticationType;
    }
    public function setAuthenticationType(string $authenticationType): self
    {
        $this->authenticationType = $authenticationType;

        return $this;
    }

    public function getIss(): ?string
    {
        return $this->iss;
    }
    public function setIss(string $iss): self
    {
        $this->iss = $iss;

        return $this;
    }

    public function getIat(): ?string
    {
        return $this->iat;
    }
    public function setIat(string $iat): self
    {
        $this->iat = $iat;

        return $this;
    }
    public function getExp(): ?string
    {
        return $this->exp;
    }
    public function setExp(string $exp): self
    {
        $this->exp = $exp;

        return $this;
    }

    public function getSub(): ?string
    {
        return $this->sub;
    }
    public function setSub(string $sub): self
    {
        $this->sub = $sub;

        return $this;
    }
    public function getAud(): ?string
    {
        return $this->aud;
    }

    public function setAud(string $aud): self
    {
        $this->aud = $aud;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->preferred_username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
