<?php
namespace App\models;

class Database
{
    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_host;
    private $pdo;
    /**
     * __construct assign values to attributes
     *
     * @param string $db_name the data base name
     * @param string $db_user the database username
     * @param string $db_pass the database password
     * @param string $db_host the host
     *
     * @return void
     */
    public function __construct(
        $db_name,
        $db_user = 'root',
        $db_pass = '',
        $db_host = 'localhost'
    ) {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }
    /**
     * The getPDO allow to get the PDO and initialize it only one time
     *
     * @return objet the connection object of data base
     */
    protected function getPDO()
    {
        if ($this->pdo === null) {
            $pdo = new \PDO('mysql:host=localhost;dbname=myblog', 'root', '');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }
    /**
     * Query recive a request data
     *
     * @param mixed $statement the request wanted by the user
     *
     * @return array obejects requested
     */
    public function query($statement)
    {
        
        $req=$this->getPDO()->query($statement);
        $data = $req->fetchAll(\PDO::FETCH_OBJ);
        return $data;
    }
}
