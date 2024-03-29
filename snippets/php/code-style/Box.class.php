<?php
namespace wcf\data\box;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

class Box extends DatabaseObject {
    /**
     * Returns the box with the given identifier.
     *
     * @param	string		$identifier
     * @return	Box|null
     */
    public static function getBoxByIdentifier($identifier) {
        $sql = "SELECT  *
                FROM    wcf1_box
                WHERE   identifier = ?";
        $statement = WCF::getDB()->prepare($sql);
        $statement->execute([$identifier]);

        return $statement->fetchObject(self::class);
    }
}
