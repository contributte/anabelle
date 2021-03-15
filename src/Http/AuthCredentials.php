<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Http;

final class AuthCredentials
{

	/**
	 * @var string|null
	 */
	private $user;

	/**
	 * @var string|null
	 */
	private $pass;


	public function __construct(?string $user, ?string $pass)
	{
		$this->user = $user;
		$this->pass = $pass;
	}


	public function getUser(): ?string
	{
		return $this->user;
	}


	public function getPass(): ?string
	{
		return $this->pass;
	}
}
