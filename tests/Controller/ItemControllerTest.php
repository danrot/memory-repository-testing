<?php

namespace App\Tests\Controller;

use App\Domain\Item;
use App\Domain\ItemRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemControllerTest extends WebTestCase
{
	public function testList(): void
	{
		$client = static::createClient();

		/** @var ItemRepositoryInterface */
		$itemRepository = $client->getContainer()->get(ItemRepositoryInterface::class);

		$itemRepository->add(new Item('Title 1', 'Description 1'));
		$itemRepository->add(new Item('Title 2', 'Description 2'));

		$client->request('GET', '/items');

		$responseContent = $client->getResponse()->getContent();
		$this->assertNotFalse($responseContent);
		$responseData = json_decode($responseContent);

		$this->assertIsArray($responseData);
		$this->assertCount(2, $responseData);
		$this->assertEquals('Title 1', $responseData[0]->title);
		$this->assertEquals('Description 1', $responseData[0]->description);
		$this->assertEquals('Title 2', $responseData[1]->title);
		$this->assertEquals('Description 2', $responseData[1]->description);
	}

	public function testListWithTitleFilter(): void
	{
		$client = static::createClient();

		/** @var ItemRepositoryInterface */
		$itemRepository = $client->getContainer()->get(ItemRepositoryInterface::class);

		$itemRepository->add(new Item('Test title 1', 'Description 1'));
		$itemRepository->add(new Item('Title 2', 'Description 2'));
		$itemRepository->add(new Item('Test title 3', 'Description 3'));

		$client->request('GET', '/items?titleFilter=Test title');

		$responseContent = $client->getResponse()->getContent();
		$this->assertNotFalse($responseContent);
		$responseData = json_decode($responseContent);

		$this->assertIsArray($responseData);
		$this->assertCount(2, $responseData);
		$this->assertEquals('Test title 1', $responseData[0]->title);
		$this->assertEquals('Description 1', $responseData[0]->description);
		$this->assertEquals('Test title 3', $responseData[1]->title);
		$this->assertEquals('Description 3', $responseData[1]->description);
	}

	public function testCreate(): void
	{
		$client = static::createClient();

		/** @var ItemRepositoryInterface */
		$itemRepository = $client->getContainer()->get(ItemRepositoryInterface::class);

		$client->jsonRequest('POST', '/items', ['title' => 'Title', 'description' => 'Description']);

		$items = $itemRepository->loadAll();
		$this->assertCount(1, $items);
		$this->assertEquals('Title', $items[0]->getTitle());
		$this->assertEquals('Description', $items[0]->getDescription());
	}
}
