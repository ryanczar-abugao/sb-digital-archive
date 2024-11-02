<?php

namespace Controller;

use Constants\CssConstants;
use Helpers\SessionHelpers;

class DashboardController
{
    private $twig;
    private $cssConstants;
    private $sessionHelper;

    public function __construct($twig)
    {
        $this->cssConstants = new CssConstants();
        $this->sessionHelper = new SessionHelpers();
        $this->twig = $twig;
    }

    public function showDashboard()
    {
        session_start();
        
        $this->sessionHelper->verifyLoggedUser();

        echo $this->twig->render('admin/dashboard.twig', [
            'css' => $this->cssConstants,
            'isLoggedIn' => isset($_SESSION['userId']), 
            'currentPage' => 'dashboard'
        ]);
    }
}
