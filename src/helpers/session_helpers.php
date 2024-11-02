<?php

namespace Helpers;

class SessionHelpers
{
  public function __construct()
  {
  }

  public function verifyLoggedUser() {
    if (!isset($_SESSION['userId'])) {
        header('Location: /admin/login');
        exit;
    }
  }
}
