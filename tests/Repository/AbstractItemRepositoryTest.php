<?php

namespace App\Tests\Repository;

use App\Domain\Item;
use App\Domain\ItemRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractItemRepositoryTest extends KernelTestCase
{
	abstract protected function createItemRepository(): ItemRepositoryInterface;

	abstract protected function flush(): void;

	public function testMultipleAddOfItem(): void
	{
		$itemRepository = $this->createItemRepository();

		$item = new Item('Test title', 'Test description');

		$itemRepository->add($item);
		$itemRepository->add($item);

		$this->flush();

		$items = $itemRepository->loadAll();

		$this->assertCount(1, $items);
		$this->assertContains($item, $items);
	}

	public function testLoadAllWithMultipleItems(): void
	{
		$itemRepository = $this->createItemRepository();

		$item1 = new Item('Test title 1', 'Test description 1');
		$item2 = new Item('Test title 2', 'Test description 2');

		$itemRepository->add($item1);
		$itemRepository->add($item2);

		$this->flush();

		$items = $itemRepository->loadAll();

		$this->assertCount(2, $items);
		$this->assertContains($item1, $items);
		$this->assertContains($item2, $items);
	}

	public function testLoadFilteredByTitle(): void
	{
		$itemRepository = $this->createItemRepository();

		$item1 = new Item('Test title 1', 'Test description 1');
		$item2 = new Item('Title 2', 'Description 2');
		$item3 = new Item('Test title 3', 'Test description 2');

		$itemRepository->add($item1);
		$itemRepository->add($item2);
		$itemRepository->add($item3);

		$this->flush();

		$items = $itemRepository->loadFilteredByTitle('Test title');

		$this->assertCount(2, $items);
		$this->assertContains($item1, $items);
		$this->assertContains($item3, $items);
	}
}
