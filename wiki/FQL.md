FQL(Faces Query Language)

select statement

    SELECT alias name FROM class name alias name where havig etc..

Samples

    SELECT b from Blog b

    SELECT c from Categories c

    SELECT b from Blog b WHERE bi.id = 1

    SELECT b from Blog b WHERE bi.id = 1 and b.name='www'

    SELECT b from Blog b limit 0,5

    SELECT c from Categories c  GROUP BY c.id HAVING AVG(c.id) > 1

Usege with entitymanager

``` 
 $query= $this->em->createQuery("SELECT b from Blog b WHERE b.comment.id >1");
 $query->execute();
 $blogs = $query->getResultList();
```

The SQL functions

    SELECT COUNT(b.id) FROM Blog b

    SELECT MAX(b.id) FROM Blog b

Usege with entitymanager

``` 
 $query= $this->em->createQuery("SELECT count(b.id) from bir b");
 $query->execute();
 echo "count =" .$query->getSingle()."<br>";
```

\<b\>FQL Sub Queryes\</b\>

    SELECT b FROM Blog b WHERE b.coment.id =(SELECT MAX(c.id) FROM  Comment c)

    SELECT o FROM Object o WHERE o.id = (SELECT  AVG(i.id) FROM Object i) OERDER BY o.name
