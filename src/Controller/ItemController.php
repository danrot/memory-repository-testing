<?php

namespace App\Controller;

use App\Domain\Item;
use App\Domain\ItemRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
	#[Route('/items', methods: ['GET'])]
	public function list(Request $request, ItemRepositoryInterface $itemRepository): JsonResponse
	{
		$titleFilter = $request->query->getString('titleFilter');
		$items = $titleFilter ? $itemRepository->loadFilteredByTitle($titleFilter) : $itemRepository->loadAll();

		return $this->json($items);
	}

	#[Route('/items', methods: ['POST'])]
	public function create(Request $request, ItemRepositoryInterface $itemRepository): JsonResponse
	{
		/** @var \stdClass */
		$data = json_decode($request->getContent());
		$item = new Item($data->title, $data->description);

		$itemRepository->add($item);

		return $this->json($item);
	}
}
