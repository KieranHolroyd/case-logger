<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';

class User
{
    public $info = [];
    public $neededFields = [];
    public $error = false;

    public function __construct($id = null)
    {
        global $pdo;
        if ($id) {
            $sql2 = "SELECT * FROM users WHERE id = :id";
            $query2 = $pdo->prepare($sql2);
            $query2->bindValue(':id', $id, PDO::PARAM_STR);
            if ($query2->execute()) {
                $usr = $query2->fetch();
                if ($usr) {
                    $this->info = $usr;
                } else {
                    $this->error = true;
                }
            } else {
                $this->error = true;
            }
        } else {
            $logintoken = 'a';
            if (isset($_COOKIE['LOGINTOKEN'])) {
                $logintoken = $_COOKIE['LOGINTOKEN'];
            }
            $sql = "SELECT * FROM login_tokens WHERE token = :token";
            $query = $pdo->prepare($sql);
            $query->bindValue(':token', sha1($logintoken), PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetch();
                if ($result) {
                    $sql2 = "SELECT * FROM users WHERE id = :id";
                    $query2 = $pdo->prepare($sql2);
                    $query2->bindValue(':id', $result->user_id, PDO::PARAM_STR);
                    if ($query2->execute()) {
                        $usr = $query2->fetch();
                        if ($usr) {
                            $this->info = $usr;
                        } else {
                            $this->error = true;
                        }
                    } else {
                        $this->error = true;
                    }
                } else {
                    $this->error = true;
                }
            } else {
                $this->error = true;
            }
        }
    }

    public function isOnLOA()
    {
        if ($this->info->loa !== null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            if (new DateTime() < new DateTime($this->info->loa)) {
                return true;
            }
        }
        return false;
    }

    public function isSLT()
    {
        if (($this->info->SLT || $this->info->Developer) && !$this->error) {
            return true;
        }
        return false;
    }

    public function isStaff()
    {
        if ($this->verified() && $this->info->isStaff) {
            return true;
        }
        return false;
    }

    public function isCommand()
    {
        if ($this->verified(false) && $this->info->isCommand) {
            return true;
        }
        return false;
    }

    public function verified($old = true)
    {
        if (!$this->error) {
            if (!$old) {return true;} else {
                if ($this->info->sRep && $this->info->rank_lvl) return true;
            }
        }
        return false;
    }

    public function displayName()
    {
        return $this->info->first_name . ' ' . $this->info->last_name;
    }

    public function isSuspended()
    {
        if ($this->info->suspended) {
            return true;
        }
        return false;
    }

    public function hasGameReadAccess($level = 0)
    {
        if ($level == 0) {
            if ($this->info->rank_lvl <= 8 || $this->info->Developer || $this->isCommand()) {
                return true;
            }
        } else {
            if ($this->info->rank_lvl <= 7 || $this->info->Developer || $this->isCommand()) {
                return true;
            }
        }
        return false;
    }

    public function hasGameWriteAccess($comp = true)
    {
        if ($comp) {
            if ($this->info->rank_lvl <= 6 || $this->info->Developer) {
                return true;
            }
        } else {
            if ($this->info->rank_lvl <= 6 || $this->info->Developer || $this->isCommand()) {
                return true;
            }
        }

        return false;
    }

    public function needMoreInfo()
    {
        if ($this->info->region == null || $this->info->region == '') $this->neededFields[] = 'region';
        if ($this->info->steamid == null || $this->info->steamid == '') $this->neededFields[] = 'steamid';


        if ($this->neededFields) return true;
        return false;
    }

    public function isPD()
    {
        if ($this->info->isPD) return true;
        return false;
    }

    public function isEMS()
    {
        if ($this->info->isEMS) return true;
        return false;
    }

    public function getInfoForFrontend()
    {
        return [
            "isSLT" => $this->isSLT(),
            "isStaff" => $this->isStaff(),
            "isSuspended" => $this->isSuspended(),
            "isPD" => $this->isPD(),
            "isEMS" => $this->isEMS(),
            "isOnLOA" => $this->isOnLOA(),
            "id" => $this->info->id,
            "rank" => $this->info->rank,
            "rankLevel" => $this->info->rank_lvl,
            "firstName" => $this->info->first_name,
            "lastName" => $this->info->last_name,
            "displayName" => $this->displayName(),
            "username" => $this->info->username,
            "team" => $this->info->team,
        ];
    }
}