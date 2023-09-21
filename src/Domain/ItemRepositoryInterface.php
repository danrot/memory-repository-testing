<?php

namespace App\Domain;

interface ItemRepositoryInterface
{
	public function add(Item $item): void;

	/**
	 * @return Item[]
	 */
	public function loadAll(): array;
}
