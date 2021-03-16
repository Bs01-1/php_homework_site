<?php


namespace Classes\Repositories;


use Classes\Advertisement;
use Classes\Collections\AdvertisementCollection;
use Classes\Request\AdvertisementRequest;
use Classes\Request\GetAdvertisementRequest;
use Classes\Request\SetVote;
use Classes\User;

class AdvertisementRepository extends Repository implements AdvertisementRepositoryInterface
{
    public function createAdvertisement (AdvertisementRequest $advertisementRequest, User $user): bool
    {
        $title = $this->connection->real_escape_string($advertisementRequest->title);
        $address = $this->connection->real_escape_string($advertisementRequest->address);
        $about = $this->connection->real_escape_string($advertisementRequest->about);
        $type = $this->connection->real_escape_string($advertisementRequest->type);

        $result =  ($this->connection->query("INSERT INTO advertisement (user_id, title, address, about, type, price)
            VALUES ({$user->id}, '{$title}', '{$address}', '{$about}', '{$type}', {$advertisementRequest->price})"));
        return (bool) $result;
    }

    public function getLastUserAdvertisement (User $user): ?Advertisement
    {
        $advertisement = $this->connection->query("SELECT * FROM advertisement WHERE user_id = {$user->id} 
            ORDER BY createdAt DESC LIMIT 1");
        $resultArray = $advertisement->fetch_assoc();
        $advertisement->free_result();
        return Advertisement::createFromArray($resultArray);
    }

    public function getAdvertisementByLimitAndOffset(
        int $limit,
        int $offset,
        GetAdvertisementRequest $advertisementRequest
    ): ?AdvertisementCollection
    {
        $result = $this->connection->query("
            SELECT * FROM advertisement WHERE type = '{$advertisementRequest->type}' ORDER BY createdAt DESC LIMIT {$limit} OFFSET {$offset}
            ");

        $advertisementCollection = new AdvertisementCollection();
        if (!$result->num_rows){
            return null;
        }
        while ($advertisementArray = $result->fetch_assoc()) {
            $advertisementCollection->addItem(Advertisement::createFromArray($advertisementArray));
        }
        return $advertisementCollection;
    }

    public function addRatingByAdvertisementId(SetVote $setVote): bool
    {
        $positiveVote = filter_var($setVote->positive_vote, FILTER_VALIDATE_BOOLEAN);
        $intVote = $positiveVote ? 1 : -1;

        return $this->connection->query("UPDATE advertisement SET rating = rating + {$intVote} 
            WHERE id = {$setVote->advertisement_id}");
    }

    public function getAdvertisementById(int $id): ?Advertisement
    {
        $advertisement = $this->connection->query("SELECT * FROM advertisement WHERE id = {$id}");
        if (!$advertisement || $advertisement->num_rows < 1){
            return null;
        }
        $advertisementArray = $advertisement->fetch_assoc();
        $advertisement->free_result();

        return Advertisement::createFromArray($advertisementArray);
    }

    public function getCountAdvertisement(string $type): Int
    {
        $type = $this->connection->real_escape_string($type);
        $result = $this->connection->query("SELECT COUNT(id) as count FROM advertisement WHERE type = '{$type}'");
        $resultArray = $result->fetch_assoc();
        return $resultArray['count'];
    }
}