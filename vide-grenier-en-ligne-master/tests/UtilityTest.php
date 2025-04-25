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
 *                    -> fournit 0, retourne une erreur
 *                    -> fournit un input de 1000 ou plus, ne plante pas et retourne une chaine de 1000
 *
 * 3 - generateUnique() (Token) -> retourne un hachage via generate()
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

}

