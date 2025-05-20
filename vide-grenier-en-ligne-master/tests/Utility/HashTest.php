<?php
declare(strict_types=1);

namespace Tests\Utility;

use App\Utility\Hash;
use PHPUnit\Framework\TestCase;

/**
 * Hash Utility Tests
 * 
 * Tests for the Hash utility class functionality
 */
class HashTest extends TestCase
{
    /**
     * Test that Generate returns the correct hash length
     */
    public function testGenerateReturnsRightHashLength() {
        $result = Hash::Generate("abc");
        $this->assertEquals(64, strlen($result));
    }

    /**
     * Test that Generate returns consistent hash with the same salt
     */
    public function testGenerateReturnsHashAndSalt(){
        $hash = "motdepasse";
        $salt = "salt";
        $expectedHash = Hash::Generate($hash, $salt);
        $result = Hash::Generate($hash, $salt);

        $this->assertEquals($expectedHash, $result);
    }

    /**
     * Test that Generate returns different hashes with different salts
     */
    public function testGenerateReturnsDifferentHashes()
    {
        $result = Hash::Generate("abc", "salt1");
        $result2 = Hash::Generate("abc", "salt2");

        $this->assertNotEquals($result, $result2);
    }
    
    /**
     * Test that Generate returns error if parameter is empty
     */
    public function testGenerateReturnsErrorIfParameterIsEmpty() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Un string est nécessaire.");

        Hash::generate("");
    }

    /**
     * Test that GenerateSalt returns the correct salt length
     */
    public function testGenerateSaltReturnsRightSaltLength() {
        $result = Hash::GenerateSalt(22);
        $this->assertEquals(22, strlen($result));
    }

    /**
     * Test that GenerateSalt returns error if parameter is empty
     */
    public function testGenerateSaltReturnsErrorIfParameterIsEmpty() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("La longueur du salt doit être un entier positif.");

        Hash::generateSalt("");
    }

    /**
     * Test that GenerateSalt returns error if parameter is not a positive integer
     */
    public function testGenerateSaltReturnsErrorIfParameterIsNoInteger() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("La longueur du salt doit être un entier positif.");

        Hash::generateSalt(-10);
    }

    /**
     * Test that GenerateSalt can handle extra long salt lengths
     */
    public function testGenerateSaltReturnsExtraLongSaltLength() {
        $length = 1000;
        $expectedLength = Hash::GenerateSalt($length);

        $this->assertEquals(strlen($expectedLength), $length);
    }
}
