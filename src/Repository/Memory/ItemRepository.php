<?php

namespace App\Repository\Memory;

use App\Domain\Item;
use App\Domain\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
{
	/**
	 * @var Item[]
	 */
	private array $items = [];

	public function add(Item $item): void
	{
		if (in_array($item, $this->items)) {
			return;
		}

		$this->items[] = $item;
	}

	public function loadAll(): array
	{
		return $this->items;
	}
}
