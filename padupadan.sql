CREATE TABLE Pengguna (
    idPengguna INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    profilepicture VARCHAR(255),
    password VARCHAR(255),
    gender VARCHAR(10) CHECK (gender IN ('Male', 'Female')),
    bodytype VARCHAR(50),
    skintone VARCHAR(50),
    style VARCHAR(100),
    preferences TEXT
);

CREATE TABLE Stylist (
    idStylist INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255) DEFAULT '123',
    profilepicture VARCHAR(255),
    speciality VARCHAR(100),
    job VARCHAR(100),
    location VARCHAR(255),
    gender VARCHAR(10) CHECK (gender IN ('Male', 'Female'))
);

CREATE TABLE KoleksiPakaian (
    idPakaian          INT PRIMARY KEY AUTO_INCREMENT,
    nama               VARCHAR(100),
    section            VARCHAR(50),
    category           VARCHAR(50),
    visibility         VARCHAR(20),
    foto               VARCHAR(255),
    linkItem           VARCHAR(255),
    idPengguna         INT,
    FOREIGN KEY (idPengguna) REFERENCES Pengguna(idPengguna)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
);

CREATE TABLE Lookbook (
    idLookbook         INT PRIMARY KEY AUTO_INCREMENT,
    idStylist          INT,
    nama               VARCHAR(100),
    description        TEXT,
    kataKunci          VARCHAR(100),
    FOREIGN KEY (idPakaian) REFERENCES KoleksiPakaian(idPakaian),
    FOREIGN KEY (idStylist) REFERENCES Stylist(idStylist)
);

CREATE TABLE VideoFashion (
    idVideoFashion     INT PRIMARY KEY AUTO_INCREMENT,
    idPengguna         INT,
    deskripsi          TEXT,
    tag                VARCHAR(100),
    formatFile         VARCHAR(50),
    ukuranFile         INT,
    FOREIGN KEY (idPengguna) REFERENCES Pengguna(idPengguna)
);

CREATE TABLE Komentar (
    idKomentar         INT PRIMARY KEY AUTO_INCREMENT,
    idVideoFashion     INT,
    idPengguna         INT,
    isiKomentar        VARCHAR(500),
    tanggalKomentar    DATETIME,
    FOREIGN KEY (idVideoFashion) REFERENCES VideoFashion(idVideoFashion),
    FOREIGN KEY (idPengguna) REFERENCES Pengguna(idPengguna)
);

CREATE TABLE Pesan (
    idPesan            INT PRIMARY KEY AUTO_INCREMENT,
    idPengguna         INT,
    idStylist          INT,
    isiPesan           TEXT,
    lampiranPesan      VARCHAR(255),
    waktukirim         DATETIME,
    statusBacaPengguna BOOLEAN DEFAULT FALSE,
    statusBacaStylist  BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (idPengguna) REFERENCES Pengguna(idPengguna),
    FOREIGN KEY (idStylist) REFERENCES Stylist(idStylist)
);

CREATE TABLE PencarianVideoFashion (
    idPencarian        INT PRIMARY KEY AUTO_INCREMENT,
    idPengguna         INT,
    idVideoFashion     INT,
    kataKunci          VARCHAR(100),
    FOREIGN KEY (idPengguna) REFERENCES Pengguna(idPengguna),
    FOREIGN KEY (idVideoFashion) REFERENCES VideoFashion(idVideoFashion)
);

CREATE TABLE WishlistVideo (
    idVideowishlist   INT PRIMARY KEY AUTO_INCREMENT,
    idPengguna        INT,
    idVideoFashion     INT,
    tanggal_ditambahkan DATETIME,
    FOREIGN KEY (idPengguna) REFERENCES Pengguna(idPengguna),
    FOREIGN KEY (idVideoFashion) REFERENCES VideoFashion(idVideoFashion)
);

CREATE TABLE WishlistItem (
    idItemwishlist    INT PRIMARY KEY AUTO_INCREMENT,
    idPengguna        INT,
    idLookbook          INT,
    tanggal_ditambahkan DATETIME,
    FOREIGN KEY (idPengguna) REFERENCES Pengguna(idPengguna),
    FOREIGN KEY (idLookbook) REFERENCES Lookbook(idLookbook)
);
