<?php
namespace T3DD\Backend\Tests\Functional\Registration;

use T3DD\Backend\Domain\Model\Registration\AbstractBookable;
use T3DD\Backend\Domain\Service\Registration\BookableService;
use TYPO3\Flow\var_dump;

/**
 * Enter descriptions here
 */
class BookableServiceTest extends \TYPO3\Flow\Tests\FunctionalTestCase  {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var BookableService
	 */
	protected $bookableService;

	/**
	 * @var \T3DD\Backend\Domain\Repository\Registration\RoomRepository
	 */
	protected $roomRepository;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->bookableService = $this->objectManager->get(BookableService::class);
		$this->roomRepository = $this->objectManager->get(\T3DD\Backend\Domain\Repository\Registration\RoomRepository::class);
	}

	/**
	 * @param int $size
	 * @return array
	 */
	protected function buildBookableRequest($size, $divisor = 1) {
		$bookableRequest = [];
		for ($i = 0; $i < $size; $i++) {
			$participant = new \T3DD\Backend\Domain\Model\Registration\Participant();
			$participant->setName(uniqid());
			$bookableRequest[] = [
				'participant' => $participant,
				'divisor' => $divisor,
				'quotaApplies' => TRUE
			];
		}
		return $bookableRequest;
	}

	/**
	 * @return array
	 */
	public function requestingRoomBookingsPersistsThoseDataProvider() {
		return [ [2], [3], [4] ];
	}

	/**
	 * @test
	 * @dataProvider requestingRoomBookingsPersistsThoseDataProvider
	 * @param int $requestAmount
	 */
	public function requestingRoomBookingsPersistsThose($requestAmount) {
		$roomRequests = $this->buildBookableRequest($requestAmount, $requestAmount);

		$this->bookableService->requestRooms($roomRequests);
		$this->persistenceManager->persistAll();
		$this->assertEquals($requestAmount, $this->roomRepository->countAll());
	}

	/**
	 * @return array
	 */
	public function requestingRoomBookingsSetsWaitingProperlyDataProvider() {
		return [
			[3, 1, 67, 67, 0, 3],
			[3, 1, 64, 67, 3, 0],
			[3, 1, 65, 67, 2, 1],
			[6, 2, 134, 67, 0, 6],
			[6, 2, 128, 67, 6, 0],
			[6, 2, 130, 67, 4, 2],
			[9, 3, 291, 67, 0, 9],
			[9, 3, 192, 67, 9, 0],
			[9, 3, 195, 67, 6, 3],
		];
	}

	/**
	 * @test
	 * @dataProvider requestingRoomBookingsSetsWaitingProperlyDataProvider
	 * @param int $requestAmount
	 * @param int $spentAmount
	 * @param int $availableQuota
	 * @param int $expectedPendingAmount
	 * @param int $expectedWaitingAmount
	 */
	public function requestingRoomBookingsSetsWaitingProperly($requestAmount, $divisor, $spentAmount, $availableQuota, $expectedPendingAmount, $expectedWaitingAmount) {
		$this->inject($this->bookableService, 'configuration', [BookableService::TYPE_ROOM => ['fractionBase' => 12, 'availableQuota' => $availableQuota]]);
		$this->bookableService->requestRooms($this->buildBookableRequest($spentAmount, $divisor));
		$this->persistenceManager->persistAll();
		$requestedRooms = $this->bookableService->requestRooms($this->buildBookableRequest($requestAmount, $divisor));
		$requestedStates = [];
		foreach ($requestedRooms as $i => $requestedRoom) {
			$requestedStates[$i] = $requestedRoom->getBookingState();
		}
		$expectedStates = [];
		for ($i = 0; $i < $expectedPendingAmount; $i++) {
			$expectedStates[$i] = AbstractBookable::BOOKING_STATE_PENDING;
		}
		for ($i = $expectedPendingAmount; $i < ($expectedPendingAmount + $expectedWaitingAmount); $i++) {
			$expectedStates[$i] = AbstractBookable::BOOKING_STATE_WAITING;
		}
		$this->assertEquals($expectedStates, $requestedStates);
	}

	/**
	 * @test
	 */
	public function requestingRoomBookingWithoutQuotaApplicationDoesNotIncreaseSpentAmount() {
		$this->inject($this->bookableService, 'configuration', [BookableService::TYPE_ROOM => ['fractionBase' => 12, 'availableQuota' => 10]]);
		$this->bookableService->requestRooms($this->buildBookableRequest(10));
		$this->persistenceManager->persistAll();
		$requests = $this->buildBookableRequest(3);
		$requests = array_map(function($request) { $request['quotaApplies'] = FALSE; return $request; }, $requests);
		$requestedRooms = $this->bookableService->requestRooms($requests);
		$this->persistenceManager->persistAll();
		$requestedStates = array_map(function($requestedRoom) { return $requestedRoom->getBookingState();}, $requestedRooms);
		$this->assertEquals(array_fill(0, 3, AbstractBookable::BOOKING_STATE_PENDING), $requestedStates);
		$this->assertEquals(10, $this->roomRepository->getSpentQuota() / 12);
	}

}