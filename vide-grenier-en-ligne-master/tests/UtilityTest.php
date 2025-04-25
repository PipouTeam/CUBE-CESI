<?php
declare(strict_types=1);

use App\Utility\Hash;
use PHPUnit\Framework\TestCase;
//require_once("../App/Utility/Hash.php");

/* Liste des tests à effectuer */

/***
 * 1- generate() -> fournit un string, la sortie est bien un hash SHA-256 (= 64 caractères)
 *               -> fournit 2 salts différents, retourne different hashes
 *               -> fournit un string et un salt, retourne un hashage avec salt
 *               -> fournit aucun string, retourne une erreur Flash
 *
 * 2 - generateSalt() -> fournit un int pour la longueur du salt, retourne un salt de la même longueur
 *                    -> fournit un champ vide, retourne une erreur
 *                    -> fournit un input de 1000 ou plus, ne plante pas et retourne une chaine de 1000
 *                    -> fournit un input non valide (non entier), retourne une erreur
 *
 * 3 - generateUnique() (Token) -> n'est pas utilisé
 */

final class UtilityTest extends TestCase
{
    /*** Test Generate() ***/

    public function testGenerateReturnsRightHashLength() {
        $result = Hash::Generate("abc");
        $this -> assertEquals(64, strlen($result));
    }

    public function testGenerateReturnsHashAndSalt(){
        $hash = "motdepasse";
        $salt ="salt";
        $expectedHash = Hash::Generate($hash, $salt);
        $result = Hash::Generate($hash, $salt);

        $this -> assertEquals($expectedHash, $result);
    }

    public function testGenerateReturnsDifferentHashes()
    {
        $result = Hash::Generate("abc", "salt1");
        $result2 = Hash::Generate("abc", "salt2");

        $this -> assertNotEquals($result, $result2);
    }
    public function testGenerateReturnsErrorIfParameterIsEmpty() {

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Un string est nécessaire.");

        Hash::generate("");
    }

    /*** Test GenerateSalt() ***/

    public function testGenerateSaltReturnsRightSaltLength() {
        $result = Hash::GenerateSalt(22);
        $this -> assertEquals(22, strlen($result));
    }

    public function testGenerateSaltReturnsErrorIfParameterIsEmpty() {

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("La longueur du salt doit être un entier positif.");

        Hash::generateSalt("");
    }

    public function testGenerateSaltReturnsErrorIfParameterIsNoInteger() {

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("La longueur du salt doit être un entier positif.");

        Hash::generateSalt(-10);
    }

    public function testGenerateSaltReturnsExtraLongSaltLength() {
        $length = 1000;
        $expectedLength = Hash::GenerateSalt($length);

        $this -> assertEquals(strlen($expectedLength), $length);
    }

}

