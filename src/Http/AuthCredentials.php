<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Http;

final class AuthCredentials
{

	public function __construct(private ?string $user, private ?string $pass) {}


	public function getUser(): ?string
	{
		return $this->user;
	}


	public function getPass(): ?string
	{
		return $this->pass;
	}
}
