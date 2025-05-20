<?php
declare(strict_types=1);

namespace Tests\Utility;

use App\Utility\Flash;
use PHPUnit\Framework\TestCase;

/**
 * Flash Utility Tests
 * 
 * Tests for the Flash utility class functionality
 */
class FlashTest extends TestCase
{
    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        // Initialize session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear any existing session data
        $_SESSION = [];
    }
    
    /**
     * Clean up after tests
     */
    protected function tearDown(): void
    {
        // Clean up session data
        $_SESSION = [];
    }
    
    /**
     * Test that danger() stores message in session
     */
    public function testDangerStoreMessageInSession() {
        $message = "Danger, Une erreur s'est produite.";

        Flash::danger($message);

        $this->assertEquals($message, $_SESSION['flash_error']);
    }

    /**
     * Test that getError() returns message from session
     */
    public function testGetErrorReturnsMessageInSession() {
        $message = "Une erreur s'est produite.";
        $_SESSION['flash_error'] = $message;

        $result = Flash::getError();
        $this->assertEquals($message, $result);
    }

    /**
     * Test that getError() deletes message from session after retrieving it
     */
    public function testGetErrorDeletesMessageInSession() {
        $message = "Une erreur s'est produite.";
        $_SESSION['flash_error'] = $message;

        Flash::getError();

        $this->assertArrayNotHasKey('flash_error', $_SESSION);
    }
}
