<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\select;

use Epaphrodites\database\query\Builders;

class auth extends Builders
{

  /**
   * Verify if useraccount table exist in database (For mongodb)
   * @return bool
   */
  private function ifCollectionExist():bool
  {

    $collections = $this->rdb(1)->listCollections();

    $result = false;

    foreach ($collections as $collectionInfo) {
      if ($collectionInfo->getName() === "useraccount") {
        $result = true;
        break;
      }
    }

    return $result;
  }

  /**
   * Verify if useraccount table exist in database (For mongodb)
   * @return bool
   */
  private function ifKeyExist():bool
  {

    $result = $this->key('useraccount')->index('*')->isExist();

    return $result;
  }  

  /**
   * Request to select all users of database (For mongo db)
   * 
   * @param string $loginuser
   * @return array|bool
   */
  public function findNosqlUsers(string $loginuser):array|bool
  {

    if ($this->ifCollectionExist() === true) {

      $documents = [];

      $result = $this->db(1)
        ->selectCollection('useraccount')
        ->find(['loginusers' => $loginuser]);

      foreach ($result as $document) {
        $documents[] = $document;
      }

      return $documents;
    } else {

      static::firstSeederGeneration();

      return false;
    }
  }

 /**
   * Request to select all users of database (For Redis db)
   * 
   * @param string $loginuser
   * @return array|bool
   */
  public function findNosqlRedisUsers(string $loginuser):array|bool
  {

    if ($this->ifKeyExist() === true) {

      return $this->key('useraccount')->search(['usersstat'])->param([1])->index($loginuser)->redisGet();

    } else {

      static::firstSeederGeneration();

      return false;
    }
  }  
}