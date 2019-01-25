<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/User.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Config.php';

class Guard extends User
{
    private static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function StaffRequired()
    {
        if (!$this->isStaff()) {
            echo '<h1 style="padding-left: 80px;padding-top: 20px;">Unauthorised! Redirecting...</h1><meta http-equiv="refresh" content="0;url=/errors/nostaff">';
            die();
        }
    }

    public function SLTRequired()
    {
        if (!$this->isSLT() || !$this->isStaff()) {
            echo '<h1 style="padding-left: 80px;padding-top: 20px;">Unauthorised! Redirecting...</h1><meta http-equiv="refresh" content="0;url=/">';
            die();
        }
    }

    public function RequireGameAccess()
    {
        if (!$this->hasGameReadAccess()) {
            echo '<h1>Unauthorised! Redirecting...</h1><meta http-equiv="refresh" content="0;url=/">';
            die();
        }
        if (!Config::$enableGamePanel) {
            echo '<div style="padding-left: 70px;padding-top: 10px;">Game Panel Disabled, Contact Your Systems Administrator.</div>';
            die();
        }
    }

    public function LoginRequired()
    {
        if (!$this->verified(false) && ($this->isCommand() || $this->isPD() || $this->isEMS() || $this->isStaff())) {
            echo '<h1>Unauthorised! Redirecting...</h1><meta http-equiv="refresh" content="0;url=/">';
            die();
        }
    }

    public function DevRequired()
    {
        if ($this->info->Developer || $this->info->id == 209) return true;
        return false;
    }
}