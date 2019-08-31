<?php

namespace repository;

use database\Connections;
use database\SimpleDAO;

class LoginDAO extends SimpleDAO {

    public function __construct() {
        parent::__construct(new Login());
    }

    public function findSystemAccountByEmailForLogin( $email ) {
        $query = "
            select l.*, r.name as role,s.id as user_id
                from login l
                inner join roles r on r.id = l.role_id
                inner join users s on s.login_id = l.id
                where l.username = :email limit 1 ";

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);
        $statement->bindParam(":email", $email);
        $statement->execute();

        return $statement->fetch();
    }

    public function setFailedPaymentById( $login_id ) {
        $status = Login::SUSPENDIDA;

        $query = "update login set status = :status where id = :login_id";

        $statement = Connections::getConnection()->prepare($query);
        $statement->bindParam(":login_id", $login_id);
        $statement->bindParam(":status", $status);
        $statement->execute();
    }


    public function setPayedPaymentById( $login_id ) {
        $status = Login::ACTIVA;

        $query = "update login set status = :status where id = :login_id";

        $statement = Connections::getConnection()->prepare($query);
        $statement->bindParam(":login_id", $login_id);
        $statement->bindParam(":status", $status);
        $statement->execute();
    }

    public function findSystemAccountByEmail( $email ) {
       $status= Login::ACTIVA;

       $query = "select * from login where username = :email and status = :status";

       $statement = Connections::getConnection()->prepare($query);
       $statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);
       $statement->bindParam(":status", $status);
       $statement->bindParam(":email", $email);
       $statement->execute();

       return $statement->fetch();
    }

    public function updatePasswordById(Login $user)
    {
        $sql = "update login  set password=:password where id=:id";
        $password_new=$user->getPassword();
        $id_login=$user->getId();
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->bindParam(":password", $password_new);
        $stmt->bindParam(":id",$id_login );
        $stmt->execute();
    }

    public function updateEmailById($id, $email)
    {
        $sql = "update login  set username=:email where id=:id";
        $id_login=$id;
        $email_login=$email;
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->bindParam(":email", $email_login);
        $stmt->bindParam(":id", $id_login);
        $stmt->execute();
    }

    public function getDataEmailById($id)
    {
        $sql = "Select email from login where id=:id";
        $id_login=$id;
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->bindParam(":id", $id_login);
        $stmt->execute();
        return $stmt->fetchAll();
    }


}