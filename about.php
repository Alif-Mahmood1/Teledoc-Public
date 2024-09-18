<?php
session_start();
require_once('./connection.php');

// 3 OOP concepts here: polymorphism, abstraction and inheritance
class Page {
    protected $title;
    protected $content;

    public function __construct($title, $content) {
        $this->title = $title;
        $this->content = $content;
    }

    public function displayHeader() {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$this->title}</title>
            <link rel='stylesheet' href='css/design.css'>
            <link rel='stylesheet' href='css/about.css'>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body>
            <?php include 'navbar.php'; ?>";
    }

    public function displayContent() {
        echo "<div class='container'>
                <h1>{$this->title}</h1>
                {$this->content}
              </div>";
    }

    public function displayFooter() {
        echo "
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script>
            <script src='scripts/script.js'></script>
        </body>
        </html>";
    }

    // Composite display method
    public function display() {
        $this->displayHeader();
        $this->displayContent();
        $this->displayFooter();
    }
}

//  FAQ section
class AboutPage extends Page {
    private $faq;

    public function __construct($title, $content, $faq) {
        parent::__construct($title, $content);
        $this->faq = $faq;
    }

    public function displayContent() {
        $faqHTML = "<div class='accordion' id='faqAccordion'>";
        foreach ($this->faq as $question => $answer) {
            $id = preg_replace('/\W+/', '', strtolower($question));  // Creating a simple ID from the question
            $faqHTML .= "
            <div class='accordion-item'>
                <h2 class='accordion-header' id='heading{$id}'>
                    <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse{$id}' aria-expanded='false' aria-controls='collapse{$id}'>
                        {$question}
                    </button>
                </h2>
                <div id='collapse{$id}' class='accordion-collapse collapse' aria-labelledby='heading{$id}' data-bs-parent='#faqAccordion'>
                    <div class='accordion-body'>
                        {$answer}
                    </div>
                </div>
            </div>
            ";
        }
        $faqHTML .= "</div>";
        $this->content .= $faqHTML;
        parent::displayContent();
    }
}

$faq = [
    "What services does Teledoc offer?" => "Teledoc offers online appointment booking with doctors for various medical services.",
    "How do I book an appointment?" => "You can easily book an appointment by logging into your account, selecting a doctor, and choosing an available time slot.",
    "Is Teledoc available 24/7?" => "Yes, Teledoc is available for appointment bookings 24 hours a day, 7 days a week.",
    "Are the doctors qualified?" => "Yes, all doctors on Teledoc are qualified healthcare professionals with appropriate certifications.",
    "Is my information secure?" => "Yes, Teledoc takes privacy and security seriously. Your personal and medical information is encrypted and stored securely."
];

$page = new AboutPage("About Teledoc", "<p>Teledoc is a website where patients can book appointments with doctors.</p>", $faq);
$page->display();
?>
