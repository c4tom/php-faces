methods save insert or update an object to database

    EntityManager::save(Entity $e)

usage

    $blog = new Blog();
    $blog->name="Hello world";
    $blog->content="xxxx";
    EntityManager::getInstance()->save($blog);

delete an object to database

    EntityManager::delete(Entity $e)

usage

    $em = EntityManager::getInstance();
    $blog = $em->find("Blog",2);
    $em->delete($blog);

find EntityManager::find(string cllassname,integer premarykey)

``` 

$entity= EntityManager::getInstance()->find("Entity",id);
```

createQuery parameter \<a
href="http://code.google.com/p/php-faces/wiki/FQL"\> FQL\</a\>

    $em=  EntityManager::getInstance();
    $query =  $em->createQuery("select b from Bir b where b.id =:id");
    $id=11;
    $query->bindParam("id", $id);
    $query->execute();
    print_r($query->getResultList())

nativeQuery parameter SQL

    $em=  EntityManager::getInstance();
    $query =  $em->createQuery("select * from bir");
