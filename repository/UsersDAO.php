<?php

namespace repository;

use util\roles\RolesPolicy;

use database\Connections;
use database\SimpleDAO;

class UsersDAO extends SimpleDAO {

    public function __construct() {
        parent::__construct(new Users());
    }

    public function findByEmail( $email ) {
        $query = '
            select
                u.*
            from users u
                inner join login l on l.id = u.login_id
                    and l.email = :email
        ';

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);
        $statement->bindParam(":email", $email);
        $statement->execute();

        return $statement->fetch();
    }

    public function findInstructorByCourseId( $course_id ) {
        $query = '
            select
                u.*
            from users u
                inner join courses c on c.id = :course_id
            where u.login_id = c.login_id
        ';

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);
        $statement->bindParam(":course_id", $course_id);
        $statement->execute();

        return $statement->fetch();
    }

    public function findAdmins() {
        $role_id = RolesPolicy::ROLE_ADMINISTRADOR_ID;
        
        $query = '
            select
                u.*
            from users u
                inner join login l on l.id = u.login_id and l.role_id = :role_id
        ';

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":role_id", $role_id);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findMemberByCourseIdAndMedalIdAndUserId( $course_id, $medal_id, $user_id ) {
        $role_id = RolesPolicy::ROLE_ALUMNO_ID;

        $query = '
            select
                l.email,
                u.id as user_id,
                l.id as login_id,
                concat_ws(" ", u.name, u.last_name) as user
            from users u
                inner join login l on l.id = u.login_id and l.role_id = :role_id
                inner join memberships_user mu on mu.user_id = u.id
                inner join memberships m on m.id = mu.membership_id
                    and m.course_id = :course_id
                left join medals_user meu on meu.user_id = u.id
                    and meu.medal_id = :medal_id
            where meu.medal_id is null and u.id = :user_id
            group by u.id
        ';

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":course_id", $course_id);
        $statement->bindParam(":medal_id", $medal_id);
        $statement->bindParam(":user_id", $user_id);
        $statement->bindParam(":role_id", $role_id);
        $statement->execute();

        return $statement->fetch();
    }

    public function findAllMembersByCourseIdAndMedalId( $course_id, $medal_id ) {
        $role_id = RolesPolicy::ROLE_ALUMNO_ID;

        $query = '
            select
                l.email,
                u.id as user_id,
                l.id as login_id,
                concat_ws(" ", u.name, u.last_name) as user
            from users u
                inner join login l on l.id = u.login_id and l.role_id = :role_id
                inner join memberships_user mu on mu.user_id = u.id
                inner join memberships m on m.id = mu.membership_id
                    and m.course_id = :course_id
                left join medals_user meu on meu.user_id = u.id
                    and meu.medal_id = :medal_id
            where meu.medal_id is null
            group by u.id
        ';

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":course_id", $course_id);
        $statement->bindParam(":medal_id", $medal_id);
        $statement->bindParam(":role_id", $role_id);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findAllExtendedByRoleId( $role_id ) {
        $activa = Login::ACTIVA;
        $cancelada = Login::CANCELADA;
        $bloqueada = Login::BLOQUEADA;
        $eliminada = Login::ELIMINADA;
        $suspendida = Login::SUSPENDIDA;
        $pago_pendiente = Login::PAGO_PENDIENTE;
        $google = Login::GOOGLE_ACCOUNT;
        $system = Login::SYSTEM_ACCOUNT;
        $facebook = Login::FACEBOOK_ACCOUNT;

        $query = sprintf('
            select
                u.name,
                u.last_name,
                u.id as user_id,
                concat_ws(" ", u.name, u.last_name) as user,
                l.id as login_id,
                l.created,
                l.email,
                l.status,
                l.account_type,
                case l.status
                    when :activa then "%s"
                    when :cancelada then "%s"
                    when :bloqueada then "%s"
                    when :eliminada then "%s"
                    when :suspendida then "%s"
                    when :pago_pendiente then "%s"
                end as status_text,
                case l.account_type
                    when :google then "Google"
                    when :system then "Tocajazz"
                    when :facebook then "Facebook"
                end as account_type_text
            from users u
                inner join login l on l.id = u.login_id and l.role_id = :role_id
        ', _("Activa"), _("Cancelada"), _("Bloqueada"), _("Eliminada"),
        _("Suspendida"), _("Pago pendiente"));

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":pago_pendiente", $pago_pendiente);
        $statement->bindParam(":suspendida", $suspendida);
        $statement->bindParam(":eliminada", $eliminada);
        $statement->bindParam(":bloqueada", $bloqueada);
        $statement->bindParam(":cancelada", $cancelada);
        $statement->bindParam(":facebook", $facebook);
        $statement->bindParam(":role_id", $role_id);
        $statement->bindParam(":google", $google);
        $statement->bindParam(":system", $system);
        $statement->bindParam(":activa", $activa);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findAllByCourseId( $course_id ) {
        $query = '
            select
                mu.user_id
            from memberships_user mu
                inner join memberships m on m.id = mu.membership_id
                    and m.course_id = :course_id
            group by mu.user_id
        ';

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":course_id", $course_id);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function updateProfileUser(Users $users)
    {
        $name=$users->getName();
        $last_name=$users->getLastName();
        $social=$users->getAllowSocialMedia();
        $user_id=$users->getId();
        $sql = "update users set name=:names,last_name=:last_names,allow_social_media=:confirm_social where id=:id";
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->bindParam(":names", $name);
        $stmt->bindParam(":last_names", $last_name);
        $stmt->bindParam(":confirm_social", $social);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
    }

    public function updatePhotoProfileUser($photo, $id)
    {
        $photoUser=$photo;
        $idUser=$id;
        $sql = "UPDATE  users SET profile_picture=:photo WHERE id=:id";
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->bindParam(":photo", $photoUser);
        $stmt->bindParam(":id", $idUser);
        $stmt->execute();
    }
    public function UserGetById($id)
    {
        $id_user=$id;
        $sql = "SELECT login.email, users.* 
        FROM login inner JOIN users ON login.id = users.login_id where users.id =:id";
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->bindParam(":id", $id_user);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteUser($id)
    {
        $id_user=$id;
        $sql = "Delete from users WHERE id=:id";
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->bindParam(":id", $id_user);
        $stmt->execute();
    }

}