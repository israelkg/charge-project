<?php

namespace App\Repository;

use MongoDB\Client as MongoClient;
use App\Document\User;            
use MongoDB\BSON\ObjectId;        
use DateTimeImmutable;            
use MongoDB\Model\BSONArray;

class UserRepository{
    private \MongoDB\Collection $collection; 

    public function __construct(MongoClient $mongoClient, string $databaseName = 'billing_db'){
        $this->collection = $mongoClient->selectCollection($databaseName, 'users');
    }

    public function save(User $user): void{
        $userData = $user->toArray(); 

        if ($user->getId()) {
            $this->collection->updateOne(
                ['_id' => new ObjectId($user->getId())],
                ['$set' => $userData]                   
            );
        } else {
            $result = $this->collection->insertOne($userData);
            $reflection = new \ReflectionClass($user);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($user, $result->getInsertedId());
            $idProperty->setAccessible(false);
        }
    }

    public function find(string $id): ?User{
        try {
            $data = $this->collection->findOne(['_id' => new ObjectId($id)]);
            if (!$data) {
                return null;
            }
            return $this->hydrateUser((array) $data);
        } catch (\MongoDB\Driver\Exception\InvalidArgumentException $e) {
            return null;
        }
    }

    public function findByEmail(string $email): ?User{
        $data = $this->collection->findOne(['email' => $email]);
        if (!$data) {
            return null;
        }
        return $this->hydrateUser((array) $data);
    }

    private function hydrateUser(array $data): User{
        $user = new User();

        $reflection = new \ReflectionClass($user);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($user, $data['_id']);
        $idProperty->setAccessible(false);

        if (isset($data['roles']) && $data['roles'] instanceof BSONArray) {
                $user->setRoles($data['roles']->getArrayCopy()); 
            } else {
                $user->setRoles($data['roles'] ?? []);
        }

        $user->setEmail($data['email'] ?? null);
        $user->setPassword($data['password'] ?? null);
        $user->setRoles((array) ($data['roles'] ?? []));

        if (isset($data['data_criacao']) && $data['data_criacao'] instanceof \MongoDB\BSON\UTCDateTime) {
            $dataCriacao = new DateTimeImmutable($data['data_criacao']->toDateTime()->format('Y-m-d H:i:s.u'));
            $reflection = new \ReflectionClass($user);
            $prop = $reflection->getProperty('dataCriacao');
            $prop->setAccessible(true);
            $prop->setValue($user, $dataCriacao);
            $prop->setAccessible(false);
        }

        if (isset($data['data_atualizacao']) && $data['data_atualizacao'] instanceof \MongoDB\BSON\UTCDateTime) {
            $dataAtualizacao = new DateTimeImmutable($data['data_atualizacao']->toDateTime()->format('Y-m-d H:i:s.u'));
            $reflection = new \ReflectionClass($user);
            $prop = $reflection->getProperty('dataAtualizacao');
            $prop->setAccessible(true);
            $prop->setValue($user, $dataAtualizacao);
            $prop->setAccessible(false);
        }

       return $user;
    }
}