<?php

namespace Post\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

use Post\Model\Post;
use Core\Db;

class PostTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function ifPostExist($email)
    {
        $adapter = $this->tableGateway->getAdapter();

        $sql = "SELECT * FROM Post WHERE Email = '" .$email ."'";

        $statement = $adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $rowset = $statement->execute();

        $row = $rowset->count();

        return ($row > 0) ? true : false;
    }

    public function getPostByEmail($email)
    {
        $rowset = $this->tableGateway->select(array('Email' => $email));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $token");
        }
        return $row;
    }

    public function fetchPost(Array $params)
    {
        $adapter = $this->tableGateway->getAdapter();

        $qi = function($name) use ($adapter) { return $adapter->platform->quoteIdentifier($name); };
        $fp = function($name) use ($adapter) { return $adapter->driver->formatParameterName($name); };

        $sql = 'SELECT ' . $qi('Post')
        . '.* FROM ' . $qi('Post')
        . ' WHERE ' . $qi('Password') . ' = ' . $fp('password')
        . ' AND ' . $qi('Email') . ' = ' . $fp('email');

        $parameters = array(
            'password' => $params['password'],
            'email' => $params['email']
        );

        $statement = $adapter->query($sql);
        $rowset = $statement->execute($parameters);

        $row = $rowset->current();

        return $row;
    }

    public function insert(Array $params)
    {
        $this->tableGateway->insert($params);
        $id = $this->tableGateway->lastInsertValue;

        return $id;
    }

    public function update(Array $parameters)
    {
        $adapter = $this->tableGateway->getAdapter();

        $statement = $adapter->query(Db::UPDATE_USER_PASSWORD);
        $rowset = $statement->execute($parameters);

        $count = $rowset->count();

        if ($count)
        {
            $result['status'] = true;
            // $result['id'] = $id;
        } 
        else 
        {
            $result['status'] = false;
        }

        return $result;
    }

    public function updateProfile(Array $parameters)
    {
        $adapter = $this->tableGateway->getAdapter();

        $statement = $adapter->query(Db::UPDATE_USER_BY_EMAIL);
        $rowset = $statement->execute($parameters);

        $count = $rowset->count();

        return $count;
    }
    
    public function deltafetch($params)
    {
        $parameters = array(
            'timestamp' => $params->timestamp,
            'postId' => $params->postId,
        );
        
        $adapter = $this->tableGateway->getAdapter();

        $statement = $adapter->query(Db::DELTA_FETCH_USER);
        $rowset = $statement->execute($parameters);

        $rows = array();

        foreach($rowset as $row) 
        { 
            $rows[] = $row;
        }

        return $rows;
    }
}

