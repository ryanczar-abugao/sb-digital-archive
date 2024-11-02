<?php

namespace Constants;

class CssConstants
{ 
// Fontawesome Icon
    private string $icon = "fas fa-fw "; 

// Color Background
    private string $colorPrimary = "slate-800";  
    private string $colorSecondary = "amber-500";
    private string $colorAccent = "purple-500";   
    private string $colorBg = "gray-50";          
    private string $colorLight = "gray-200";          
    private string $colorWhite = "gray-100";      

// Color Background Hover
    private string $colorPrimaryHover = "slate-900";    
    private string $colorSecondaryHover = "amber-400";   
    private string $colorAccentHover = "purple-600";     

// Color Text
    private string $colorTextPrimary = "gray-900";   
    private string $colorTextSecondary = "gray-700"; 
    private string $colorTextWhite = "gray-200";     
    private string $colorTextDark = "gray-800";     

// Properties to hold generated class names
    public string $textPrimary; 
    public string $textSecondary; 
    public string $textWhite; 
    public string $textDark; 
    public string $bgPrimary; 
    public string $bgSecondary; 
    public string $bgAccent; 
    public string $bgWhite; 
    public string $bgLight; 
    public string $bg; 
    public string $bgPrimaryHover; 
    public string $bgSecondaryHover; 
    public string $bgAccentHover; 

// Layout styles
    public string $separator;
    public string $navActive;

    public string $header;
    public string $headerNavBtn;

    public string $heroSection;
    public string $heroSectionTitle;
    public string $heroSectionDescription;
    public string $heroSectionCta;

    public string $featureSection;
    public string $featureSectionTitle;
    public string $featureSectionCards;
    public string $featureSectionCard;
    public string $featureSectionCardTitle;
    public string $featureSectionCardDescription;
    
    public string $footer;

    public string $loginForm;

// Sidebar styles
    public string $sidebar;
    public string $sidebarNavs;
    public string $sidebarNav;

// Dashboard icon properties
    public string $dashboardIconSize;
    public string $membersIcon;
    public string $historyIcon;
    public string $ordinanceIcon;

// Background images
    public string $sbOfficeBg = "background-image: url(/assets/uploads/sbOffice.jpg); background-size: cover;";
    public string $libraryBg = "background-image: url(/assets/uploads/libraryBg.jpg); background-size: cover;";

// Tiles styles
    public string $tiles;
    public string $tilesTitle;
    public string $tilesDescription;
    public string $tilesSm;
    public string $tilesTitleSm;

// Card styles
    public string $card;
    public string $cardImage;
    public string $cardTitle;
    public string $cardSubTitle;
    public string $cardDescription;

// Form styles
    public string $formPopover;
    public string $formPopoverHeader;
    public string $formPopoverBtnContainer;

// Button styles
    public string $loginBtn;
    public string $formPopoverBtn;
    public string $formPopoverCloseBtn;
    public string $tilesBtn;
    public string $tilesBtnSm;
    public string $cardPrimaryBtn;
    public string $cardSecondaryBtn;
    public string $editBtn;
    public string $deleteBtn;
    public string $saveBtn;
    public string $cancelBtn;

// Input styles
    public string $inputText;
    public string $inputFileImage;
    public string $inputFilePdf;

// Table styles
    public string $tableContainer;
    public string $table;
    public string $tableHeader;
    public string $tableRows;
    public string $tableRowData;

// Icons container styles
    public string $addContainer;
    public string $actionContainer;

// Icons
    public string $editIcon;
    public string $deleteIcon;
    public string $imageIcon;
    public string $saveIcon;
    public string $addIcon;
    public string $uploadIcon;
    public string $filePdfIcon;
    public string $downloadIcon;
    public string $peopleRoofIcon;
    public string $clockRotateLeftIcon;
    public string $scrollIcon;
    public string $xmarkIcon;
    public string $gauge;
    public string $rightFromBracket;

// Constructor to initialize styles
    public function __construct()
    {
    // Colors
        $this->textPrimary = "text-{$this->colorTextPrimary}";
        $this->textSecondary = "text-{$this->colorTextSecondary}";
        $this->textWhite = "text-{$this->colorTextWhite}";
        $this->textDark = "text-{$this->colorTextDark}";
        $this->bgPrimary = "bg-{$this->colorPrimary}";
        $this->bgSecondary = "bg-{$this->colorSecondary}";
        $this->bgAccent = "bg-{$this->colorAccent}";
        $this->bgWhite = "bg-{$this->colorWhite}";
        $this->bgLight = "bg-{$this->colorLight}";
        $this->bg = "bg-{$this->colorBg}";

    // Color hover
        $this->bgPrimaryHover = "bg-{$this->colorPrimaryHover}";
        $this->bgSecondaryHover = "bg-{$this->colorSecondaryHover}";
        $this->bgAccentHover = "bg-{$this->colorAccentHover}";

    // Layout styles
        $this->separator = "mt-3 mb-5 border border-1 border-gray-200";
        $this->navActive = "{$this->bgSecondary} {$this->textDark}";

        $this->header = "{$this->bgPrimary} {$this->textWhite} p-4 sticky top-0 shadow-lg z-50";
        $this->headerNavBtn = "{$this->textWhite} hover:{$this->bgSecondaryHover} hover:{$this->textDark} p-2 rounded w-full";

        // Home Hero styles
        $this->heroSection = "pt-8 pb-12 px-8 {$this->textWhite} bg-backdrop-blur-sm bg-black/70 h-[calc(100vh-60px)] flex flex-col items-start justify-center";
        $this->heroSectionTitle = "text-4xl md:text-5xl font-bold";
        $this->heroSectionDescription = "text-lg mt-3";
        $this->heroSectionCta = "{$this->bgAccent} {$this->textWhite} hover:{$this->bgAccentHover} py-3 px-6 rounded-lg font-semibold text-xl mt-3";

        // Home Feature styles
        $this->featureSection = "pt-8 pb-12 px-8 ";
        $this->featureSectionTitle = "text-3xl font-bold mb-12";
        $this->featureSectionCards = "grid grid-cols-1 md:grid-cols-3 gap-8";
        $this->featureSectionCard = "{$this->bgWhite} {$this->textPrimary} border border-{$this->colorPrimary} p-6 rounded-lg shadow-lg";
        $this->featureSectionCardTitle = "text-2xl font-semibold mb-4";
        $this->featureSectionCardDescription = "text-lg";
        
        $this->footer = "{$this->bgPrimary} {$this->textWhite} p-4";

        $this->loginForm = "p-4 w-full rounded-lg border border-{$this->colorPrimary} shadow-lg mt-5";

    // Sidebar styles
        $this->sidebar = "{$this->bgPrimaryHover} {$this->textWhite} min-w-[250px]";
        $this->sidebarNavs = "p-4 py-6 flex flex-col";
        $this->sidebarNav = "flex";

    // Tiles styles        
        $this->tiles = "{$this->bgWhite} {$this->textPrimary} border border-{$this->colorPrimary} p-6 rounded-lg";
        $this->tilesTitle = "{$this->textPrimary} text-xl font-semibold";
        $this->tilesDescription = "{$this->textPrimary} mt-2 mb-4";
        $this->tilesSm = "{$this->bgPrimary} {$this->textWhite} p-4 rounded-xl shadow hover:shadow-lg hover:{$this->bgPrimaryHover}";
        $this->tilesTitleSm = "text-sm font-semibold text-center mt-2";

    // Card styles
        $this->card = "{$this->bgWhite} border border-{$this->colorPrimary} shadow-md rounded p-4 py-6";
        $this->cardImage = "bg-contain w-[40px] md:w-[50px] h-[40px] md:h-[50px] rounded";
        $this->cardTitle = "{$this->textPrimary} font-bold";
        $this->cardSubTitle = "{$this->textSecondary} mb-3";
        $this->cardDescription = "{$this->textSecondary}";

    // Form styles
        $this->formPopover = "w-full h-screen backdrop-blur-md backdrop-blur-sm bg-white/100 p-0";
        $this->formPopoverHeader = "{$this->bgPrimary} {$this->textWhite} sticky top-0 z-50 flex items-center justify-between p-4 mb-3 shadow-lg";
        $this->formPopoverBtnContainer = "sticky bottom-0 flex flex-col gap-1 pb-3 justify-end items-start";

    // Button styles
        $this->loginBtn = "{$this->bgPrimary} {$this->textWhite} font-bold p-3 rounded-lg text-md px-6";
        $this->formPopoverBtn = "{$this->bgPrimary} hover:{$this->bgPrimaryHover} {$this->textWhite} p-2 rounded-lg shadow-sm mb-3";
        $this->formPopoverCloseBtn = "{$this->bgSecondary} hover:{$this->bgSecondaryHover} {$this->textDark} p-2 rounded-lg shadow-sm";
        $this->tilesBtn = "p-2 {$this->bgPrimary} hover:{$this->bgPrimaryHover} {$this->textWhite}  transition duration-300 ease-in rounded-lg shadow-sm";
        $this->tilesBtnSm = "{$this->bgPrimary} {$this->textWhite} p-2 rounded shadow-sm";
        $this->cardPrimaryBtn = "{$this->bgPrimary} hover:{$this->bgPrimaryHover} {$this->textWhite} p-2 rounded-lg shadow-sm";
        $this->cardSecondaryBtn = "{$this->bgSecondary} hover:{$this->bgSecondaryHover} {$this->textWhite} p-2 rounded-lg shadow-sm";
        $this->editBtn = "{$this->bgPrimary} hover:{$this->bgPrimaryHover} {$this->textWhite} rounded-lg p-[0.4rem] grid items-center grid-cols-3 w-[70px] shadow-sm";
        $this->deleteBtn = "{$this->bgSecondary} hover:{$this->bgSecondaryHover} {$this->textDark} rounded-lg p-[0.4rem] grid items-center grid-cols-3 w-[70px] shadow-sm";
        $this->saveBtn = "{$this->bgPrimary} hover:{$this->bgPrimaryHover} {$this->textWhite} p-3 rounded-xl text-lg shadow-lg";
        $this->cancelBtn = "{$this->bgSecondary} hover:{$this->bgSecondaryHover} {$this->textDark} p-2 rounded-lg";

    // Input styles
        $this->inputText = "{$this->bgLight} {$this->textPrimary} mt-1 block w-full rounded-md border-transparent focus:border-{$this->colorPrimary} focus:bg-white focus:ring-0";
        $this->inputFileImage = "{$this->bgPrimary} {$this->textWhite} px-4 py-2 rounded cursor-pointer hover:{$this->bgPrimaryHover}";
        $this->inputFilePdf = $this->inputFileImage;

    // Table styles
        $this->tableContainer = "overflow-x-auto shadow my-3";
        $this->table = "min-w-full bg-white border border-gray-200 rounded-lg shadow-md table-fixed text-sm md:text-base";
        $this->tableHeader = "border-b border-gray-300 p-3 text-left";
        $this->tableRows = "hover:bg-gray-100 transition duration-300 ease-in-out";
        $this->tableRowData = "border-b border-gray-300 p-3";

    // Icons container styles                       
        $this->addContainer = "{$this->bgPrimary} rounded p-2";
        $this->actionContainer = "flex flex-col gap-1 items-start justify-start w-full text-xs";

    // Icon classes
        $this->editIcon = $this->icon . "fa-edit";
        $this->deleteIcon = $this->icon . "fa-trash";
        $this->imageIcon = $this->icon . "fa-camera";
        $this->saveIcon = $this->icon . "fa-save";
        $this->addIcon = $this->icon . "fa-plus";
        $this->uploadIcon = $this->icon . "fa-upload";
        $this->filePdfIcon = $this->icon . "fa-file-pdf";
        $this->downloadIcon = $this->icon . "fa-download";
        $this->peopleRoofIcon = $this->icon . "fa-people-roof";
        $this->clockRotateLeftIcon = $this->icon . "fa-clock-rotate-left";
        $this->scrollIcon = $this->icon . "fa-scroll";
        $this->xmarkIcon = $this->icon . "fa-xmark";
        $this->gauge = $this->icon . "fa-gauge";
        $this->rightFromBracket = $this->icon . "fa-right-from-bracket";

    // Dashboard icon properties    
        $this->dashboardIconSize = "fa-5x";
        $this->membersIcon = $this->peopleRoofIcon . " " . $this->dashboardIconSize . " w-full";
        $this->historyIcon = $this->clockRotateLeftIcon . " " . $this->dashboardIconSize . " w-full";
        $this->ordinanceIcon = $this->scrollIcon . " " . $this->dashboardIconSize . " w-full";
    }
}
