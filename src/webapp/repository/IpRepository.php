<?php
namespace tdt4237\webapp\repository;
use PDO;
use tdt4237\webapp\models\Phone;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\NullUser;
use tdt4237\webapp\models\User;
class IpRepository
{
    const INSERT_QUERY   = "INSERT INTO ip(session_id, ip) VALUES('%s', '%s')";
    const FIND_BY_SESSID   = "SELECT * FROM ip WHERE session_id=?";
    const DELETE_BY_SESSID = "DELETE FROM ip WHERE session_id=?";

    /**
     * @var PDO
     */
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getIpBySessid($sessid)
    {
        $query = sprintf(self::FIND_BY_SESSID, $sessid);
        $result = $this->pdo->query($query, PDO::FETCH_ASSOC);
        $row = $result->fetch();
        $ip = $row['ip'];
        return $ip;
    }

    public function deleteBySessid($sessid)
    {
        return $this->pdo->exec(
            sprintf(self::DELETE_BY_SESSID, $sessid)
        );
    }

    public function saveNewIp($sessid, $ip)
    {
        $query = sprintf(
            self::INSERT_QUERY, $sessid, $ip
        );
        return $this->pdo->exec($query);
    }


}