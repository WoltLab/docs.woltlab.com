CREATE TABLE wcf1_foo_bar (
    fooID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    packageID INT(10) NOT NULL,
    bar VARCHAR(255) NOT NULL DEFAULT '',
    foobar VARCHAR(50) NOT NULL DEFAULT '',

    UNIQUE KEY baz (bar, foobar)
);

ALTER TABLE wcf1_foo_bar ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;