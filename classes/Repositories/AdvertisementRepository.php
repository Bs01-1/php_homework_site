<?php


namespace Classes\Repositories;


use Classes\Advertisement;
use Classes\Collections\AdvertisementCollection;
use Classes\Request\AdvertisementRequest;
use Classes\Request\GetAdvertisementRequest;
use Classes\User;

class AdvertisementRepository extends Repository implements AdvertisementRepositoryInterface
{
    public function createAdvertisement (AdvertisementRequest $advertisementRequest, User $user): bool
    {
        $title = $this->connection->real_escape_string($advertisementRequest->title);
        $address = $this->connection->real_escape_string($advertisementRequest->address);
        $about = $this->connection->real_escape_string($advertisementRequest->about);
        $type = $this->connection->real_escape_string($advertisementRequest->type);

        $result =  ($this->connection->query("INSERT INTO content (user_id, title, address, about, type)
            VALUES ({$user->id}, '{$title}', '{$address}', '{$about}', '{$type}')"));
        return (bool) $result;
    }

    public function getLastUserAdvertisement (User $user): ?Advertisement
    {
        $advertisement = $this->connection->query("SELECT * FROM content WHERE user_id = {$user->id} 
            ORDER BY createdAt DESC LIMIT 1");
        $resultArray = $advertisement->fetch_assoc();
        $advertisement->free_result();
        return Advertisement::createFromArray($resultArray);
    }

    public function getAdvertisementByLimitAndOffset(
        int $limit,
        int $offset,
        GetAdvertisementRequest $advertisementRequest
    ): AdvertisementCollection
    {
        $result = $this->connection->query("SELECT * FROM content WHERE type = '{$advertisementRequest->type}' ORDER BY createdAt DESC LIMIT {$limit} OFFSET {$offset}");

        $advertisementCollection = new AdvertisementCollection();
        while ($advertisementArray = $result->fetch_assoc()) {
            $advertisementCollection->addItem(Advertisement::createFromArray($advertisementArray));
        }
        return $advertisementCollection;
    }
}