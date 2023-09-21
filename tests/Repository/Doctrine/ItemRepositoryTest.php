<?php

namespace App\Tests\Repository\Doctrine;

use App\Domain\ItemRepositoryInterface;
use App\Repository\Doctrine\ItemRepository;
use App\Tests\Repository\AbstractItemRepositoryTest;
use Doctrine\ORM\EntityManagerInterface;

class ItemRepositoryTest extends AbstractItemRepositoryTest
{
	protected function createItemRepository(): ItemRepositoryInterface
	{
		return new ItemRepository($this->getContainer()->get(EntityManagerInterface::class));
	}

	protected function flush(): void
	{
		$this->getContainer()->get(EntityManagerInterface::class)->flush();
	}

	protected function setUp(): void
	{
		$this->getContainer()->get(EntityManagerInterface::class)->getConnection()->setNestTransactionsWithSavepoints(true);
		$this->getContainer()->get(EntityManagerInterface::class)->getConnection()->beginTransaction();
	}

	protected function tearDown(): void
	{
		$this->getContainer()->get(EntityManagerInterface::class)->getConnection()->rollBack();
	}
}
