<?php

namespace SilexApi;

use Doctrine\DBAL\Connection;

class PostDao
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    protected function getDb()
    {
        return $this->db;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM posts";
        $result = $this->getDb()->fetchAll($sql);

        $entities = array();
        foreach ( $result as $row ) {
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObjects($row);
        }

        return $entities;
    }

    public function findAllByAuthor($author)
    {
        $sql = "SELECT * FROM posts WHERE author=?";
        $result = $this->getDb()->fetchAll($sql, array($author));

        $entities = array();
        foreach ( $result as $row ) {
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObjects($row);
        }

        return $entities;
    }

    public function find($id)
    {
        $sql = "SELECT * FROM posts WHERE id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row) {
            return $this->buildDomainObjects($row);
        } else {
            throw new \Exception("No user matching id ".$id);
        }
    }

    public function save(Post $post)
    {
        $postData = array(
            'content' => $post->getContent(),
            'date' => $post->getDate(),
            'author' => $post->getAuthor()
        );

        if ($post->getId()) {
            $this->getDb()->update('posts', $postData, array('id' => $post->getId()));
        } else {
            $this->getDb()->insert('posts', $postData);
            $id = $this->getDb()->lastInsertId();
            $post->setId($id);
        }
    }

    public function truncate()
    {
        $this->getDb()->executeQuery("TRUNCATE TABLE posts");
    }

    protected function buildDomainObjects($row)
    {
        $post = new Post();
        $post->setId($row['id']);
        $post->setContent($row['content']);
        $post->setDate($row['date']);
        $post->setAuthor($row['author']);

        return $post;
    }
}